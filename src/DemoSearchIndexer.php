<?php

namespace Drupal\demo_search;

use GuzzleHttp\ClientInterface;
use Drupal\search_api\Entity\Index;

/**
 * Class DemoSearchIndexer.
 */
class DemoSearchIndexer {

  /**
   * GuzzleHttp\ClientInterface definition.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;
  /**
   * Constructs a new DemoSearchIndexer object.
   */
  public function __construct(ClientInterface $http_client) {
    $this->httpClient = $http_client;
  }

  public function index() {
    $url = 'https://doc.eccti.or.cr/document/domain/Oferta%20Tecnologica%20Publica/visible';
    $response = $this->httpClient->request('GET', $url);
    $data = json_decode($response->getBody()->getContents());
    $datasource_id = 'external_document';
    $item_ids = [];
    foreach ($data as $element) {
      $item = [
        'publicId' => $element->publicId,
        'title' => $element->title,
        'domain' => $element->domain,
      ];
      $item_ids[] = $element->publicId;
    }

    $index = Index::load('external_docs');
    $index->trackItemsInserted($datasource_id, $item_ids);
  }

  public function getItems($item_ids) {
    $url = 'https://doc.eccti.or.cr/document/domain/Oferta%20Tecnologica%20Publica/visible';
    $response = $this->httpClient->request('GET', $url);
    $data = json_decode($response->getBody()->getContents());

    $items = [];

    foreach ($item_ids as $id) {
      foreach ($data as $item) {
        if ($item->publicId === $id) {
          $items[$id] = $item;
        }
      }
    }

    return $items;
  }

}
