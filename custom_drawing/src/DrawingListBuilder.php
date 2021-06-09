<?php

namespace Drupal\custom_drawing;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Link;

/**
 * Defines a class to build a listing of Drawing entities.
 *
 * @ingroup custom_drawing
 */
class DrawingListBuilder extends EntityListBuilder
{

  /**
   * {@inheritdoc}
   */
    public function buildHeader()
    {
        $header['id'] = $this->t('Drawing ID');
        $header['game'] = $this->t('Game');
        return $header;
    }

    /**
     * {@inheritdoc}
     */
    public function buildRow(EntityInterface $entity)
    {
        /* @var \Drupal\custom_drawing\Entity\Drawing $entity */
        $row['id'] = $entity->id();
        $row['game'] = ($entity->game->entity) ? $entity->game->entity->label() : '';
        return $row;
    }
}
