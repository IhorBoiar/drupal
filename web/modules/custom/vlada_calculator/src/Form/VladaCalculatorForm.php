<?php

namespace Drupal\vlada_calculator\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class VladaCalculatorForm extends FormBase {

  public $action;

  public function getFormId() {
    return 'vlada_calculator_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['first'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First value'),
    ];

    $form['action'] = [
      '#type' => 'select',
      '#title' => $this->t('Action'),
      '#options' => [
        'plus' => '+',
        'minus' => '-',
        'multiplication' => '*',
        'division' => '/'
      ],
    ];

    $form['second'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Second value'),
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Result'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $first_value = $form_state->getValue('first');
    $second_value = $form_state->getValue('second');
    $action = $form_state->getValue('action');

    $result = FALSE;
    switch ($action) {
      case 'plus':
        $result = $first_value + $second_value;
        break;

      case 'minus':
        $result = $first_value - $second_value;
        break;

      case 'multiplication' :
        $result = $first_value * $second_value;
        break;

      case 'division' :
        $result = $first_value / $second_value;
        break;
    }

    if ($result == 0) {
      $result = 'Result is 0';
    }

    if ($result) {
      \Drupal::messenger()->addStatus($result);
    }
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!is_numeric($form_state->getValue('first'))) {
      $form_state->setErrorByName('first', $this->t('The first value should be numeric.'));
    }

    if (!is_numeric($form_state->getValue('second'))) {
      $form_state->setErrorByName('second', $this->t('The second value should be numeric.'));
    }

    if($action = 'division' ) {
      if($form_state->getValue('second') == 0) {
        $form_state->setErrorByName('division', $this->t('The second value will can not 0.'));
      }
    }
  }

}
