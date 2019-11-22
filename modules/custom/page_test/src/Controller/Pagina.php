<?php

namespace Drupal\page_test\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Defines HelloController class.
 */
class Pagina extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */

  public function paginadesdeotrocontrolador() {
    return [
      '#type' => 'markup',
      '#markup' => 'Página desde otro controlador',
    ];
  }

  public function verpagina( $idpagina = null ) {
    return [
      '#type' => 'markup',
      '#markup' => 'Página con parámetro id: '.$idpagina,
    ];
  }

    public function verpaginacustom( $custom_arg ) {
      return [
        '#type' => 'markup',
        '#markup' => 'Página con parámetro id: '.$custom_arg,
      ];
    }

    public function varias() {
      $contenido = array();
      $contenido['linea1'] = array('#markup' => '<strong>Linea 1</strong><br>',);
      $contenido['linea2'] = array('#markup' => '<i>Linea 2</i><br>',);
      $contenido['linea3'] = array('#markup' => 'Linea 3<br>',);
      return $contenido;
      //return 33;
    }

    public function form() {
      //$form = \Drupal::formBuilder()->getForm('Drupal\example\Form\ExampleForm');
      $form = \Drupal::formBuilder()->getForm('Drupal\form_example\Form\addform');
      $contenido = [];

      $contenido['linea1'] = ['#markup' => '<strong>Linea 1</strong><br>',];
      $contenido['linea2'] = ['#markup' => '<i>Linea 2</i><br>',];
      $contenido['linea3'] = ['#markup' => 'Linea 3<br>',];
      $contenido['linea4'] = $form;
      return $contenido;
      //return 33;
    }

    public function template() {
      $form = \Drupal::formBuilder()->getForm('Drupal\form_example\Form\addform');

        return [
          '#theme' => 'my_template',
          '#mi_variable' => $this->t('Esta es mi variable'),
          '#form' => $form,
        ];

      }

}
