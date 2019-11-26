<?php

  namespace Drupal\form_example\Form;

  use Drupal\Core\Form\FormBase;
  use Drupal\Core\Form\FormStateInterface;
  use Symfony\Component\HttpFoundation\RedirectResponse;
  /**
   * Implements an example form.
   */

  class deleteform extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'form_example_deleteform';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $arg = null) {

    $registro = [];
    //$registro = $this->listarunregistro($arg);
    //ksm($registro);

    //attach css and js files
    $form['#attached']['library'][] = 'form_example/form_example_libraries';
    $form['#attached']['library'][] = 'seven/global-styling';

    //add markup
    $form['elemento_imagen'] = array(
      //'#markup' => '<img src="https://i0.wp.com/arnimartinez.com/wp-content/uploads/2017/12/datos-personales.gif?resize=525%2C238">'
      '#markup' => 'El registro a eliminar es el ' . $arg . '.<br><br><i>Esta acción no se podrá deshacer.</i>'
    );


    //***************** BOTONES Y ACCIONES *****************
    $form['actions']['#type'] = 'actions';

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Delete'),
      '#button_type' => 'primary',
      '#attributes' => array(
        'class' => array('mibotonprincipal')
      ),
    ];

    //'#limit_validation_errors' => array(), -> esta línea se salta todo tipo de validaciones (es como el event.preventDefault de JavaSript!!)
    $form['actions']['cancelar'] = [
      '#type' => 'submit',
      '#value' => $this->t('Cancel'),
      '#submit' => array('form_example_cancelar'),
      '#limit_validation_errors' => array(),
      '#attributes' => array(
        'class' => array('mibotonprincipal')
      ),
    ];

    $form['idregistro'] = array(
      '#type' => 'hidden',
      '#value' => $arg,
    );

    //******************************************************
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    //validación formulario

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    global $base_url;
    $database = \Drupal::database();

    $id = $form_state->getValue('idregistro');

    $database->delete('datospersonales')
      ->condition('id', $id, '=')
      ->execute();

    drupal_set_message(t( 'Se ha eliminado el registro ' . $id ));

    $form_state->setRedirect('form_example.mostrartodo');

  }

}
