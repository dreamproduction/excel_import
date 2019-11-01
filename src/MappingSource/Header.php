<?php
/**
 * Contains Drupal\excel_import\MappingSource\Header.
 */
namespace Drupal\excel_import\MappingSource;

use Drupal\mapping_field\MappingSource\BaseSource;
use Drupal\excel_import\Parser\PHPExcelParser;

class Header extends BaseSource {

  protected static $cache;

  protected $uri;

  protected $header;

  /**
   * Header constructor.
   * @param $uri
   */
  public function __construct($uri = NULL) {
    $this->setUri($uri);
  }

  function getForm($default_value = '_none', $states) {
    return[
      '#type' => 'select',
      '#title' => t('Source'),
      '#options' => ['_none' => t('Select a column')] + array_combine($this->getHeader(), $this->getHeader()),
      '#default_value' => $default_value,
      '#states' => $states,
    ];
  }

  protected function loadHeader() {
    if (!isset(static::$cache[$this->getUri()])) {
      $header_keys = TRUE;
      $parser = new PHPExcelParser($this->getUri(), $header_keys);
      static::$cache[$this->getUri()] = $parser->getHeader();
    }

    $this->setHeader(static::$cache[$this->getUri()]);
  }

  /**
   * @return mixed
   */
  protected function getUri() {
    return $this->uri;
  }

  /**
   * @param mixed $uri
   */
  protected function setUri($uri) {
    $this->uri = $uri;
  }

  /**
   * @return mixed
   */
  protected function getHeader() {
    if (!isset($this->header)) {
      $this->loadHeader();
    }
    return $this->header;
  }

  /**
   * @param mixed $header
   */
  protected function setHeader($header) {
    $this->header = $header;
  }

  public static function getValue($row, $key, $import_file) {
    return $row[$key];
  }
}
