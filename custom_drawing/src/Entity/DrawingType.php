<?php

namespace Drupal\custom_drawing\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Drawing type entity.
 *
 * @ConfigEntityType(
 *   id = "drawing_type",
 *   label = @Translation("Drawing type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\custom_drawing\DrawingTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\custom_drawing\Form\DrawingTypeForm",
 *       "edit" = "Drupal\custom_drawing\Form\DrawingTypeForm",
 *       "delete" = "Drupal\custom_drawing\Form\DrawingTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\custom_drawing\DrawingTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "drawing_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "drawing",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/drawing_type/{drawing_type}",
 *     "add-form" = "/admin/structure/drawing_type/add",
 *     "edit-form" = "/admin/structure/drawing_type/{drawing_type}/edit",
 *     "delete-form" = "/admin/structure/drawing_type/{drawing_type}/delete",
 *     "collection" = "/admin/structure/drawing_type"
 *   }
 * )
 */
class DrawingType extends ConfigEntityBundleBase implements DrawingTypeInterface
{

  /**
   * The Drawing type ID.
   *
   * @var string
   */
    protected $id;

    /**
     * The Drawing type label.
     *
     * @var string
     */
    protected $label;
}
