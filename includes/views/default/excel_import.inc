<?php

/**
 * @file
 * Contains a view listing the import files for an import type.
 */

$view = new view();
$view->name = 'excel_import';
$view->description = '';
$view->tag = 'default';
$view->base_table = 'eck_excel_import';
$view->human_name = 'Excel import';
$view->core = 7;
$view->api_version = '3.0';
$view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

/* Display: Master */
$handler = $view->new_display('default', 'Master', 'default');
$handler->display->display_options['use_more_always'] = FALSE;
$handler->display->display_options['access']['type'] = 'none';
$handler->display->display_options['cache']['type'] = 'none';
$handler->display->display_options['query']['type'] = 'views_query';
$handler->display->display_options['exposed_form']['type'] = 'basic';
$handler->display->display_options['pager']['type'] = 'full';
$handler->display->display_options['pager']['options']['items_per_page'] = '50';
$handler->display->display_options['pager']['options']['offset'] = '0';
$handler->display->display_options['pager']['options']['id'] = '0';
$handler->display->display_options['pager']['options']['quantity'] = '9';
$handler->display->display_options['style_plugin'] = 'table';
$handler->display->display_options['style_options']['columns'] = array(
  'id' => 'id',
);
$handler->display->display_options['style_options']['default'] = '-1';
$handler->display->display_options['style_options']['info'] = array(
  'id' => array(
    'sortable' => 0,
    'default_sort_order' => 'asc',
    'align' => '',
    'separator' => '',
    'empty_column' => 0,
  ),
);
$handler->display->display_options['style_options']['sticky'] = TRUE;
/* Field: Import file: Id */
$handler->display->display_options['fields']['id']['id'] = 'id';
$handler->display->display_options['fields']['id']['table'] = 'eck_excel_import';
$handler->display->display_options['fields']['id']['field'] = 'id';
/* Field: Import file: Title */
$handler->display->display_options['fields']['title']['id'] = 'title';
$handler->display->display_options['fields']['title']['table'] = 'eck_excel_import';
$handler->display->display_options['fields']['title']['field'] = 'title';
/* Field: Import file: Import status */
$handler->display->display_options['fields']['field_import_status']['id'] = 'field_import_status';
$handler->display->display_options['fields']['field_import_status']['table'] = 'field_data_field_import_status';
$handler->display->display_options['fields']['field_import_status']['field'] = 'field_import_status';
/* Field: Import file: Review link */
$handler->display->display_options['fields']['review_link']['id'] = 'review_link';
$handler->display->display_options['fields']['review_link']['table'] = 'eck_excel_import';
$handler->display->display_options['fields']['review_link']['field'] = 'review_link';
$handler->display->display_options['fields']['review_link']['label'] = 'Review';
/* Field: Import file: Import link */
$handler->display->display_options['fields']['import_link']['id'] = 'import_link';
$handler->display->display_options['fields']['import_link']['table'] = 'eck_excel_import';
$handler->display->display_options['fields']['import_link']['field'] = 'import_link';
$handler->display->display_options['fields']['import_link']['label'] = 'Import';
/* Field: Import file: Delete link */
$handler->display->display_options['fields']['delete_link']['id'] = 'delete_link';
$handler->display->display_options['fields']['delete_link']['table'] = 'eck_excel_import';
$handler->display->display_options['fields']['delete_link']['field'] = 'delete_link';
$handler->display->display_options['fields']['delete_link']['label'] = 'Delete';
/* Contextual filter: Field: Import type (field_import_type) */
$handler->display->display_options['arguments']['field_import_type_target_id']['id'] = 'field_import_type_target_id';
$handler->display->display_options['arguments']['field_import_type_target_id']['table'] = 'field_data_field_import_type';
$handler->display->display_options['arguments']['field_import_type_target_id']['field'] = 'field_import_type_target_id';
$handler->display->display_options['arguments']['field_import_type_target_id']['default_action'] = 'empty';
$handler->display->display_options['arguments']['field_import_type_target_id']['default_argument_type'] = 'fixed';
$handler->display->display_options['arguments']['field_import_type_target_id']['summary']['number_of_records'] = '0';
$handler->display->display_options['arguments']['field_import_type_target_id']['summary']['format'] = 'default_summary';
$handler->display->display_options['arguments']['field_import_type_target_id']['summary_options']['items_per_page'] = '25';
/* Filter criterion: Import file: excel_import type */
$handler->display->display_options['filters']['type']['id'] = 'type';
$handler->display->display_options['filters']['type']['table'] = 'eck_excel_import';
$handler->display->display_options['filters']['type']['field'] = 'type';
$handler->display->display_options['filters']['type']['value'] = array(
  'excel_import' => 'excel_import',
);
$translatables['excel_import'] = array(
  t('Master'),
  t('more'),
  t('Apply'),
  t('Reset'),
  t('Sort by'),
  t('Asc'),
  t('Desc'),
  t('Items per page'),
  t('- All -'),
  t('Offset'),
  t('« first'),
  t('‹ previous'),
  t('next ›'),
  t('last »'),
  t('Id'),
  t('.'),
  t(','),
  t('Title'),
  t('Import status'),
  t('Review'),
  t('Import'),
  t('Delete'),
  t('All'),
);
