<?php

  namespace Drupal\form_example\Form;

  use Drupal\Core\Form\FormBase;
  use Drupal\Core\Form\FormStateInterface;
  use Symfony\Component\HttpFoundation\RedirectResponse;
  /**
   * Implements an example form.
   */

  class editform extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'form_example_editform';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $arg = null) {
    //attach css and js files
    $form['#attached']['library'][] = 'form_example/form_example_libraries';

    //add markup
    $form['elemento_imagen'] = array(
      //'#markup' => '<img src="https://i0.wp.com/arnimartinez.com/wp-content/uploads/2017/12/datos-personales.gif?resize=525%2C238">'
      '#markup' => '<img class="zoom" src="https://i.ytimg.com/vi/5IjoI91BZXM/hqdefault.jpg">'
    );

    //********* CONTENEDOR FIELDSET Y SU CONTENIDO *********
    $form['datos_personales'] = array(
      '#type' => 'fieldset',
      '#title' => t('Datos Personales'),
      '#attributes' => array(
        'class' => array('mi_clase')
      ),
    );

    $form['datos_personales']['nombre'] = array(
      '#type' => 'textfield',
      '#title' => t('Introduzca su nombre'),
      //'#default_value' => $node->title,
      '#size' => 60,
      '#maxlength' => 128,
      '#required' => true,
    );

    $form['datos_personales']['apellidos'] = array(
      '#type' => 'textfield',
      '#title' => t('Introduzca sus apellidos'),
      //'#default_value' => $node->title,
      '#size' => 150,
      '#maxlength' => 128,
      '#required' => false,
    );

    $form['datos_personales']['email'] = array(
      '#type' => 'email',
      '#title' => t('Introduzca su email'),
    );
    //************** FIN CONTENEDOR FIELDSET **************

    //********* CONTENEDOR DETAILS Y SU CONTENIDO *********
    $form['datos_institucionales'] = array(
      '#type' => 'details',
      '#title' => t('Datos Institucionales'),
      '#open' => true,
    );

    $form['datos_institucionales']['phone_number'] = [
      '#type' => 'tel',
      '#title' => $this->t('Introduzca su teléfono'),
    ];

    $form['datos_institucionales']['fecha_contratacion'] = array(
      '#type' => 'date',
      '#title' => $this
        ->t('Fecha de contratación'),
      '#default_value' => date(),
    );
    //*************** FIN CONTENEDOR DETAILS ***************

    //***************** BOTONES Y ACCIONES *****************
    $form['actions']['#type'] = 'actions';

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
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
    //******************************************************
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    //validación teléfono
    $phone = $form_state->getValue('phone_number');
    if (strlen($phone) > 0 && strlen($phone) < 3 ) {
      $form_state->setErrorByName('phone_number', $this->t('Número inválido, debe tener más dígitos.'));
    }

    //validación email (no es necesaria ya existe una por js)
    $email = $form_state->getValue('email');
    $findme   = '@';
    $pos = strpos($email, $findme);

    if ($pos === false) {
        //$email no encontrado
        $form_state->setErrorByName('email', $this->t('Email inválido.'));
    }

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    global $base_url;
    $database = \Drupal::database();

    $campos = array(
      'nombre' => $form_state->getValue('nombre'),
      'apellidos' => $form_state->getValue('apellidos'),
      'email' => $form_state->getValue('email'),
      'telefono' => $form_state->getValue('phone_number'),
      'fecha' => $form_state->getValue('fecha_contratacion'),
    );

    $result = $database->insert('datospersonales')
      ->fields($campos)
      ->execute();

    drupal_set_message(t( 'Datos guardados correctamente. Se ha creado el registro ' . $result ));

    $form_state->setRedirect('form_example.mostrartodo');

    //$this->messenger()->addStatus($this->t('Su número de teléfono es: @number', ['@number' => $form_state->getValue('phone_number')]));

    /*
        $this->messenger()->addStatus($this->t('Los datos fueron correctamente guardados.'));

        $respuesta = new RedirectResponse($base_url, 302);
        $respuesta->send();
        return;
    */

  }

}
