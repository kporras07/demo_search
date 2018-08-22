<?php

namespace Drupal\demo_search\Plugin\DataType;

use Drupal\Core\TypedData\ComplexDataInterface;
use Drupal\Core\TypedData\TypedData;
use Drupal\Core\TypedData\TypedDataInterface;
use Drupal\search_api\Item\ItemInterface;
use Drupal\Core\TypedData\Plugin\DataType\Any;
use Drupal\demo_search\TypedData\ExternalDocumentDefinition;
use Drupal\demo_search\TypedData\ExternalFieldDefinition;

/**
 * Defines the "External document" data type.
 *
 * Instances of this class wrap Search API Item objects and allow to deal with
 * items based upon the Typed Data API.
 *
 * @DataType(
 *   id = "external_document",
 *   label = @Translation("External document"),
 *   description = @Translation("Records from a External source"),
 *   definition_class = "\Drupal\demo_search\TypedData\ExternalDocumentDefinition"
 * )
 */
class ExternalField extends Any implements \IteratorAggregate {
  public function isList() {
    return FALSE;
  }

  public function getValue() {
    return $this->definition[0];
  }
  /**
   * {@inheritdoc}
   */
  public function getIterator() {
    return new \ArrayIterator([]);
  }

  /**
   * {@inheritdoc}
   */
  public function getDataDefinition() {
    return new ExternalFieldDefinition();
  }
}
