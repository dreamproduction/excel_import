<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_source plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingSource
 */

$plugin = [
  'title' => t('Select column from Header row'),
  'description' => t('Mapping plugin for header source.'),
  'class' => 'Drupal\excel_import\MappingSource\Header',
];
