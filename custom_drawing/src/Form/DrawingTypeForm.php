<?php

namespace Drupal\custom_drawing\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class DrawingTypeForm.
 */
class DrawingTypeForm extends EntityForm
{

  /**
   * {@inheritdoc}
   */
    public function form(array $form, FormStateInterface $form_state)
    {
        $form = parent::form($form, $form_state);

        $drawing_type = $this->entity;
        $form['label'] = [
          '#type' => 'textfield',
          '#title' => $this->t('Label'),
          '#maxlength' => 255,
          '#default_value' => $drawing_type->label(),
          '#description' => $this->t("Label for the Drawing type."),
          '#required' => true,
        ];

        $form['id'] = [
          '#type' => 'machine_name',
          '#default_value' => $drawing_type->id(),
          '#machine_name' => [
            'exists' => '\Drupal\custom_drawing\Entity\DrawingType::load',
          ],
          '#disabled' => !$drawing_type->isNew(),
        ];

        /* You will need additional form elements for your custom properties. */

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function save(array $form, FormStateInterface $form_state)
    {
        $drawing_type = $this->entity;
        $status = $drawing_type->save();

        switch ($status) {
            case SAVED_NEW:
                $this->messenger()->addMessage($this->t('Created the %label Drawing type.', [
                  '%label' => $drawing_type->label(),
                ]));
                break;

            default:
                $this->messenger()->addMessage($this->t('Saved the %label Drawing type.', [
                  '%label' => $drawing_type->label(),
                ]));
        }
        $form_state->setRedirectUrl($drawing_type->toUrl('collection'));
    }
}
