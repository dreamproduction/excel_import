<?php

/**
 * @file
 * Contains page callbacks for excel_import.
 */
use Drupal\excel_import\Entity\ImportFileEntity;
use Drupal\excel_import\Entity\ImportTypeEntity;
use Drupal\excel_import\Entity\MappingEntity;
use Drupal\excel_import\Parser\PHPExcelParser;

/**
 * Page callback for the import overview page.
 * @param $import_type
 * @return array
 */
function excel_import__import_overview_page($import_type) {
  $return = [];

  $entity = entity_create(ImportFileEntity::ENTITY_TYPE, ['type' => ImportFileEntity::BUNDLE]);
  $form = drupal_get_form('eck__entity__form_add_' . ImportFileEntity::ENTITY_TYPE . '_' . ImportFileEntity::BUNDLE, $entity, ['import_type' => $import_type]);

  $return['add_form'] = [
    '#theme' => 'fieldset',
    '#children' => drupal_render($form),
    '#title' => t('+ Upload file for import'),
    '#attributes' => [
      'class' => ['import-file-add-fieldset', 'collapsible', 'collapsed'],
    ],
    '#attached' => [
      'library' => [['system', 'drupal.collapse']],
    ],
  ];

  $return['listing'] = ['#markup' => views_embed_view('excel_import', 'default', $import_type->id)];

  return $return;
}

function excel_import__review_page($import_file) {
  $build = array();

  if (!class_exists('PHPExcel_IOFactory')) {
    drupal_set_message(t('File parsing failed. Please make sure composer-manager is installed, and the dependecies are installed.'));
    return $build;
  }

  $build['back'] = [
    '#type' => 'markup',
    '#markup' => l(t('Back to overview'), 'admin/content/import'),
  ];

  $wrapper = entity_metadata_wrapper(ImportFileEntity::ENTITY_TYPE, $import_file);
  $file_data = $wrapper->field_import_file->value();
  $uri = drupal_realpath($file_data['uri']);

  $header_keys = TRUE;
  $parser = new PHPExcelParser($uri, $header_keys);

  // First find the total number of items and initialize the pager.
  $total = $parser->getCount();
  $num_per_page = 100;
  $page = pager_default_initialize($total, $num_per_page);

  // Next, retrieve and display the items for the current page.
  $offset = $num_per_page * $page;

  $build['table'] = [
    '#theme' => 'table',
    '#header' => $parser->getHeader(),
    '#rows' => $parser->getRows($offset, $num_per_page),
  ];

  // Display the pager controls.
  $build['pager'] = [
    '#theme' => 'pager',
  ];

  $options = [
    'query' => ['destination' => 'admin/content/import'],
  ];

  $build['import'] = [
    '#type' => 'markup',
    '#markup' => l(t('Import'), 'admin/content/import/' . $import_file->id . '/import', $options),
  ];

  return $build;
}

function excel_import__import_page($import_file) {
  $build = [];
  return $build;
}

function excel_import__mapping_page($import_type) {
  $return = [];

  $wrapper = entity_metadata_wrapper(ImportTypeEntity::ENTITY_TYPE, $import_type);
  $file_data = $wrapper->field_header_sample->value();
  $entity_type_bundle = $wrapper->field_destination_bundle->value() ;

  $context = [
    'import_type' => $import_type,
    'header_file_uri' => drupal_realpath($file_data['uri']),
    'destination' => [
      'entity_type' => $entity_type_bundle['entity_type'],
      'bundle' => $entity_type_bundle['bundle'],
    ]
  ];
  $entity = entity_create(MappingEntity::ENTITY_TYPE, ['type' => MappingEntity::BUNDLE]);
  $return['add_form'] = drupal_get_form('eck__entity__form_add_' . MappingEntity::ENTITY_TYPE . '_' . MappingEntity::BUNDLE, $entity, $context);
  // @TODO: If mapping already exists, use edit form.

  return $return;
}