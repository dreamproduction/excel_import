<?php

/**
 * @file
 * Plugin definition and callbacks for an excel_import:import_processor plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginImportProcessor
 */

$plugin = [
  'title' => t('Import processor: SkipExisting'),
  'description' => t('Import processor plugin that skips existing entities.'),
  'class' => 'Drupal\excel_import\Importer\SkipExisting',
];