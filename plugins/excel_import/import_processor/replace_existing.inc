<?php

/**
 * @file
 * Plugin definition and callbacks for an excel_import:import_processor plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginImportProcessor
 */

$plugin = [
  'title' => t('Import processor: ReplaceExisting'),
  'description' => t('Import processor plugin that replaces existing entities.'),
  'class' => 'Drupal\excel_import\Importer\ReplaceExisting',
];