<?php

namespace Drupal\excel_import\Entity;

class ImportFileEntity extends BaseEntity {

  const ENTITY_TYPE = 'excel_import';
  const BUNDLE = 'excel_import';

  protected function getEntityTypeDefinition() {
    return [
      'label' => 'Import source',
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
        'uid' => [
          'label' => 'Author',
          'type' => 'integer',
          'behavior' => 'author',
        ],
      ],
    ];
  }

  protected function getBundleDefinition() {
    return [
      'label' => 'Import source',
    ];
  }

  protected function getFieldsDefinition() {
    return [
      'field_import_source' => [
        'type' => 'list_text',
        'field' => [
          'field_name' => 'field_import_source',
          'settings' => [
            'allowed_values' => [
              IMPORT_SOURCE_FILE => 'Import from File',
              IMPORT_SOURCE_URL => 'Import from URL',
            ],
          ],
        ],
        'instance' => [
          'label' => 'Import source',
          'required' => TRUE,
          'widget' => [
            'type' => ' options_buttons',
          ],
          'default_value' => [['value' => 'file']],
        ],
      ],
      'field_import_file' => [
        'type' => 'file',
        'field' => [
          'field_name' => 'field_import_file',
          'settings' => [
            'uri_scheme' => 'public',
          ],
        ],
        'instance' => [
          'label' => 'Import file',
          'required' => FALSE,
          'widget' => [
            'type' => 'file_generic',
          ],
          'settings' => [
            'file_directory' => 'import_files',
            'file_extensions' => 'xls xlsx',
          ],
        ]
      ],
      'field_import_url' => [
        'type' => 'link_field',
        'field' => [
          'field_name' => 'field_import_url',
          'settings' => [
            'attributes' => [
              'target' => 'default',
            ],
          ],
        ],
        'instance' => [
          'label' => 'Import URL',
          'required' => FALSE,
          'widget' => [
            'type' => ' link_field',
          ],
          'settings' => [
            'title' => 'none',
          ]
        ],
      ],
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
      'field_import_status' => [
        'type' => 'list_text',
        'field' => [
          'field_name' => 'field_import_status',
          'settings' => [
            'allowed_values' => [
              'not_imported' => 'Not imported',
              'imported' => 'Imported',
            ],
          ],
        ],
        'instance' => [
          'label' => 'Import status',
          'required' => TRUE,
          'widget' => [
            'type' => ' options_buttons',
          ],
          'default_value' => [['value' => 'not_imported']],
        ],
      ],
      'field_import_date' => [
        'type' => 'datestamp',
        'field' => [
          'field_name' => 'field_import_date',
          'settings' => [
            'todate' => '',
            'granularity' => [
              'day' => 'day',
              'month' => 'month',
              'year' => 'year',
              'hour' => 'hour',
              'minute' => 'minute',
              'second' => 'second',
            ],
            'tz_handling' => 'site',
            'timezone_db' => 'UTC',
          ],
        ],
        'instance' => [
          'label' => 'Import date',
          'widget' => [
            'type' => 'date_popup',
            'settings' => [
              'input_format' => 'Y-m-d H:i:s',
              'default_value' => 'blank',
            ],
          ],
        ],
      ],
    ];
  }

}
