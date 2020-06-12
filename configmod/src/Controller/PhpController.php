<?php
namespace Drupal\configmod\Controller;
/**
 * @file
 * This file is used as a practice to get the block rendered via code
 */
use Drupal\paragraphs\Entity\Paragraph;
use Drupal\node\Entity\Node;
use Drupal\node\NodeInterface;
use Drupal\Core\Controller\ControllerBase;
use \Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use \Drupal\block\Entity\Block;
/**
  * This class contains functions for rendering block via code
*/
class PhpController extends ControllerBase {
  /**
   * Function for rendering block via custom code
   * @return [mixed]
   */
  public function hello() {
    // Load Plugin manager for blocks
    $block_manager = \Drupal::service('plugin.manager.block');
    $config = [];
    // Get configuration for list block
    $plugin_block = $block_manager->createInstance('list_config_block', $config);
    // Call out the build function of block to render the form
    $render = $plugin_block->build();
    // Return renderable data of block
    return $render;
  }
}
