<?php

/**
 * @file
 * Contains code for configuration saving .
 */

namespace Drupal\configmod\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;
/**
 * Class ConfigurationForm.
 *
 * @package Drupal\configmod\Form
 */
class ConfigurationForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'configmod.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Set configuration and add fields
    $config = $this->config('configmod.settings');
    $form['Title'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Title'),
      '#default_value' => $config->get('Title'),
    );
    $form['Description'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Description'),
      '#default_value' => $config->get('Description'),
    );
    $form['Image'] = array(
      '#type' => 'managed_file',
      '#title' => $this->t('Image'),
      '#upload_location' => 'public://profile-pictures',
      '#upload_validators' => array(
       'file_validate_extensions' => array('gif png jpg jpeg'),
        ),
       '#default_value' => $config->get('Image', 0),
    );
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Get file location of image to be stored as path to image
    $form_file = $form_state->getValue('Image', 0);
    $image_entity = \Drupal\file\Entity\File::load($form_file[0]);
    $image_entity_url = $image_entity->url();
    // Send data to custom table for config
    db_insert('custom_config')->fields(
      array(
        'title' => $form_state->getValue('Title'),
        'description' => $form_state->getValue('Description'),
        'image' => $image_entity_url,
      ))
    ->execute();
    // Move image to folder
    $file = File::load($form_file[0]);
    $file->setPermanent();
    $file->save();
    // Load back to fields on config page
    $this->config('configmod.settings')
      ->set('Title', $form_state->getValue('Title'))
      ->set('Description', $form_state->getValue('Description'))
      ->set('Image', $form_state->getValue('Image', 0))
      ->save();
    parent::submitForm($form, $form_state);
  }
}
