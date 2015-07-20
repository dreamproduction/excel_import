<?php

/**
 * @file
 * Contains ParserInterface.
 */
namespace Drupal\excel_import\Parser;

interface ParserInterface extends \Iterator{
  /**
   * Get the header of the excel file.
   * @return mixed
   */
  public function getHeader();

  /**
   * Get the rows of the excel file.
   * @return mixed
   */
  public function getRows();
}