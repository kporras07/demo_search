<?php

namespace Drupal\demo_search;

use Drupal\Core\TypedData\TypedDataManagerInterface;
use Drupal\demo_search\TypedData\ExternalFieldDefinition;
use Drupal\demo_search\TypedData\ExternalDocumentDefinition;
use Drupal\Core\TypedData\Plugin\DataType\Any;

/**
 * Defines a class for a External Document factory.
 */
class ExternalDocumentFactory {

  /**
   * A typed data manager.
   *
   * @var \Drupal\Core\TypedData\TypedDataManagerInterface
   */
  protected $typedDataManager;

  /**
   * Constructs a ExternalDocumentFactory object.
   *
   * @param \Drupal\Core\TypedData\TypedDataManagerInterface $typedDataManager
   *   A typed data manager.
   */
  public function __construct(TypedDataManagerInterface $typedDataManager) {
    $this->typedDataManager = $typedDataManager;
  }

  /**
   * {@inheritdoc}
   */
  public function create($item) {
    $public_id = new Any([$item->publicId]);
    $title = new Any([$item->title]);
    $domain = new Any([$item->domain]);
    $document = new ExternalDocumentDefinition($public_id, $title, $domain);
    return $document;
  }

}
