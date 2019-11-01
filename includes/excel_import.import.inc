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
    $plugin = excel_import_get_plugin('import_processor', $context['import_processor']);
    $processor = new $plugin['class']($row, $context['mapping'], $context['entity_type'], $context['bundle'], $context['import_file']);
    $processor->processEntity();
  }
}
function excel_import_do_import_finish($success, $results, $operations) {
  if ($success && isset($results['import_file'])) {
    $import_file = $results['import_file'];
    $wrapper = entity_metadata_wrapper(\Drupal\excel_import\Entity\ImportFileEntity::ENTITY_TYPE, $import_file);
    $wrapper->field_import_status->set('imported');
    $wrapper->field_import_date->set(strtotime('NOW'));
    $wrapper->save();
  }
}