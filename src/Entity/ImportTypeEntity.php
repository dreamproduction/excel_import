<?php

namespace Drupal\excel_import\Entity;

class ImportTypeEntity extends BaseEntity{

  const ENTITY_TYPE = 'import_type';
  const BUNDLE = 'import_type';

  protected function getEntityTypeDefinition() {
    return [
      'label' => 'Import type',
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
      'label' => 'Import type',
    ];
  }

  protected function getFieldsDefinition() {
    return [
      'field_destination_bundle' => [
        'type' => 'entity_type_bundle',
        'field' => [
          'field_name' => 'field_destination_bundle',
        ],
        'instance' => [
          'label' => 'Destination bundle',
          'required' => TRUE,
          'widget' => [
            'type' => 'entity_type_bundle',
          ],
        ]
      ],
      'field_header_sample' => [
        'type' => 'file',
        'field' => [
          'field_name' => 'field_header_sample',
          'settings' => [
            'uri_scheme' => 'public',
          ],
        ],
        'instance' => [
          'label' => 'Header file',
          'required' => TRUE,
          'widget' => [
            'type' => 'file_generic',
          ],
          'settings' => [
            'file_directory' => 'import_files',
            'file_extensions' => 'xls xlsx',
          ],
        ]
      ],
      'field_import_processor' => [
        'type' => 'list_text',
        'field' => [
          'field_name' => 'field_import_processor',
          'settings' => [
            'allowed_values' => [
              'skip_existing' => 'Skip (do not import)',
              'replace_existing' => 'Replace (delete and create from scratch)',
              'update_existing' => 'Update (overwrite just the values present in the import)',
              'duplicate_existing' => 'Duplicate (just create a new entity for each row, without checking for existing entities)',
            ],
          ],
        ],
        'instance' => [
          'label' => 'When an existing entity is found',
          'required' => TRUE,
          'widget' => [
            'type' => ' options_buttons',
          ],
          'default_value' => [['value' => 'skip']],
        ],
      ],
      'field_mapping_type' => [
        'type' => 'list_text',
        'field' => [
          'field_name' => 'field_mapping_type',
          'settings' => [
            'allowed_values' => [
              'columns' => 'Columns (Numeric)',
              'header' => 'Header (The text in the first row of the file)',
            ],
          ],
        ],
        'instance' => [
          'label' => 'Mapping based on',
          'required' => TRUE,
          'widget' => [
            'type' => ' options_buttons',
          ],
          'default_value' => [['value' => 'header']],
        ],
      ],
    ];
  }

}
