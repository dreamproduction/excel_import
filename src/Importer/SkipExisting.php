<?php
/**
 * Created by PhpStorm.
 * User: calinmarian
 * Date: 7/20/15
 * Time: 19:43
 */

namespace Drupal\excel_import\Importer;


class SkipExisting extends AbstractImporter {

  protected function loadEntity() {
    $ids = $this->getExistingEntitiesIds();
    if (count($ids)) {
      return;
    }

    $this->createEntity();
  }

}