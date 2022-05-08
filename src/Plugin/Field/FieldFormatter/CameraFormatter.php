<?php

namespace Drupal\camera_widget\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'field_camera_field_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "camera_formatter",
 *   module = "camera_widget",
 *   label = @Translation("Camera widget"),
 *   field_types = {
 *     "camera"
 *   }
 * )
 */
class CameraFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element  = [];

    foreach($items as $delta => $item) {
      $element[$delta] = [
        '#type' => 'html_tag',
        '#tag' => 'img',
        '#attributes' => [
          'src' => $item->value,
          'width' => $item->width,
          'height' => $item->height,
        ]
        ];
    }

    return $element;
  }

}