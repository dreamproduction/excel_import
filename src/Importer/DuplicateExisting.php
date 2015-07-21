<?php
/**
 * Created by PhpStorm.
 * User: calinmarian
 * Date: 7/20/15
 * Time: 20:21
 */

namespace Drupal\excel_import\Importer;


class DuplicateExisting extends AbstractImporter {

  protected function loadEntity() {
    $this->createEntity();
  }
}