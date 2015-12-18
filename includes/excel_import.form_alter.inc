<?php

/**
 * @file
 * Form alters for excel_import module.
 */
use Drupal\excel_import\Entity\ImportFileEntity;
use Drupal\excel_import\Entity\ImportTypeEntity;
use Drupal\excel_import\Entity\MappingEntity;

/**
 * Alter the add form for the import file entity.
 *
 * @param $form
 * @param $form_state
 * @param $form_id
 */
function excel_import_alter_import_file_add_form(&$form, &$form_state, $form_id) {
  $args = $form_state['build_info']['args'];

  // Get a shortcut to the import file being created, and a wrapper for it.
  $entity = &$form_state[ImportFileEntity::ENTITY_TYPE];
  $wrapper = entity_metadata_wrapper(ImportFileEntity::ENTITY_TYPE, $entity);

  // Set the status as not imported, and hide it from the form.
  unset($form['field_import_status']);
  $wrapper->field_import_status->set('not_imported');

  // Hide the date from the form.
  $form['field_import_date']['#access'] = FALSE;

  $form['title']['#access'] = FALSE;

  // We only hide the import type field if we have an import type provided.
  $import_type = isset($form_state['build_info']['args'][1]['import_type']) ? $form_state['build_info']['args'][1]['import_type'] : NULL ;
  if (isset($args[1]) && is_array($args[1]) && isset($args[1]['import_type'])) {

    // Hide the import type field, and set it to the provided import type.
    $form['field_import_type']['#access'] = FALSE;
    $wrapper->field_import_type->set($import_type->id);
  }

  // Show/hide the import source based on its type.
  if (isset($form['field_import_source'])){
    $form['field_import_file']['#states'] = array(
      'visible' => array(
        ':input[name="field_import_source[und]"]' => array('value' => 'file'),
      ),
    );
    $form['field_import_url']['#states'] = array(
      'visible' => array(
        ':input[name="field_import_source[und]"]' => array('value' => 'url'),
      ),
    );
  }
}

/**
 * Alter the add form for the import type entity.
 *
 * @param $form
 * @param $form_state
 * @param $form_id
 */
function excel_import_alter_import_type_add_form(&$form, &$form_state, $form_id) {
  // Rebuild the menu after submission, a new menu route will be added for the
  // new import type under admin/content/import.
  $form['#submit'][] = 'menu_rebuild';
}

/**
 * Alter the add form for the mapping entity.
 *
 * @param $form
 * @param $form_state
 * @param $form_id
 */
function excel_import_alter_mapping_add_form(&$form, &$form_state, $form_id) {
  $args = $form_state['build_info']['args'];

  // Get a shortcut to the mapping entity being created, and a wrapper for it.
  $entity = &$form_state[MappingEntity::ENTITY_TYPE];
  $wrapper = entity_metadata_wrapper(MappingEntity::ENTITY_TYPE, $entity);

  // We only hide the import type field if we have an import type provided.
  if (isset($args[1]) && is_array($args[1]) && isset($args[1]['import_type'])) {
    $import_type = $args[1]['import_type'];

    // Hide the import type field, and set it to the provided import type.
    unset($form['field_import_type']);
    $wrapper->field_import_type->set($import_type->id);

    // Hide the title, we will get it from the import type.
    $form['title']['#access'] = FALSE;
  }
}
