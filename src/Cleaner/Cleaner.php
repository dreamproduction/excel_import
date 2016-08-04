<?php

namespace Drupal\excel_import\Cleaner;


class Cleaner {

  /**
   * @var \EntityFieldQuery
   */
  protected $efq;

  /**
   * @var object
   */
  protected $import_file;


  /**
   * @var array
   */
  protected $rows;

  /**
   * @var array
   */
  protected $mapping;

  /**
   * @var string
   */
  protected $entity_type;

  /**
   * @var string
   */
  protected $bundle;

  /**
   * Cleaner constructor.
   * @param $row
   * @param $mapping
   * @param $entity_type
   * @param $bundle
   */
  public function __construct($rows, $mapping, $entity_type, $bundle, $import_file) {
    $this->setRows($rows);
    $this->setMapping($mapping);
    $this->setEntityType($entity_type);
    $this->setBundle($bundle);
    $this->setImportFile($import_file);
  }

  /**
   * Removes all entities that can't be found in a specific import file.
   */
  public function processEntities() {
    if (!$entities_ids = $this->getNotExistingEntities()) {
      return;
    }
    entity_delete_multiple($this->getEntityType(), $entities_ids);
  }

  protected function getNotExistingEntities() {
    $this->setEfq(new \EntityFieldQuery());

    $this->getEfq()
      ->entityCondition('entity_type', $this->getEntityType())
      ->entityCondition('bundle', $this->getBundle());

    if (!$this->addConditions()) {
      return;
    }

    $result = $this->getEfq()->execute();
    if (isset($result[$this->getEntityType()])) {
      return array_keys($result[$this->getEntityType()]);
    }
  }

  protected function addConditions() {
    $condition_added = FALSE;
    foreach ($this->getMapping() as $mapping) {
      $destination_plugin_name = $mapping['destination_plugin'];
      $destination_plugin = mapping_field_get_plugin('mapping_destination', $destination_plugin_name);
      $destination_plugin_instance = new $destination_plugin['class']($this->getEntityType(), $this->getBundle());
      $destination_data = $mapping['destination_data'][$destination_plugin_name];

      if ($destination_plugin_instance->isIdField($destination_data)) {
        $source_plugin_name = $mapping['source_plugin'];
        $source_plugin = mapping_field_get_plugin('mapping_source', $source_plugin_name);
        $source_plugin_instance = new $source_plugin['class']();
        $source_data = $mapping['source_data'][$source_plugin_name];
        if (isset($source_plugin['static']) && $source_plugin['static']) {
          $value = $source_plugin_instance->getValue($this->getRows(), $source_data, $this->getImportFile());
          $destination_plugin_instance->addCondition($this->getEfq(), $destination_data, $value);
          $condition_added = TRUE;
        }
        else {
          $values = [];
          foreach ($this->getRows() AS $row) {
            $values[] = $source_plugin_instance->getValue($row, $source_data, $this->getImportFile());
          }
          $destination_plugin_instance->addCondition($this->getEfq(), $destination_data, $values, 'NOT IN');
        }
      }
    }

    return $condition_added;
  }

  /**
   * @return mixed
   */
  protected function getRows() {
    return $this->rows;
  }

  /**
   * @param mixed $row
   */
  protected function setRows($rows) {
    $this->rows = $rows;
  }
  /**
   * @return mixed
   */
  protected function getMapping() {
    return $this->mapping;
  }

  /**
   * @param mixed $mapping
   */
  protected function setMapping($mapping) {
    $this->mapping = $mapping;
  }

  /**
   * @return mixed
   */
  protected function getEntityType() {
    return $this->entity_type;
  }

  /**
   * @param mixed $entity_type
   */
  protected function setEntityType($entity_type) {
    $this->entity_type = $entity_type;
  }

  /**
   * @return mixed
   */
  protected function getBundle() {
    return $this->bundle;
  }

  /**
   * @param mixed $bundle
   */
  protected function setBundle($bundle) {
    $this->bundle = $bundle;
  }

  /**
   * @return \EntityFieldQuery
   */
  protected function getEfq() {
    return $this->efq;
  }

  /**
   * @param \EntityFieldQuery $efq
   */
  protected function setEfq(\EntityFieldQuery $efq) {
    $this->efq = $efq;
  }

  /**
   * @return mixed
   */
  protected function getImportFile() {
    return $this->import_file;
  }

  /**
   * @param mixed $import_file
   */
  protected function setImportFile($import_file) {
    $this->import_file = $import_file;
  }
}