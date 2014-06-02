<?php

/**
 * @file
 * Contains \Drupal\entity_embed\Plugin\EntityEmbedDisplay\EntityEmbedDefaultDisplay.
 */

namespace Drupal\entity_embed\Plugin\EntityEmbedDisplay;

use Drupal\entity_embed\EntityEmbedDisplayBase;

/**
 * Default embed display, which renders the entity using entity_view().
 *
 * @EntityEmbedDisplay(
 *   id = "default",
 *   label = @Translation("Default")
 * )
 */
class EntityEmbedDefaultDisplay extends EntityEmbedDisplayBase {

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return array(
      'view_mode' => 'embed',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, array &$form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    $form['view_mode'] = array(
      '#type' => 'select',
      '#title' => t('View mode'),
      '#options' => \Drupal::entityManager()->getDisplayModeOptions('view_mode', $this->entity->getEntityTypeId()),
      '#default_value' => $this->getConfigurationValue('view_mode'),
      '#required' => TRUE,
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    // Clone the entity since we're going to set some additional properties we
    // don't want kept around afterwards.
    $entity = clone $this->entity;
    $entity->entity_embed_context = $this->getContext();

    // Build the rendered entity.
    $build = entity_view($this->entity, $this->getConfigurationValue('view_mode'), $this->getContextValue('langcode'));

    // Hide entity links by default.
    if (isset($build['links'])) {
      $build['links']['#access'] = FALSE;
    }

    return $build;
  }
}
