<?php

namespace Drupal\camera_widget\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Template\Attribute;
use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatter;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Component\Utility\NestedArray;
use Drupal\file\Entity\File;
use Drupal\file\Plugin\Field\FieldWidget\FileWidget;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Plugin implementation of the 'field_camera_field_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "camera_formatter",
 *   module = "camera_widget",
 *   label = @Translation("Camera widget"),
 *   field_types = {
 *     "image"
 *   }
 * )
 */
class CameraFormatter extends ImageFormatter {

    /**
     * {@inheritdoc}
     */
    public static function defaultSettings() {
        return array(
            'width' => 1200,
            'height' => 720,
        ) + parent::defaultSettings();
    }

    /**
     * {@inheritdoc}
     */
    public function settingsForm(array $form, FormStateInterface $form_state) {
        $elements = parent::settingsForm($form, $form_state);

        $elements['width'] = [
          '#type' => 'number',
          '#step' => '.1',
          '#title' => t('Width'),
          '#default_value' => $this->getSetting('width'),
          '#element_validate' => ['element_validate_number'],
          '#required' => TRUE,
          '#description' => t('Width of screen'),
        ];

        return $elements;
    }

    public function settingsSummary() {
        $summary = parent::settingsSummary();

        if (!empty($this->getSetting('width'))) {
          $summary[] = t('Width : %width', ['%width' => $this->getSetting('width')]);
        }

        return $summary;
    }

  /**
    * {@inheritdoc}
    */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);
    
    $total = count($elements);
    $cardinality = $this->fieldDefinition->get('fieldStorage')->get('cardinality');
    if ($cardinality == -1) {
      $cardinality = $total + 1;
    }
    if ($cardinality > $total) {
      // $elements['#attached']['library'][] = 'camera_widget/camera_widget';

      $height = 300;
      $width = 500;

      $entity = $items->getEntity();
      $settings = $this->fieldDefinition->getSetting("default_image");
      $defData['data-file_directory'] = $this->fieldDefinition->getSetting("file_directory");
      $defData['data-entity_type'] = $this->fieldDefinition->getTargetEntityTypeId();
      $defData['data-bundle'] = $this->fieldDefinition->getTargetBundle();
      $defData['data-field_type'] = $this->fieldDefinition->getType();
      $defData['data-field_name'] = $this->fieldDefinition->getName();
      $defData['data-entity_id'] = $entity->id();
      if (!empty($settings['width'])) {
        $width = $settings['width'];
      }

      $data = [];
      foreach($this->settings as $attribute => $settingValue) {
        if (in_array($attribute, ['image_style', 'image_link'])) {
          continue;
        }
        $data['data-' . $attribute] = $settingValue;
      }
      $id = str_replace('.', '-', $this->fieldDefinition->id()) . '-' . $entity->id();

      foreach(range($total, $cardinality - 1) as $delta) {
        $id .= '-' . $delta;
        $attributes = new Attribute([
          'id' => $id,
          'width' => $width,
          'height' => $height,
          'delta' => $delta,
          'class' => ['camera-pad']
        ] + $data);

        $elements[$delta] = [
          '#type' => 'container',
          '#attributes' => ['class' => ['camera-container']],
          '#weight' => $delta,
          'img' => [
            '#theme' => 'camera',
            '#canvas_id' => $id,
            '#height' => $height,
            '#width' => $width,
            '#settings' => $this->settings + ['is_formatter' => TRUE],
          ],
          ];

          $defData['data-delta'] = $delta;

          $elements[$delta]['camera'] = [
            '#type' => 'html_tag',
            '#tag' => 'img',
            '#attributes' => [
              'id' => $id . '-camera',
              'class' => ['camera-storage'],
              'data-delta' => $delta,
              'src' => $data,
            ] + $defData,
            '#attached' => [
              'drupalSettings' => [
                'camera_pad' => [$id => $this->settings],
              ]
            ]
              ];


      }
    }
    $height = 300;
    $width = 500;

    $elements['camera'] = [
      '#type' => 'html_tag',
      '#tag' => 'img',
      '#attributes' => [
        'width' => $width,
        'height' => $height
      ]
    ];

    return $elements;
  }

}