<?php

namespace Drupal\excel_import\Entity;
use EntityType;
use Bundle;

abstract class BaseEntity {
  /**
   * Returns an araay defining the entity_type.
   */
  abstract protected function getEntityTypeDefinition();

  /**
   * Returns an array defining the bundle.
   */
  abstract protected function getBundleDefinition();

  /**
   * Return an array defining the fields.
   */
  abstract protected function getFieldsDefinition();

  /**
   * Create the defined entity_type.
   */
  protected function createEntityType() {
    // Create the entity type if it doesn't exist.
    $entity_type = EntityType::loadByName(static::ENTITY_TYPE);
    if (!$entity_type) {
      // Load the entity type definition.
      $definition = $this->getEntityTypeDefinition();

      $entity_type = new EntityType();
      $entity_type->name = static::ENTITY_TYPE;
      $entity_type->label = $definition['label'];
      foreach ($definition['properties'] as $name => $property) {
        $entity_type->addProperty($name, $property['label'], $property['type'], $property['behavior']);
      }
      $entity_type->save();
    }
  }

  /**
   * Delete the entity type.
   */
  protected function deleteEntityType() {
    $entity_type = EntityType::loadByName(static::ENTITY_TYPE);

    if ($entity_type) {
      $entity_type->delete();
    }
  }

  /**
   * Create the defined bundle.
   */
  protected function createBundle() {
    // Create the bundle if it doesn't exist.
    $bundle = Bundle::loadByMachineName(static::ENTITY_TYPE . '_' . static::BUNDLE);
    if (!$bundle) {
      // Load the entity type definition.
      $definition = $this->getBundleDefinition();

      $bundle = new Bundle();
      $bundle->name = static::BUNDLE;
      $bundle->label = $definition['label'];
      $bundle->entity_type = static::ENTITY_TYPE;
      $bundle->save();
    }
  }

  /**
   * Create the defined fields, if they don't exist yet.
   */
  protected function createFields() {
    $definition = $this->getFieldsDefinition();
    $bundle = Bundle::loadByMachineName(static::ENTITY_TYPE . '_' . static::BUNDLE);

    foreach ($definition as $field_name => $field_definition) {
      $instance = field_info_instance(static::ENTITY_TYPE, $field_name, static::BUNDLE);

      if (!$instance) {
        $bundle->addField($field_definition['type'], [
          'field' => $field_definition['field'],
          'instance' => $field_definition['instance'],
        ]);
      }
    }
  }

  /**
   * Delete the defined fields.
   */
  protected function deleteFields() {
    $definition = $this->getFieldsDefinition();

    foreach ($definition as $field_name => $field_definition) {
      $instance = field_info_instance(static::ENTITY_TYPE, $field_name, static::BUNDLE);

      if ($instance) {
        field_delete_instance($instance);
      }
    }
  }

  /**
   * Ensure the entity_type, bundle and fields are created.
   */
  public function ensureEntityTypeCreated() {
    $this->createEntityType();
    $this->createBundle();
    $this->createFields();
  }

  /**
   * Ensure the entity_type, bundle and fields are deleted.
   */
  public function ensureEntityTypeDeleted() {
    $this->deleteFields();
    $this->deleteEntityType();
  }

  static public function getAllEntities() {
    return entity_load(static::ENTITY_TYPE);
  }
}
