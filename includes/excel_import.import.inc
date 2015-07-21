<?php

/**
 * @file
 * Contains the import callback for the excel_import module.
 */

function excel_import_do_import($context, &$batch_context) {
  // Put the import_file in the batch context.
  if (!isset($batch_context['results']['import_file'])) {
    $batch_context['results']['import_file'] = $context['import_file'];
  }

  $header_keys = TRUE;
  $parser = new \Drupal\excel_import\Parser\PHPExcelParser($context['uri'], $header_keys);

  $rows = $parser->getRows($context['offset'], $context['limit']);
  foreach ($rows as $row) {
    $importer = new $context['import_processor']($row, $context['mapping'], $context['entity_type'], $context['bundle']);
    $importer->processEntity();
  }
}
function excel_import_do_import_finish($success, $results, $operations) {
  if ($success) {
    $import_file = $results['import_file'];
    $wrapper = entity_metadata_wrapper(\Drupal\excel_import\Entity\ImportFileEntity::ENTITY_TYPE, $import_file);
    $wrapper->field_import_status->set('imported');
    $wrapper->save();
  }
}