<?php

namespace Drupal\demo_search\Plugin\search_api\datasource;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\PluginFormInterface;
use Drupal\Core\TypedData\ComplexDataInterface;
use Drupal\search_api\Datasource\DatasourcePluginBase;
use Drupal\search_api\Plugin\PluginFormTrait;
use Drupal\demo_search\ExternalDocumentFactory;
use Drupal\demo_search\ExternalFieldManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Represents a datasource which exposes external External Documents.
 *
 * @SearchApiDatasource(
 *   id = "external_document",
 *   label = @Translation("External Document"),
 *   description = @Translation("Exposes external External Documents as a datasource."),
 * )
 */
class ExternalDocument extends DatasourcePluginBase implements PluginFormInterface {

  use PluginFormTrait;

  /**
   * The External document factory.
   *
   * @var \Drupal\demo_search\ExternalDocumentFactoryInterface
   */
  protected $externalDocumentFactory;

  /**
   * The External field manager.
   *
   * @var \Drupal\demo_search\ExternalFieldManagerInterface
   */
  protected $externalFieldManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    /** @var static $datasource */
    $datasource = parent::create($container, $configuration, $plugin_id, $plugin_definition);

    $datasource->setExternalDocumentFactory($container->get('external_document.factory'));
    $datasource->setExternalFieldManager($container->get('external_field.manager'));

    return $datasource;
  }

  /**
   * Sets the External document factory.
   *
   * @param \Drupal\demo_search\ExternalDocumentFactoryInterface $factory
   *   The new entity field manager.
   *
   * @return $this
   */
  public function setExternalDocumentFactory(ExternalDocumentFactory $factory) {
    $this->externalDocumentFactory = $factory;
    return $this;
  }

  /**
   * Returns the External document factory.
   *
   * @return \Drupal\demo_search\ExternalDocumentFactory
   *   The External document factory.
   */
  public function getExternalDocumentFactory() {
    return $this->externalDocumentFactory ?: \Drupal::getContainer()->get('external_document.factory');
  }

  /**
   * Sets the External field manager.
   *
   * @param \Drupal\search_api_external_datasource\ExternalFieldManager $external_field_manager
   *   The new entity field manager.
   *
   * @return $this
   */
  public function setExternalFieldManager(ExternalFieldManager $external_field_manager) {
    $this->externalFieldManager = $external_field_manager;
    return $this;
  }

  /**
   * Returns the External field manager.
   *
   * @return \Drupal\search_api_external_datasource\ExternalFieldManager
   *   The External field manager.
   */
  public function getExternalFieldManager() {
    return $this->externalFieldManager ?: \Drupal::getContainer()->get('external_field.manager');
  }

  /**
   * {@inheritdoc}
   */
  public function getItemId(ComplexDataInterface $item) {
    // @todo Implement this.
  }

  /**
   * {@inheritdoc}
   */
  public function getPropertyDefinitions() {
    // @todo Handle IndexInterface::getServerInstance() returning NULL.
    $fields = $this->getExternalFieldManager()->getFieldDefinitions();
    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function loadMultiple(array $ids) {
    $items = \Drupal::service('demo_search.indexer')->getItems($ids);
    $documents = [];
    foreach ($items as $id => $item) {
      $documents[$id] = $this->externalDocumentFactory->create($item);
    }
    return $documents;
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    return [];
  }

}
