<?php

/**
 * @file
 * Plugin definition and callbacks for an excel_import:cleaner_processor plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginCleanerProcessor
 */

$plugin = [
  'title' => t('Cleaner processor: Clean'),
  'description' => t('Cleaner processor plugin that delete not existing entities.'),
  'class' => 'Drupal\excel_import\Cleaner\Cleaner',
];