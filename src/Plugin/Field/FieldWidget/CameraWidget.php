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
    $width = $items[$delta]->width !== null ? $items[$delta]->width : '100%';
    $height = $items[$delta]->height !== null ? $items[$delta]->height : '100%';
 
    $element['#attached']['library'][] = 'camera_widget/camera_widget_admin';

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
      '#size' => 19,
      '#maxlength' => 10000000,
      '#attributes' => [
        'id' => ['generate_url'],
        'style' => ['z-index:0; pointer-events:none; position:relative;'],
      ],
    ];

    $element['width'] = [
      '#type' => 'textfield',
      '#default_value' => $width,
      '#title' => t('Width'),
      '#description' => t('Width of the photo when displayed on the page. Leave empty for 100% of the field. Add pt, px, % and etc. after the number.'),
      '#size' => 10,
      '#maxlength' => 10,
      '#attributes' => [
        'id' => ['camera_width']
      ]
    ];

    $element['height'] = [
      '#type' => 'textfield',
      '#default_value' => $height,
      '#title' => t('Height'),
      '#description' => t('Height of the photo when displayed on the page. Leave empty for 100% of the field. Add pt, px, % and etc. after the number.'),
      '#size' => 10,
      '#maxlength' => 10,
      '#attributes' => [
        'id' => ['camera_height']
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
      if (empty($value['width'])) {
        unset($values[$key]['width']);
      }
      if (empty($value['height'])) {
        unset($values[$key]['height']);
      }
    }

    return $values;
  }
}
