<?php

namespace Drupal\custom_drawing;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Drawing entity.
 *
 * @see \Drupal\custom_drawing\Entity\Drawing.
 */
class DrawingAccessControlHandler extends EntityAccessControlHandler
{

  /**
   * {@inheritdoc}
   */
    protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account)
    {
        /** @var \Drupal\custom_drawing\Entity\DrawingInterface $entity */

        switch ($operation) {
            case 'view':
                if (!$entity->isPublished()) {
                    return AccessResult::allowedIfHasPermission($account, 'view unpublished drawing entities');
                }
                return AccessResult::allowedIfHasPermission($account, 'view published drawing entities');

            case 'update':
                return AccessResult::allowedIfHasPermission($account, 'edit drawing entities');

            case 'delete':
                return AccessResult::allowedIfHasPermission($account, 'delete drawing entities');
        }

        // Unknown operation, no opinion.
        return AccessResult::neutral();
    }

    /**
     * {@inheritdoc}
     */
    protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = null)
    {
        return AccessResult::allowedIfHasPermission($account, 'add drawing entities');
    }

    /**
     * Test for given 'own' permission.
     *
     * @param \Drupal\Core\Entity\EntityInterface $entity
     * @param $operation
     * @param \Drupal\Core\Session\AccountInterface $account
     *
     * @return string|null
     *   The permission string indicating it's allowed.
     */
    protected function checkOwn(EntityInterface $entity, $operation, AccountInterface $account)
    {
        $status = $entity->isPublished();
        $uid = $entity->getOwnerId();

        $is_own = $account->isAuthenticated() && $account->id() == $uid;
        if (!$is_own) {
            return;
        }

        $bundle = $entity->bundle();

        $ops = [
          'create' => '%bundle add own %bundle entities',
          'view unpublished' => '%bundle view own unpublished %bundle entities',
          'view' => '%bundle view own entities',
          'update' => '%bundle edit own entities',
          'delete' => '%bundle delete own entities',
        ];
        $permission = strtr($ops[$operation], ['%bundle' => $bundle]);

        if ($operation === 'view unpublished') {
            if (!$status && $account->hasPermission($permission)) {
                return $permission;
            } else {
                return null;
            }
        }
        if ($account->hasPermission($permission)) {
            return $permission;
        }

        return null;
    }
}
