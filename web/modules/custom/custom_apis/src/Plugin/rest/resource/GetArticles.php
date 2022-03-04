<?php

namespace Drupal\custom_apis\Plugin\rest\resource;

use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;

/**
 * Provides a Demo Resource
 *
 * @RestResource(
 *   id = "articles",
 *   label = @Translation("Articles listing"),
 *   uri_paths = {
 *     "canonical" = "/get/articles",
 *     "create" = "add/articles"
 *   }
 * )
 */
class GetArticles extends ResourceBase
{
  /**
   * Responds to entity GET requests.
   * @return \Drupal\rest\ResourceResponse
   */
  public function get()
  {
    try {
      $nids = \Drupal::entityQuery('node')->condition('type', 'article')->execute();
      $nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);
      $response = $this->processNodes($nodes);
    return new ResourceResponse($response);
    } catch (EntityStorageException $e) {
      \Drupal::logger('custom-rest')->error($e->getMessage());
    }
  }
  /**
   * Get articles
   */
  private function processNodes($nodes)
  {
    $output = [];
    foreach ($nodes as $key => $node) {
      $output[$key]['title'] = $node->get('title')->getValue();
      $output[$key]['node_id'] = $node->get('nid')->getValue();
      $output[$key]['description'] = $node->get('body')->getValue();
    }
    return $output;
  }

  /**
   * Post api
   */
 public function post($data){
   try {

     $new_term = Term::create([
       'name' => $data['title'],
       'vid' => $data['type'],
     ]);
     $new_term->save();

     return new ResourceResponse('Term created successfully in '. $data['type']);
   } catch (EntityStorageException $e) {
     \Drupal::logger('custom-rest')->error($e->getMessage());
   }
 }
  /**
   * Patch api
   */
  public function patch($data){
    try {

      $term = Term::load($data['tid']);
      $term->setName($data['name']);
      $term->field_custom_title->setValue($data['sub_title']);

      $term->save();

      return new ResourceResponse('Term updated successfully');
    } catch (EntityStorageException $e) {
      \Drupal::logger('custom-rest')->error($e->getMessage());
    }
  }
}
