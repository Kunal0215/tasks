<?php
namespace Drupal\configmod\Plugin\Block;
/**
 * @file
 *
 */
use \Symfony\Component\HttpFoundation\Response;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Routing;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
/**
 * Provides a 'List config' Block.
 *
 * @Block(
 *   id = "list_config_block",
 *   admin_label = @Translation("List Config block"),
 *   category = @Translation("List Config World"),
 * )
 */
class ListBlock extends BlockBase {

  /**
   *
   */
  public function listshow() {
    // Load configuration saved by config form
    $config = \Drupal::config('configmod.settings');
    // Load image saved in configuration
    $fid = $config->get('Image');
    $image_entity = \Drupal\file\Entity\File::load($fid[0]);
    // Get source url for image to be rendered and passed to twig
    $image_entity_url = $image_entity->url();
    // Returnable array
    $items=array();
    // Fetch other fields of config and store to renderable array
    $items[] = [
      'title' => $config->get('Title'),
      'url' =>   $image_entity_url,
      'desc' => $config->get('Description'),
     ];
    return array(
     'theme' => 'list',
     'items' => $items,
     'title' => 'Configuration',
    );
  }
  /**
   * [Function as defined for block rendering]
   * @return
   */
  public function build() {
    // Get data as a returnable array and render as block
    $data = $this->listshow();
    return  array(
      '#theme' => $data['theme'],
      '#items' => $data['items'],
      '#title' => $data['title'],
      '#cache' => [
        'max-age' => 0,
      ]
    );
  }
}
