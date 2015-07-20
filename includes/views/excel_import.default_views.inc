<?php

/**
 * @file
 * Provides default views implemented by the excel_import module.
 */

/**
 * Implements hook_views_default_views().
 */
function excel_import_views_default_views() {
  $files = file_scan_directory(
    drupal_get_path('module', ' excel_import') . '/includes/views/default',
    '/.+\.inc$/',
    ['recurse' => FALSE]
  );

  $return = [];
  foreach ($files as $file) {
    $view = NULL;
    include $file->uri;
    if ($view) {
      $return[$view->name] = $view;
    }
  }

  return $return;
}