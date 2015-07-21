<?php
namespace Drupal\excel_import\Entity;

class MappingEntity extends BaseEntity {

  const ENTITY_TYPE = 'excel_mapping';
  const BUNDLE = 'excel_mapping';

  protected function getEntityTypeDefinition() {
    return [
      'label' => 'Import mapping',
      'properties' => [
        'title' => [
          'label' => 'Title',
          'type' => 'text',
          'behavior' => 'title',
        ],
        'created' => [
          'label' => 'Created',
          'type' => 'integer',
          'behavior' => 'created',
        ],
      ],
    ];
  }

  protected function getBundleDefinition() {
    return [
      'label' => 'Import mapping',
    ];
  }

  protected function getFieldsDefinition() {
    return [
      'field_import_type' => [
        'type' => 'entityreference',
        'field' => [
          'field_name' => 'field_import_type',
          'settings' => [
            'target_type' => ImportTypeEntity::ENTITY_TYPE,
            'handler_settings' => [
              'target_bundles' => [
                ImportTypeEntity::BUNDLE => ImportTypeEntity::BUNDLE,
              ],
              'sort' => [
                'type' => 'field',
                'field' => 'title_field:value',
                'direction' => 'ASC',
              ]
            ],
          ],
        ],
        'instance' => [
          'label' => 'Import type',
          'required' => TRUE,
          'widget' => [
            'type' => 'entityreference_autocomplete',
          ],
          'display' => [
            'default' => [
              'label' => 'hidden',
              'type' => 'hidden',
              'weight' => 1,
            ],
          ],
        ],
      ],
      // @TODO: add field_mapping definition.
    ];
  }
}
