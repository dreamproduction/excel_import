<?php
/**
 * @file
 * Field handler to present a link to import the file content.
 */

// @codingStandardsIgnoreStart
class excel_import_views_handler_field_link_import extends eck_views_handler_field_link {

  function render_link($entity, $values) {
    if (user_access('import excel files')) {

      $this->options['alter']['make_link'] = TRUE;
      $this->options['alter']['path'] = 'admin/content/import/' . $entity->id . '/import';
      $this->options['alter']['query'] = drupal_get_destination();

      $text = !empty($this->options['text']) ? $this->options['text'] : t('import');

      return $text;
    }
  }
}
// @codingStandardsIgnoreEnd