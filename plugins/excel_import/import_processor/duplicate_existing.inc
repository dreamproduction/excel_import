<?php

/**
 * @file
 * Plugin definition and callbacks for an excel_import:import_processor plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginImportProcessor
 */

$plugin = [
  'title' => t('Import processor: DuplicateExisting'),
  'description' => t('Import processor plugin that duplicates existing entities.'),
  'class' => 'Drupal\excel_import\Importer\DuplicateExisting',
];