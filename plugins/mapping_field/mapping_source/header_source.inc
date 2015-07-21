<?php

/**
 * @file
 * Plugin definition and callbacks for a mapping_field:mapping_type plugin.
 *
 * @ingroup CToolsPlugin CToolsPluginMappingType
 */

$plugin = [
  'title' => t('Mapping source: Header'),
  'description' => t('Mapping plugin for header source.'),
  'class' => 'Drupal\excel_import\MappingSource\Header',
];
