<?php

/**
 * @file
 * Contains the cleaner callback for the excel_import module.
 */

function excel_import_do_clean($context) {
  $header_keys = TRUE;
  $parser = new \Drupal\excel_import\Parser\PHPExcelParser($context['uri'], $header_keys);
  $rows = $parser->getRows();
  $plugin = excel_import_get_plugin('cleaner_processor', 'clean_not_existing');
  $processor = new $plugin['class']($rows, $context['mapping'], $context['entity_type'], $context['bundle'], $context['import_file']);
  $processor->processEntities();
}
