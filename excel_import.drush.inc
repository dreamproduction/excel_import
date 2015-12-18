<?php

use Drupal\excel_import\Entity\ImportFileEntity;
use Drupal\excel_import\Entity\MappingEntity;
use Drupal\excel_import\Parser\PHPExcelParser;

/**
 * Implements hook_drush_command().
 */
function excel_import_drush_command() {
  $items = array();

  $items['import-import-type'] = array(
    'description' => dt('Execute all import sources for a specific import type.'),
    'callback' => 'drush_excel_import_type',
    'aliases' => array('iit'),
    'arguments' => array(
      'type' => 'The import type id.',
    ),
  );

  return $items;
}

/**
 * Runs all the imports for a specific import_type.
 */
function drush_excel_import_type($import_type_id = NULL) {
  // Set error for import type if is not set to drush command.
  if(!isset($import_type_id) || empty($import_type_id)){    
    return drush_set_error('error', dt('The import type id is missing.'));
  }

  // Loads all entities for the specified import type.
	$query = new EntityFieldQuery();
  $query->entityCondition('entity_type', ImportFileEntity::ENTITY_TYPE)
    ->entityCondition('bundle', ImportFileEntity::BUNDLE)
    ->entityCondition('field_import_type', $import_type_id);
  $results = $query->execute();

  $import_file_ids = array_keys($results[ImportFileEntity::ENTITY_TYPE]);
  $import_files = entity_load(ImportFileEntity::ENTITY_TYPE, $import_file_ids);

  module_load_include('inc', 'excel_import', 'includes/excel_import.import');

  // Run all the imports for the specified import type.
  foreach ($import_files as $import_file) {
    $batch_context = array();
  
    $wrapper = entity_metadata_wrapper(ImportFileEntity::ENTITY_TYPE, $import_file);
    $import_type = $wrapper->field_import_type->value();
    $mapping_entity = excel_import_load_mapping($import_type);
    // Set error for mapping definition if there is none defined.
    if (empty($mapping_entity)){
      return drush_set_error('error', dt('A mapping should be defined for the import type id @import_type_id, before import run.', array('@import_type_id' => $import_type_id)));
    }
    $mapping_wrapper = entity_metadata_wrapper(MappingEntity::ENTITY_TYPE, $mapping_entity);
    $mapping_data = $mapping_wrapper->field_mapping->value();

    $destination_data = $wrapper->field_import_type->field_destination_bundle->value();
    $import_processor = $wrapper->field_import_type->field_import_processor->value();

    $uri = excel_import_get_uri($import_file);
    // Get the number of records in the file.
    $header_keys = TRUE;
    $parser = new PHPExcelParser($uri, $header_keys);
    $count = $parser->getCount();

    $offset = 0;
    $limit = 20;
    while($offset < $count) {
      $context = [
        'uri' => $uri,
        'entity_type' => $destination_data['entity_type'],
        'bundle' => $destination_data['bundle'],
        'mapping' => $mapping_data,
        'import_processor' => $import_processor,
        'offset' => $offset,
        'limit' => $limit,
        'import_file' => $import_file,
      ];
      excel_import_do_import($context, $batch_context);
      $offset += $limit;
    }

    excel_import_do_import_finish($success = TRUE, $batch_context['results'], $operations = NULL);
  }
}
