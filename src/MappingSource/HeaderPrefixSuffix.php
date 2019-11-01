<?php
/**
 * Contains Drupal\excel_import\MappingSource\HeaderPrefixSuffix.
 */

namespace Drupal\excel_import\MappingSource;


class HeaderPrefixSuffix extends Header {

  function getForm($default_value = '_none', $states) {
    return [
      'prefix' => [
        '#type' => 'textfield',
        '#title' => t('Prefix'),
        '#default_value' => $default_value['prefix'],
        '#states' => $states,
      ],
      'column' => parent::getForm($default_value['column'], $states),
      'suffix' => [
        '#type' => 'textfield',
        '#title' => t('Suffix'),
        '#default_value' => $default_value['suffix'],
        '#states' => $states,
      ]
    ];
  }

  public static function getValue($row, $data, $import_file) {
    return $data['prefix'] . parent::getValue($row, $data['column'], $import_file) . $data['suffix'];
  }
  
}
