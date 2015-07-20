<?php
/**
 * @file
 * Provide views data and handlers for excel_import module.
 */
use Drupal\excel_import\Entity\ImportFileEntity;

/**
 * Implements hook_views_data_alter().
 */
function excel_import_views_data_alter(&$data) {
  $data['eck_' . ImportFileEntity::ENTITY_TYPE]['review_link'] = array(
    'field' => array(
      'title' => t('Review link'),
      'help' => t('Provide a simple link to review the import file contents.'),
      'handler' => 'excel_import_views_handler_field_link_review',
    ),
  );
  $data['eck_' . ImportFileEntity::ENTITY_TYPE]['import_link'] = array(
    'field' => array(
      'title' => t('Import link'),
      'help' => t('Provide a simple link to import the file.'),
      'handler' => 'excel_import_views_handler_field_link_import',
    ),
  );
}
