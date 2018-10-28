<?php

namespace Drupal\add_to_calendar_field\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;

/**
 * EventForm class.
 */
class EventForm extends FormBase
{

    private $nid;
    private $configName;
    
    /**
     * Gets the configuration names that will be editable.
     *
     * @return array
     *   An array of configuration object names that are editable if called in
     *   conjunction with the trait's config() method.
     */
    protected function getEditableConfigNames() {
        return [$this->configName];
    }

    public function setEditableConfigNames(){
        $this->configName = 'config.send_email_form_'.$this->nid;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormId() {

        $nid = $this->nid;

        return 'send_email_form_'.$nid;
    }

    public function setFormId($nid){
         $this->nid = $nid;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, $options = array()) {

        $form = [];

        $form_container = 'send_email_form_container_'.$options['nid'];
        $form['#prefix'] = '<div id="'.$form_container.'">';
        $form['#suffix'] = '</div>';

        // The status messages that will contain any form errors.
        $form['status_messages'] = [
            '#type' => 'status_messages',
            '#weight' => -10,
        ];

        $form['name'] = array(
            '#title' => $this->t('Name'),
            '#type' => 'textfield',
            '#required' => TRUE,
            '#size' => 'auto',
            '#description' => t('Enter your name'),
            '#attributes' => ['placeholder'=>$this->t('Name')]
        );

        $form['email'] = array(
            '#title' => $this->t('Email'),
            '#type' => 'textfield',
            '#required' => TRUE,
            '#size' => 'auto',
            '#description' => $this->t('Enter email address'),
            '#attributes' => ['placeholder' => $this->t('address@email.com')]
        );


        $form['ics'] = array(
            '#title' => 'ics_file',
            '#type' => 'hidden',
            '#value'=> $options['ics'],
        );

        $form['nid'] = array(
            '#title' => 'node_id',
            '#type' => 'hidden',
            '#value' => $options['nid'],
        );


        $form['actions'] = array('#type' => 'actions');
        $form['actions']['send'] = [
            '#type' => 'submit',
            '#value' => $this->t('Send Email'),
            '#name' => 'send-button',
            '#attributes' => [
                'class' => ['use-ajax btn-lg btn-primary pull-right']],
            '#ajax' => [
                'callback' => [$this, 'submitEventFormAjax'],
                'event' => 'click',
                'disable-refocus' => TRUE,
            ],
        ];

        $form['#attached']['library'][] = 'core/drupal.dialog.ajax';

        return $form;
    }

    /**
     * AJAX callback handler that displays any errors or a success message.
     */
    public function submitEventFormAjax(array $form, FormStateInterface $form_state) {

        $response = new AjaxResponse();

        $email = $form_state->getValue('email');
        $ics = $form_state->getValue('ics');

        // If there are any form errors, re-display the form.
        if ($form_state->hasAnyErrors()) {
            $response->addCommand(new OpenModalDialogCommand("There was an error sending your email", $form, ['width' => 800]));
        }
        else {
            $mailManager = \Drupal::service('plugin.manager.mail');
            $module = 'add_to_calendar_field';
            $key = 'send_ics_file';
            $to = $email;
            $params['message'] = 'Add Event to your calendar. Looking forward to seeing you!';
            $params['title'] = 'Add Event to Calendar';
            $params['ics'] = $ics;
            $langcode = 'en';
            $send = TRUE;

            $result = $mailManager->mail($module, $key, $to, $langcode, $params, NULL, $send);

            if ($result['result'] !== true) {
                drupal_set_message('There was a problem sending your message and it was not sent.', 'error');
                $response->addCommand(new OpenModalDialogCommand($this->t('Error'), $form, ['width' => 800]));
            }
            else {

                $modal = [];
                $modal['#prefix'] = '<div id="modal-form-success">';
                $modal['#suffix'] = '</div>';

                // The status messages that will contain any form errors.
                $modal['status_messages'] = [
                    '#type' => 'status_messages',
                    '#weight' => -10,
                ];

                drupal_set_message('Your email was sent.', 'status');
                $response->addCommand(new OpenModalDialogCommand($this->t('Success'), $modal, ['width' => 800]));
            }

        }

        return $response;
    }

    /**
     * {@inheritdoc}
     *
     */
    public function validateForm(array &$form, FormStateInterface $form_state) {
        // Assert the email is valid
        // Assert the firstname is valid
        if (!$form_state->getValue('name') || empty($form_state->getValue('name'))) {
            $form_state->setErrorByName('name', $this->t('You must enter a name.'));
        }

        if (!$form_state->getValue('email') || !filter_var($form_state->getValue('email'), FILTER_VALIDATE_EMAIL)) {
            $form_state->setErrorByName('email', $this->t('Entered an invalid email address.'));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {}

}
