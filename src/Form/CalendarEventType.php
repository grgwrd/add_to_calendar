<?php

namespace Drupal\add_to_calendar\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\field\Entity\FieldConfig;
use Drupal\node\Entity\NodeType;
/**
 * Class CalendarEventType.
 */
class CalendarEventType extends ConfigFormBase {


  private $formFields = ['name_of_content_type', 'field_name_for_title_of_event',
                         'field_name_for_start_date', 'field_name_for_end_date',
                          'field_name_for_location', 'field_name_for_description'];

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'calendar_event_type';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'calendar_event_type.settings',
    ];
  }


  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // save settings for Content type configurations
    $config = $this->config('calendar_event_type.settings');

    $form['directions'] = [
      '#type'=>'markup',
      '#markup'=>'<h4>Define the machine name labels for content type.</h4>'.
                 '<p>They should only contain lowercase characters and underscores.</p>',
    ];

    $form['name_of_content_type'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Name of Content Type'),
      '#description' => $this->t('Enter the machine name for the content type.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '0',
      '#required' => TRUE,
      '#default_value' => $config->get('name_of_content_type'),
    ];
    $form['field_name_for_title_of_event'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Field name for Title of event'),
      '#description' => $this->t('Enter the machine name field for the title.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '1',
      '#required' => TRUE,
      '#default_value' => $config->get('field_name_for_title_of_event'),
    ];
    $form['field_name_for_start_date'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Field name for start date and time'),
      '#description' => $this->t('Enter the machine name field for the start date and time.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '2',
      '#required' => TRUE,
      '#default_value' => $config->get('field_name_for_start_date'),
    ];
    $form['field_name_for_end_date'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Field name for end date and time'),
      '#description' => $this->t('Enter the machine name field for the end date and time.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '3',
      '#required' => TRUE,
      '#default_value' => $config->get('field_name_for_end_date'),
    ];
    $form['field_name_for_location'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Field name for location'),
      '#description' => $this->t('Enter the machine name field for the location.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '4',
      '#required' => FALSE,
      '#default_value' => $config->get('field_name_for_location'),
    ];
    $form['field_name_for_description'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Field name for description'),
      '#description' => $this->t('Enter the machine name field for the description.'),
      '#maxlength' => 64,
      '#size' => 64,
      '#weight' => '5',
      '#required' => FALSE,
      '#default_value' => $config->get('field_name_for_description'),
    ];

    return parent::buildForm($form, $form_state);

  }

  public function validateForm(array &$form, FormStateInterface $form_state)
  {

    /*
     * Step 1: validate inputs are valid machine name strings.
     */
    $fields = $this->formFields;
    //lowercase_lowercase only
    $regex = '/^[a-z][_a-z]+$/';
    $validValues = TRUE;
    foreach($fields as $field){
      $value =  $form_state->getValue($field);
      if($value && !preg_match($regex, $value)){
        $form_state->setErrorByName($field, $this->t('Machine name should only contain lowercase and underscores.'));
        $validValues = FALSE;
      }
    }
    /*
     * Step 2: validate given machine name for content type.
     */
    if($validValues){
      $machineName = $form_state->getValue('name_of_content_type');
      $contentTypes = \Drupal::entityTypeManager()->getStorage('node_type')->loadMultiple();
      $validContentType = FALSE;
      foreach($contentTypes as $contentType){
        if($contentType instanceof NodeType){
          $nodeLabel = $contentType->getOriginalId();
        }
        if($machineName == $nodeLabel){
          $validContentType = TRUE;
        }
      }
      if(!$validContentType){
        $form_state->setErrorByName('name_of_content_type', $this->t('Machine name does not match content type.'));
        $validValues = FALSE;
      }
    }
    if($validValues){
      /*
      * Step 3: validate given machine name for fields.
      */
      $definitions = \Drupal::service('entity_field.manager')->getFieldDefinitions('node', $machineName);
      //loop through fields and match machine names for each field
      for($i = 1; $i < 4; $i++){
        $value =  $form_state->getValue($fields[$i]);
        $fieldFound = FALSE;
        foreach($definitions as $definition){
          if($definition instanceof FieldConfig){
            $name = $definition->get('field_name');
            if($name == $value){
              //check field types for start and end dates
              $fieldType = $definition->get('field_type');
              if($fields[$i] == "field_name_for_start_date" OR $fields[$i] == "field_name_for_end_date"){
                if($fieldType != "datetime"){
                  $form_state->setErrorByName($fields[$i], $this->t('Field should be a datetime type.'));
                }
              }
              $fieldFound = TRUE;
            }
          }
        }
        if(!$fieldFound){
          $form_state->setErrorByName($fields[$i], $this->t('Machine name not found for field name.'));
        }
      }
    }
    parent::validateForm($form, $form_state); // TODO: Change the autogenerated stub

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    parent::submitForm($form, $form_state);
    // save settings for Content type configurations
    $config = $this->config('calendar_event_type.settings');

    $config->set('name_of_content_type', $form_state->getValue('name_of_content_type'));
    $config->set('field_name_for_title_of_event', $form_state->getValue('field_name_for_title_of_event'));
    $config->set('field_name_for_start_date', $form_state->getValue('field_name_for_start_date'));
    $config->set('field_name_for_end_date', $form_state->getValue('field_name_for_end_date'));
    $config->set('field_name_for_location', $form_state->getValue('field_name_for_location'));
    $config->set('field_name_for_description', $form_state->getValue('field_name_for_description'));
    $config->save();

  }
}
