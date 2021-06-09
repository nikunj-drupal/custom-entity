<?php

namespace Drupal\custom_drawing;

use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
use Drupal\Core\Entity\EntityInterface;

/**
 * Provides a listing of Drawing type entities.
 */
class DrawingTypeListBuilder extends ConfigEntityListBuilder
{

  /**
   * {@inheritdoc}
   */
    public function buildHeader()
    {
        $header['label'] = $this->t('Drawing type');
        $header['id'] = $this->t('Machine name');
        return $header + parent::buildHeader();
    }

    /**
     * {@inheritdoc}
     */
    public function buildRow(EntityInterface $entity)
    {
        $row['label'] = $entity->label();
        $row['id'] = $entity->id();
        // You probably want a few more properties here...
        return $row + parent::buildRow($entity);
    }
}
