<?php

namespace Drupal\demo_search;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Cache\UseCacheBackendTrait;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\search_api\Entity\Server;
use Drupal\demo_search\TypedData\ExternalFieldDefinition;

/**
 * Manages the discovery of External fields.
 */
class ExternalFieldManager {

  use UseCacheBackendTrait;
  use StringTranslationTrait;

  /**
   * Static cache of field definitions per External server.
   *
   * @var array
   */
  protected $fieldDefinitions;

  /**
   * Constructs a new SorFieldManager.
   *
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   The cache backend.
   */
  public function __construct(CacheBackendInterface $cache_backend) {
    $this->cacheBackend = $cache_backend;
  }

  /**
   * {@inheritdoc}
   */
  public function getFieldDefinitions() {
    if (!isset($this->fieldDefinitions)) {
      // Not prepared, try to load from cache.
      $cid = 'external_field_definitions';
      if ($cache = $this->cacheGet($cid)) {
        $field_definitions = $cache->data;
      }
      elseif ($field_definitions = $this->buildFieldDefinitions()) {
        // Only cache the field definitions if they aren't empty.
        $this->cacheSet($cid, $field_definitions, Cache::PERMANENT, ['search_api_server' => $server_id]);
      }
      $this->fieldDefinitions = $field_definitions;
    }
    return $this->fieldDefinitions;
  }

  /**
   * Builds the field definitions for a External server from its Luke handler.
   *
   * @return \Drupal\demo_search\TypedData\ExternalFieldDefinitionInterface[]
   *   The array of field definitions for the server, keyed by field name.
   *
   * @throws \InvalidArgumentException
   */
  protected function buildFieldDefinitions() {
    $fields = [
      'publicId' => [
        'type' => 'string',
        'name' => 'publicId',
      ],
      'title' => [
        'type' => 'string',
        'name' => 'title',
      ],
      'domain' => [
        'type' => 'string',
        'name' => 'domain',
      ],
      'tags' => [
        'type' => 'array',
        'name' => 'tags',
      ],
    ];

    $definitions = [];
    foreach ($fields as $name => $field) {
      $definition = new Any($field);
      $definition->setLabel($name);
      $definitions[$name] = $definition;
    }
    return $definitions;
  }

}
