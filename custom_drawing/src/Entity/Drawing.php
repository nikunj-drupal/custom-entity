<?php

namespace Drupal\custom_drawing\Entity;

use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityPublishedTrait;
use Drupal\Core\Entity\EntityTypeInterface;

/**
 * Defines the Drawing entity.
 *
 * @ingroup custom_drawing
 *
 * @ContentEntityType(
 *   id = "drawing",
 *   label = @Translation("Drawing"),
 *   bundle_label = @Translation("Drawing type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\custom_drawing\DrawingListBuilder",
 *     "views_data" = "Drupal\custom_drawing\Entity\DrawingViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\custom_drawing\Form\DrawingForm",
 *       "add" = "Drupal\custom_drawing\Form\DrawingForm",
 *       "edit" = "Drupal\custom_drawing\Form\DrawingForm",
 *       "delete" = "Drupal\custom_drawing\Form\DrawingDeleteForm",
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\custom_drawing\DrawingHtmlRouteProvider",
 *     },
 *     "access" = "Drupal\custom_drawing\DrawingAccessControlHandler",
 *   },
 *   base_table = "drawing",
 *   translatable = FALSE,
 *   permission_granularity = "bundle",
 *   admin_permission = "administer drawing entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "langcode" = "langcode",
 *     "published" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/drawing/{drawing}",
 *     "add-page" = "/admin/structure/drawing/add",
 *     "add-form" = "/admin/structure/drawing/add/{drawing_type}",
 *     "edit-form" = "/admin/structure/drawing/{drawing}/edit",
 *     "delete-form" = "/admin/structure/drawing/{drawing}/delete",
 *     "collection" = "/admin/structure/drawing",
 *   },
 *   bundle_entity_type = "drawing_type",
 *   field_ui_base_route = "entity.drawing_type.edit_form"
 * )
 */
class Drawing extends ContentEntityBase implements DrawingInterface
{
    use EntityChangedTrait;
    use EntityPublishedTrait;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->get('name')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setName($name)
    {
        $this->set('name', $name);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedTime()
    {
        return $this->get('created')->value;
    }

    /**
     * {@inheritdoc}
     */
    public function setCreatedTime($timestamp)
    {
        $this->set('created', $timestamp);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwner()
    {
        return $this->get('uid')->entity;
    }

    /**
     * {@inheritdoc}
     */
    public function getOwnerId()
    {
        return $this->get('uid')->target_id;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwnerId($uid)
    {
        $this->set('uid', $uid);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOwner(UserInterface $account)
    {
        $this->set('uid', $account->id());
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public static function baseFieldDefinitions(EntityTypeInterface $entity_type)
    {
        $fields = parent::baseFieldDefinitions($entity_type);

        // Add the published field.
        $fields += static::publishedBaseFieldDefinitions($entity_type);

        $fields['uid'] = BaseFieldDefinition::create('entity_reference')
          ->setLabel(t('Authored by'))
          ->setDescription(t('The user ID of author of the Event entity.'))
          ->setSetting('target_type', 'user')
          ->setSetting('handler', 'default')
          ->setDefaultValueCallback('Drupal\node\Entity\Node::getCurrentUserId')
          ->setTranslatable(true)
          ->setDisplayOptions('view', [
            'label' => 'hidden',
            'type' => 'author',
            'weight' => 0,
          ])
          ->setDisplayOptions('form', [
            'type' => 'entity_reference_autocomplete',
            'weight' => 5,
            'settings' => [
              'match_operator' => 'CONTAINS',
              'size' => '60',
              'autocomplete_type' => 'tags',
              'placeholder' => '',
            ],
          ])
          ->setDisplayConfigurable('form', true)
          ->setDisplayConfigurable('view', true);

        $fields['name'] = BaseFieldDefinition::create('string')
          ->setLabel(t('Title'))
          ->setDescription(t('The product title.'))
          ->setRequired(true)
          ->setTranslatable(true)
          ->setSettings([
              'default_value' => '',
              'max_length' => 255,
            ])
          ->setDisplayOptions('view', [
            'label' => 'hidden',
            'type' => 'string',
            'weight' => -5,
          ])
          ->setDisplayOptions('form', [
            'type' => 'string_textfield',
            'weight' => -5,
          ])
          ->setDisplayConfigurable('form', true)
          ->setDisplayConfigurable('view', true);

        $fields['game'] = BaseFieldDefinition::create('entity_reference')
          ->setLabel(t('Draw Game'))
          ->setDescription(t('The Drawing game this drawing is for.'))
          ->setRevisionable(true)
          ->setSetting('target_type', 'node')
          ->setSetting('handler', 'default')
          ->setTranslatable(true)
          ->setDisplayOptions('view', [
            'label' => 'hidden',
            'type' => 'node',
            'weight' => 0,
          ])
          ->setDisplayOptions('form', [
            'type' => 'entity_reference_autocomplete',
            'weight' => 5,
            'settings' => [
              'match_operator' => 'CONTAINS',
              'size' => '60',
              'autocomplete_type' => 'tags',
              'placeholder' => '',
            ],
          ])
          ->setDisplayConfigurable('form', true)
          ->setDisplayConfigurable('view', true);

        $fields['status']->setDescription(t('A boolean indicating whether the Drawing is published.'))
          ->setDisplayOptions('form', [
            'type' => 'boolean_checkbox',
            'weight' => -3,
          ]);

        $fields['created'] = BaseFieldDefinition::create('created')
          ->setLabel(t('Created'))
          ->setDescription(t('The time that the entity was created.'));

        $fields['changed'] = BaseFieldDefinition::create('changed')
          ->setLabel(t('Changed'))
          ->setDescription(t('The time that the entity was last edited.'));

        return $fields;
    }
}
