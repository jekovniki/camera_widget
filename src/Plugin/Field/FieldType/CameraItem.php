<?php

namespace Drupal\camera_widget\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * @FieldType(
 *      id = "camera",
 *      label = @Translation("Camera widget"),
 *      default_formatter = "camera_formatter",
 *      default_widget = "camera_widget"
 * )
 */

class CameraItem extends FieldItemBase {
    /**
     * {@inheritdoc}
     */
    public static function schema(FieldStorageDefinitionInterface $field_definition) {
        return [
            'columns' => [
                'value' => [
                    'type' => 'text',
                    'size' => 'medium',
                ],
                'width' => [
                    'type' => 'text',
                    'size' => 'tiny',
                ],
                'height' => [
                    'type' => 'text',
                    'size' => 'tiny',
                ]
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
        $properties['value'] = DataDefinition::create('string')
        ->setLabel(t('Camera widget'));
        $properties['width'] = DataDefinition::create('string')
        ->setLabel(t('Picture width'));
        $properties['height'] = DataDefinition::create('string')
        ->setLabel(t('Picture height'));

        return $properties;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty() {
        $value = $this->get('value')->getValue();

        return $value === NULL || $value === '';
    }
}