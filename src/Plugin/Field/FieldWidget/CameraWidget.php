<?php

namespace Drupal\camera_widget\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'image_image' widget.
 *
 * @FieldWidget(
 *   id = "camera_widget",
 *   label = @Translation("Camera"),
 *   field_types = {
 *     "camera"
 *   }
 * )
 */
class CameraWidget extends WidgetBase {
  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $value = $items[$delta]->value !== null ? $items[$delta]->value : 'Nothing to show';

    $element['#attached']['library'][] = 'camera_widget/camera_widget';

    $element['container'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => [
          'contentarea'
        ],
      ],
    ];

    $element['container']['camera'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => [
          'camera'
        ],
      ],
    ];

    $element['container']['camera']['video'] = [
      '#type' => 'html_tag',
      '#tag' => 'video',
      '#attributes' => [
        'id' => [
          'video'
        ],
      ],
    ];

    $element['container']['camera']['button'] = [
      '#type' => 'html_tag',
      '#tag' => 'button',
      '#attributes' => [
        'id' => [
          'startbutton'
        ],
        'onclick' => [
          'generateUrl()',
        ],
      ],
      '#value' => t('Take picture'),
    ];

    $element['container']['canvas'] = [
      '#type' => 'html_tag',
      '#tag' => 'canvas',
      '#attributes' => [
        'id' => [
          'canvas'
        ],
      ],
    ];

    $element['container']['output'] = [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => [
          'output'
        ],
      ],
    ];
    $element['container']['output']['img'] = [
      '#type' => 'html_tag',
      '#tag' => 'img',
      '#attributes' => [
        'id' => [
          'photo'
        ],
        'alt' => ['The screen capture will appear in this box.'],
      ],
    ];

    $element['value'] = [
      '#type' => 'textfield',
      '#default_value' => $value,
      '#title' => t('Generated url'),
      '#description' => t('Generated url'),
      '#size' => 1000,
      '#maxlength' => 200000,
      '#attributes' => [
        'id' => ['generate_url']
      ]
    ];
    
    return $element;
  }

  public static function validate($element, FormStateInterface $form_state) {
    $value = $element['#value'];

    if (srtlen($value) === 0) {
      $form_state->setValueForElement($element, '');

      return;
    }
  }

  public function messageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as $key => $value) {
      if (empty($value['value'])) {
        unset($values[$key]['value']);
      }
    }

    return $values;
  }
}
