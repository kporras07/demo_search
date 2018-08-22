<?php

namespace Drupal\demo_search\Plugin\DataType;

use Drupal\Core\TypedData\ComplexDataInterface;
use Drupal\Core\TypedData\TypedData;
use Drupal\search_api\Item\ItemInterface;
use Drupal\demo_search\TypedData\ExternalDocumentDefinition;

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
class ExternalDocument extends TypedData implements \IteratorAggregate, ComplexDataInterface {

  /**
   * The wrapped Search API Item.
   *
   * @var \Drupal\search_api\Item\ItemInterface|null
   */
  protected $item;

  /**
   * Creates an instance wrapping the given Item.
   *
   * @param \Drupal\search_api\Item\ItemInterface|null $item
   *   The Item object to wrap.
   *
   * @return static
   */
  public static function createFromItem(ItemInterface $item) {
    $server_id = $item->getIndex()->getServerInstance()->id();
    $definition = ExternalDocumentDefinition::create($server_id);
    $instance = new static($definition);
    $instance->setValue($item);
    return $instance;
  }

  /**
   * {@inheritdoc}
   */
  public function getValue() {
    return $this->item;
  }

  /**
   * {@inheritdoc}
   */
  public function setValue($item, $notify = TRUE) {
    $this->item = $item;
  }

  /**
   * {@inheritdoc}
   */
  public function get($property_name) {
    if (!isset($this->item)) {
      throw new MissingDataException("Unable to get External field $property_name as no item has been provided.");
    }
    $field = $this->item->getField($property_name);
    if ($field === NULL) {
      throw new \InvalidArgumentException("The External field $property_name has not been configured in the index.");
    }
    // Create a new typed data object from the item's field data.
    /** @var \Drupal\search_api_external_datasource\Plugin\DataType\ExternalField $plugin */
    $plugin = \Drupal::typedDataManager()->getDefinition('external_field')['class'];
    return $plugin::createFromField($field, $property_name, $this);
  }

  /**
   * {@inheritdoc}
   */
  public function set($property_name, $value, $notify = TRUE) {
    // Do nothing because we treat External documents as read-only.
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getProperties($include_computed = FALSE) {
    // @todo Implement this.
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

  /**
   * {@inheritdoc}
   */
  public function getIterator() {
    return isset($this->item) ? $this->item->getIterator() : new \ArrayIterator([]);
  }

}
