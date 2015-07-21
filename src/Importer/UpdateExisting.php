<?php
/**
 * Created by PhpStorm.
 * User: calinmarian
 * Date: 7/20/15
 * Time: 19:42
 */

namespace Drupal\excel_import\Importer;


class UpdateExisting extends AbstractImporter {

  protected function loadEntity() {
    $ids = $this->getExistingEntitiesIds();
    if (count($ids)) {
      if (count($ids) != 1) {
        return;
      }
      $entity = entity_load_single($this->getEntityType(), reset($ids));
      $this->setEntity($entity);
    }
    else {
      $this->createEntity();
    }
  }
}