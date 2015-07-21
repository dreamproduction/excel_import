<?php
/**
 * Created by PhpStorm.
 * User: calinmarian
 * Date: 7/20/15
 * Time: 19:44
 */

namespace Drupal\excel_import\Importer;


class ReplaceExisting extends AbstractImporter {

   protected function loadEntity() {
     $ids = $this->getExistingEntitiesIds();
     if (count($ids)) {
       if (count($ids) != 1) {
         return;
       }
       entity_delete($this->getEntityType(), reset($ids));
     }

     $this->createEntity();
   }
}