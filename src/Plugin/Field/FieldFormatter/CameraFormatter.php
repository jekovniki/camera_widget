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
    $element['#attached']['library'][] = 'camera_widget/camera_widget';

    foreach($items as $delta => $item) {
      $element[$delta] = [
        '#markup' => '<img class="camera_widget" src=""/>
        <code class="base64image cam">' . $item->value . '</code>
        <code class="width cam">' .$item->width .'</code>
        <code class="height cam">' .$item->height .'</code>
        '
      ];
    }

    return $element;
  }

}