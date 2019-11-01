<?php
/**
 * Created by PhpStorm.
 * User: calinmarian
 * Date: 7/20/15
 * Time: 19:44
 */

namespace Drupal\excel_import\Importer;


abstract class AbstractImporter {

  /**
   * @var \EntityFieldQuery
   */
  protected $efq;

  /**
   * @var object
   */
  protected $entity;

  /**
   * @var array
   */
  protected $row;

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
   * AbstractImporter constructor.
   * @param $row
   * @param $mapping
   * @param $entity_type
   * @param $bundle
   */
  public function __construct($row, $mapping, $entity_type, $bundle) {
    $this->setRow($row);
    $this->setMapping($mapping);
    $this->setEntityType($entity_type);
    $this->setBundle($bundle);
  }

  abstract protected function loadEntity();

  public function processEntity() {
    if (!$entity = $this->getEntity()) {
      return;
    }
    $wrapper = entity_metadata_wrapper($this->getEntityType(), $entity);
    $updated = FALSE;
    foreach ($this->getMapping() as $mapping) {
      $source_plugin_name = $mapping['source_plugin'];
      $source_plugin = mapping_field_get_plugin('mapping_source', $source_plugin_name);
      $source_data = $mapping['source_data'][$source_plugin_name];

      $value = $source_plugin['class']::getValue($this->getRow(), $source_data);
      $destination_plugin_name = $mapping['destination_plugin'];
      $destination_plugin = mapping_field_get_plugin('mapping_destination', $destination_plugin_name);
      $destination_plugin_instance = new $destination_plugin['class']($this->getEntityType(), $this->getBundle());
      $destination_data = isset($mapping['destination_data'][$destination_plugin_name]) ? $mapping['destination_data'][$destination_plugin_name] : [];
      try {
        $previous_value = $destination_plugin_instance->getValue($wrapper, $destination_data);
      } catch (\EntityMetadataWrapperException $e) {
        drupal_set_message(t('Can\'t read the previous value for destination: !data', ['!data' => var_export($destination_data, TRUE)]));
      }

      if ($value != $previous_value) {
        try {
          $destination_plugin_instance->setValue($wrapper, $value, $destination_data);
          $updated = TRUE;
        } catch (\EntityMetadataWrapperException $e) {
          drupal_set_message(t('Wrong value passed: !value for destination: !data', ['!value' => var_export($value, TRUE), '!data' => var_export($destination_data, TRUE)]), 'warning');
        }
      }
    }

    if ($updated) {
      try {
        $wrapper->save();
      } catch (\Exception $e) {
        drupal_set_message(t('Can\'t save entity with values: !values. Original mesage: !message.', ['!values' => var_export($wrapper->value(), TRUE), '!message' => $e->getMessage()]), 'warning');
      }
    }
  }

  protected function getExistingEntitiesIds() {
    $this->setEfq(new \EntityFieldQuery());

    $this->getEfq()
      ->entityCondition('entity_type', $this->getEntityType())
      ->entityCondition('bundle', $this->getBundle());

    if (!$this->addConditions()) {
      return;
    }

    $result = $this->getEfq()->execute();
    if (isset($result[$this->getEntityType()])) {
      return  array_keys($result[$this->getEntityType()]);
    }
  }

  protected function addConditions() {
    $condition_added = FALSE;
    foreach ($this->getMapping() as $mapping) {
      $destination_plugin_name = $mapping['destination_plugin'];
      $destination_plugin = mapping_field_get_plugin('mapping_destination', $destination_plugin_name);
      $destination_plugin_instance = new $destination_plugin['class']($this->getEntityType(), $this->getBundle());
      $destination_data = isset($mapping['destination_data'][$destination_plugin_name]) ? $mapping['destination_data'][$destination_plugin_name] : [];

      if ($destination_plugin_instance->isIdField($destination_data)) {
        $source_plugin_name = $mapping['source_plugin'];
        $source_plugin = mapping_field_get_plugin('mapping_source', $source_plugin_name);
        $source_data = $mapping['source_data'][$source_plugin_name];
        $value =  $source_plugin['class']::getValue($this->getRow(), $source_data);
        $destination_plugin_instance->addCondition($this->getEfq(), $destination_data, $value);
        $condition_added = TRUE;
      }
    }
    return $condition_added;
  }

  protected function createEntity() {
    $bundle_key = $this->getBundleKey();
    $properties = [];
    if ($bundle_key) {
      $properties[$bundle_key] = $this->getBundle();
    }
    $entity = entity_create($this->getEntityType(), $properties);
    $this->setEntity($entity);
  }

  protected function getBundleKey() {
    $entity_info = entity_get_info($this->getEntityType());
    return isset($entity_info['entity keys']['bundle']) ? $entity_info['entity keys']['bundle'] : NULL;
  }

  /**
   * @return mixed
   */
  protected function getRow() {
    return $this->row;
  }

  /**
   * @param mixed $row
   */
  protected function setRow($row) {
    $this->row = $row;
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
  protected function getEntity() {
    if (!isset($this->entity)) {
      $this->loadEntity();
    }
    return $this->entity;
  }

  /**
   * @param mixed $entity
   */
  protected function setEntity($entity) {
    $this->entity = $entity;
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

}
