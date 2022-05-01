<?php

namespace Drupal\camera_widget\Plugin\Field\FieldWidget;

use Drupal\Component\Render\PlainTextOutput;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\File\FileSystemInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Utility\Html;
use Drupal\Core\Template\Attribute;
use Drupal\file\Entity\File;
use Drupal\image\Plugin\Field\FieldWidget\ImageWidget;

/**
 * Plugin implementation of the 'image_image' widget.
 *
 * @FieldWidget(
 *   id = "camera_widget",
 *   label = @Translation("Camera"),
 *   field_types = {
 *     "image"
 *   }
 * )
 */
class CameraWidget extends ImageWidget {
    
    /**
    * {@inheritdoc}
    */
   public static function defaultSettings() {
    return array(
     'width' => 750,
     'show_remove_btn' => FALSE
   ) + parent::defaultSettings();
 }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $elements = [];

    $elements['width'] = [
      '#type' => 'number',
      '#title' => t('Screen width'),
      '#default_value' => $this->getSetting('Width'),
      '#required' => TRUE,
      '#description' => t('Screen width.'),
    ];

    return $elements;
  }

  public function settingsSummary() {
    $summary = [];

    if(!empty($this->getSetting('width'))) {
      $summary[] = t('Width: %width', ['%width' => $this->getSetting('width')]);
    }

    return $summary;
  }

  // protected function formMultipleElements(FieldItemListInterface $items, array &$form, FormStateInterface $form_state) {
  //   $cardinality = $this->fieldDefinition->getFieldStorageDefinition()->getCardinality();
  //   switch ($cardinality) {
  //     case FieldStorageDefinitionInterface::CARDINALITY_UNLIMITED:
  //       $max = count($items);
  //       $is_multiple = TRUE;
  //       break;

  //     default:
  //       $max = $cardinality - 1;
  //       $is_multiple = ($cardinality > 1);
  //       break;
  //   }

  //   $elements['container'] = [
  //     '#type' => 'html_tag',
  //     '#tag' => 'div',
  //     '#attributes' => [
  //       'class' => [
  //         'contentarea'
  //       ]
  //     ],
  //   ];

  //   $elements['container']['h2'] = [
  //     '#type' => 'html_tag',
  //     '#tag' => 'h2',
  //     '#value' => t('WebRTC: Still photo capture'),
  //   ];

  //   $elements['container']['camera'] = [
  //     '#type' => 'html_tag',
  //     '#tag' => 'div',
  //     '#attributes' => [
  //       'class' => [
  //         'camera'
  //       ]
  //     ],
  //   ];
  //   $elements['container']['camera']['video'] = [
  //     '#type' => 'html_tag',
  //     '#tag' => 'video',
  //     '#attributes' => [
  //       'id' => [
  //         'video'
  //       ]
  //     ],
  //   ];
  //   $elements['container']['camera']['button'] = [
  //     '#type' => 'html_tag',
  //     '#tag' => 'button',
  //     '#attributes' => [
  //       'id' => [
  //         'startbutton'
  //       ]
  //     ],
  //     '#value' => t('Take picture'),
  //   ];

  //   $elements['container']['canvas'] = [
  //     '#type' => 'html_tag',
  //     '#tag' => 'canvas',
  //     '#attributes' => [
  //       'id' => [
  //         'canvas'
  //       ]
  //     ],
  //   ];

  //   $elements['container']['output'] = [
  //     '#type' => 'html_tag',
  //     '#tag' => 'div',
  //     '#attributes' => [
  //       'class' => [
  //         'output'
  //       ]
  //     ],
  //   ];
  //   $elements['container']['output']['img'] = [
  //     '#type' => 'html_tag',
  //     '#tag' => 'img',
  //     '#attributes' => [
  //       'id' => [
  //         'photo',
  //       ],
  //       'alt' => ['The screen capture will appear in this box.'],
  //     ],
  //   ];

  //   $element = [];

  //   foreach (range(0, $max) as $delta) {
  //     $elements[$delta] = [
  //       '#type' => 'container',
  //       '#attributes' => ['class' => ['container', 'col-sm']],
  //       '#weight' => $delta,
  //     ] + $this->formSingleElement($items, $delta, $elements, $form, $form_state);
  //   }

  //   return $elements;
  // }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    
    $field_settings = $this->getFieldSettings();

    $values = $items->getValue();
    $width = 500;
    $height = 250;
    if (!empty($max_resolution)) {
      [$width, $height] = explode('x', $max_resolution);
    }
    if (!empty($min_resolution)) {
      [$width, $height] = explode('x', $min_resolution);
    }
    $settings = $field_settings["default_image"];
    if (!empty($settings['width'])) {
      $width = $settings['width'];
    }
    if (empty($element['#upload_validators'])) {
      $element['#upload_validators'] = FALSE;
    }
    $field_name = $this->fieldDefinition->getName();

    $id = Html::getUniqueId($field_name);
    $fid = $img = '';
    if (!empty($values[$delta])) {
      $fid = $values[$delta]['target_id'];
      $file = File::load($fid);
    }
    if (empty($file) && !empty($settings["uuid"])) {
      $file = \Drupal::service('entity.repository')
        ->loadEntityByUuid('file', $settings["uuid"]);
    }
    $imgSrc = !empty($file) ? $file->createFileUrl() : '';

    $attributes = new Attribute([
      'id' => $id,
      'width' => $width,
      'height' => $height,
      'class' => ["camera-pad"],
    ]);
    foreach ($this->settings as $attribute => $settingValue) {
      $attributes['data-' . $attribute] = $settingValue;
    }
    $camera_pad = [
      '#theme' => 'camera',
      '#attributes' => $attributes,
      '#canvas_id' => $id,
      '#camera_src' => $imgSrc,
      '#height' => $height,
      '#width' => $width,
      '#settings' => $this->settings,
    ];

    $element['#attached']['drupalSettings']['camera_pad'][$id] = $this->settings;

    $element += [
      '#type' => 'hidden',
      '#required' => FALSE,
      '#attributes' => [
        'id' => $id . '-sign',
        'class' => ['signature-storage'],
      ],
      '#title_field' => $field_settings['title_field'],
      '#title_field_required' => $field_settings['title_field_required'],
    ];

    $element['#attached']['library'][] = 'camera_widget/camera_widget';

    $file_extensions = $this->fieldDefinition->getSetting("file_extensions");
    $elements = [
      'value' => $element,
      'img' => $camera_pad,
      '#field_parents' => $element['#field_parents'],
      '#upload_validators' => [
        'file_validate_extensions' => [$file_extensions],
      ],
      '#required' => FALSE,
    ];

    if ($field_settings['alt_field']) {
      $elements['alt'] = [
        '#title' => t('Alternative text'),
        '#type' => 'textfield',
        '#default_value' => !empty($values[$delta]['alt']) ? $values[$delta]['alt'] : '',
        '#description' => t('Short description of the image used by screen readers and displayed when the image is not loaded. This is important for accessibility.'),
        '#maxlength' => 512,
        '#required' => $field_settings['alt_field_required'],
      ];
    }
    if ($field_settings['title_field']) {
      $elements['title'] = [
        '#type' => 'textfield',
        '#title' => t('Title'),
        '#default_value' => !empty($values[$delta]['title']) ? $values[$delta]['title'] : '',
        '#description' => t('The title is used as a tool tip when the user hovers the mouse over the image.'),
        '#maxlength' => 1024,
        '#required' => $field_settings['title_field_required'],
      ];
    }

    $elements['image_url'] = [
      '#type' => 'textfield',
      '#title' => t('Generated url'),
      '#default_value' => !empty($values[$delta]['gen_url']) ? $values[$delta]['gen_url'] : '',
      '#description' => t('Generated url'),
      '#maxlength' => 200000,
      '#attributes' => [
        'id' => ['generate_url']
      ]
    ];

    // In case modify.
    if (!empty($fid)) {
      $file = File::load($fid);
      $elements['value']['#default_value'] = $fid;
      $array_parents = array_merge(
        $element["#field_parents"],
        [$field_name, $delta]
      );
      $parents_prefix = implode('_', $array_parents);
      // Generate a unique wrapper HTML ID.
      $ajax_settings = [
        'callback' => ['Drupal\file\Element\ManagedFile', 'uploadAjaxCallback'],
        'options' => [
          'query' => [
            'element_parents' => implode('/', array_merge(
              $array_parents, ['widget', $delta]
            )),
          ],
        ],
        'wrapper' => Html::getId('edit-' .
          implode('-', $array_parents)
        ),
        'effect' => 'none',
        'progress' => [
          'type' => 'throbber',
          'message' => NULL,
        ],
      ];

      if ($file) {
        $elements['#files'] = [$fid => $file->setTemporary()];
        $elements['#multiple'] = FALSE;
        $elements['#prefix'] = '';
        $elements['#suffix'] = '';
      }
      $elements['fids'] = [
        '#type' => 'hidden',
        '#value' => [$fid],
      ];
    }
    
    return $elements;
  }

}
