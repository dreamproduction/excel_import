<?php

/**
 * @file
 * Contains PHPExcelParser.
 */
namespace Drupal\excel_import\Parser;

class PHPExcelParser implements ParserInterface {

  protected $uri;
  protected $header;
  protected $rows;
  protected $header_keys;
  protected $index;
  protected $count;

  function __construct($uri, $header_keys = TRUE) {
    if (!class_exists('PHPExcel_IOFactory')) {
      throw new \Exception(t("PHPExcel library not found."));
    }
    $this->setUri($uri);
    $this->rewind();
  }

  protected function loadFile() {
    try {
      $objPHPExcel = \PHPExcel_IOFactory::load($this->getUri());
    }
    catch (Exception $e) {
      drupal_set_message(t('Error loading file %filename: @error_message', [
        '%filename' => pathinfo($uri, PATHINFO_BASENAME),
        '@error_message' => $e->getMessage(),
      ]), 'error');

      return;
    }

    // Set the active sheet
    $objPHPExcel->setActiveSheetIndex(0);
    // Get worksheet dimensions
    $highest_row = $objPHPExcel->getActiveSheet()->getHighestRow();
    $highest_column = $objPHPExcel->getActiveSheet()->getHighestColumn();

    // Get the header
    $row_data = $objPHPExcel->getActiveSheet()->rangeToArray('A1:' . $highest_column . '1', NULL, TRUE, FALSE);
    $header = array_filter(reset($row_data));
    $this->setHeader($header);

    // Loop through each row of the worksheet in turn
    $rows = [];
    for ($row_id = 2; $row_id <= $highest_row; $row_id++){
      // Read a row of data into an array
      $row_data = $objPHPExcel->getActiveSheet()->rangeToArray('A' . $row_id . ':' . $highest_column . $row_id, NULL, TRUE, FALSE);
      $row = reset($row_data);
      if (array_filter($row_data)) {
        $rows[] = array_combine($header, $row);
      }
    }

    $this->setRows($rows);
    $this->setCount(count($rows));
  }

  /**
   * Return the element identified by the provided index.
   * @param $index
   * @return mixed
   */
  protected function getRow($index) {
    $rows = $this->getRows();
    return isset($rows[$index]) ? $rows[$index] : NULL;
  }

  /**
   * Return the current element
   * @link http://php.net/manual/en/iterator.current.php
   * @return mixed Can return any type.
   */
  public function current() {
    return $this->getRow($this->getIndex());
  }
  /**
   * Move forward to next element
   * @link http://php.net/manual/en/iterator.next.php
   * @return void Any returned value is ignored.
   */
  public function next() {
    $this->setIndex($this->getIndex() + 1);
  }

  /**
   * Return the key of the current element
   * @link http://php.net/manual/en/iterator.key.php
   * @return mixed scalar on success, or null on failure.
   */
  public function key() {
    return $this->getIndex();
  }

  /**
   * Checks if current position is valid
   * @link http://php.net/manual/en/iterator.valid.php
   * @return boolean The return value will be casted to boolean and then evaluated.
   * Returns true on success or false on failure.
   */
  public function valid() {
    return $this->getIndex() < $this->getCount();
  }

  /**
   * Rewind the Iterator to the first element
   * @link http://php.net/manual/en/iterator.rewind.php
   * @return void Any returned value is ignored.
   */
  public function rewind() {
    $this->setIndex(0);
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
   * Get the header of the excel file.
   * @return mixed
   */
  public function getHeader() {
    if (!isset($this->header)) {
      $this->loadFile();
    }
    return $this->header;
  }

  /**
   * @param mixed $header
   */
  protected function setHeader($header) {
    $this->header = $header;
  }

  /**
   * Get the rows of the excel file.
   * @return mixed
   */
  public function getRows($offest = 0, $length = NULL) {
    if (!isset($this->rows)) {
      $this->loadFile();
    }
    return array_slice($this->rows, $offest, $length);
  }

  /**
   * @param mixed $rows
   */
  protected function setRows($rows) {
    $this->rows = $rows;
  }

  /**
   * @return mixed
   */
  protected function getHeaderKeys() {
    return $this->header_keys;
  }

  /**
   * @param mixed $header_keys
   */
  protected function setHeaderKeys($header_keys) {
    $this->header_keys = $header_keys;
  }

  /**
   * @return mixed
   */
  protected function getIndex() {
    return $this->index;
  }

  /**
   * @param mixed $index
   */
  protected function setIndex($index) {
    $this->index = $index;
  }

  /**
   * @return mixed
   */
  public function getCount() {
    if (!isset($this->count)) {
      $this->loadFile();
    }
    return $this->count;
  }

  /**
   * @param mixed $count
   */
  protected function setCount($count) {
    $this->count = $count;
  }

}