<?php

namespace Drupal\demo_search\TypedData;

use Drupal\Core\TypedData\TypedData;
use Drupal\Core\TypedData\ComplexDataInterface;

/**
 * A typed data definition class for describing External documents.
 */
class ExternalDocumentDefinition extends TypedData implements \IteratorAggregate, ComplexDataInterface {

  public function __construct($publicId, $title, $domain) {
    $this->propertyDefinitions['publicId'] = $publicId;
    $this->propertyDefinitions['title'] = $title;
    $this->propertyDefinitions['domain'] = $domain;

    $this->value = $this->propertyDefinitions;
  }

   public function get($property_name) {
     if (isset($this->propertyDefinitions[$property_name])) {
       return $this->propertyDefinitions[$property_name];
     }
     return NULL;
   }

   public function set($property_name, $value, $notify = TRUE) {
     $this->propertyDefinitions[$property_name] = $value;
   }

     public function getProperties($include_computed = FALSE) {
       return $this->propertyDefinitions;
     }

  /**
   * {@inheritdoc}
   */
  public function getIterator() {
    return new \ArrayIterator($this->propertyDefinitions);
  }


  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions() {
    return $this->propertyDefinitions;
  }

  /**
   * {@inheritdoc}
   */
  public function toArray() {
    // @todo Implement this.
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty() {
    return !isset($this->item);
  }

  /**
   * {@inheritdoc}
   */
  public function onChange($name) {
    // Do nothing.  Unlike content entities, Items don't need to be notified of
    // changes.
  }

}
