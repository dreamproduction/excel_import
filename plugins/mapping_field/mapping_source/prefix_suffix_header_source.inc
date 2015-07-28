<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_source plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingSource
 */

$plugin = [
  'title' => t('Select column from Header row, with prefix and suffix'),
  'description' => t('Mapping plugin for header source, with prefix and suffix.'),
  'class' => 'Drupal\excel_import\MappingSource\HeaderPrefixSuffix',
];
