<?php

/**
 * @file
 * Provides a fallback class autoloader.
 */

/**
 * Register a fallback class loader.
 */
function excel_import_register_fallback_autoloader() {
  static $first_run = TRUE;
  if (!$first_run) {
    return;
  }
  $first_run = FALSE;

  spl_autoload_register('excel_import_autoload');
}


/**
 * The autoload callback for uninstalling.
 *
 * @param string $class
 *   The class we want to load.
 */
function excel_import_autoload($class) {

  static $lib_dir;
  if (!isset($lib_dir)) {
    // remove includes and the filename from the file path, and add src to it.
    $lib_dir = dirname(dirname(__FILE__)) . '/src/';
  }

  $module = 'excel_import';
  $l = strlen($module);

  if (FALSE !== $nspos = strrpos($class, '\\')) {
    // PSR-0 mode. Omit this if you use only the PHP 5.2 compatibility mode.
    if ("Drupal\\$module\\" === substr($class, 0, $l + 8)) {
      $namespace = substr($class, 0, $nspos);
      // remove Drupal\module_name from the namespace for the path.
      $namespace_path = substr($namespace, $l + 8);
      $classname = substr($class, $nspos + 1);
      $path = $lib_dir . str_replace('\\', '/', $namespace_path) . '/' . str_replace('_', '/', $classname) . '.php';
      if (is_file($path)) {
        include $path;
      }
    }
  }
  else {
    // PHP 5.2 compatibility mode. Omit this if you use only PSR-0.
    if ($module . '_' === substr($class, 0, $l + 1)) {
      $path = $lib_dir . str_replace('_', '/', $class) . '.php';
      if (is_file($path)) {
        include $path;
      }
    }
  }
}
