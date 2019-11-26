<?php

namespace Drupal\form_example\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\form_example\Form\editform;
/**
 * Defines HelloController class.
 */
class Form_exampleController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */

    public function mostrarunregistro($arg) {
      $contenido = [];

      $contenido['linea1'] = array(
                                    '#markup' => '<strong>Esta información es confidencial. El id del registro es: ' . $arg . '</strong><br>',
                                  );

      $registro = [];
      $registro = editform::listarunregistro($arg);
      ksm($registro);

      return $contenido;
    }

    public function mostrartodo() {
      $contenido = [];

      $contenido['linea1'] = array(
                                    '#markup' => '<strong>En esta sección se gestionarán los datos personales de los usuarios</strong><br>',
                                  );

      $url = Url::fromRoute('form_example.addform');
      $project_link = Link::fromTextAndUrl(t('Crear nuevo registro'), $url);
      $project_link = $project_link->toRenderable();
      // If you need some attributes.
      $project_link['#attributes'] = array('class' => array('button', 'button--primary', 'button--small'));

      $contenido['linea2'] = array(
                                    '#markup' => '<i>Para crear nuevos registros pulse click en el siguiente botón ' . render($project_link) . '</i><br>',
                                  );

      $rows = listar();
      // Build a render array which will be themed as a table with a pager.
      $contenido['table'] = [
        '#type' => 'table',
        '#header' => [  $this->t('Id'),
                        $this->t('Nombre'),
                        $this->t('Apellidos'),
                        $this->t('Email'),
                        $this->t('Teléfono'),
                        $this->t('Fecha'),
                        $this->t('Ver'),
                        $this->t('Editar'),
                        $this->t('Eliminar')
                      ],
        '#rows' => $rows,
        '#empty' => $this->t('There are no nodes to display. Please <a href=":url">create a node</a>.', [
          ':url' => Url::fromRoute('node.add', ['node_type' => 'page'])->toString(),
        ]),
      ];

      // Add our pager element so the user can choose which pagination to see.
      // This will add a '?page=1' fragment to the links to subsequent pages.
      $contenido['pager'] = [
        '#type' => 'pager',
        '#weight' => 10,
      ];

      return $contenido;

    }

}

function listar(){

  global $base_url;
  $database = \Drupal::database();
  // Using the TableSort Extender is what tells  the query object that we
  // are sorting.
  $query = $database->select('datospersonales', 'dp')
    ->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(5);

  $query->fields('dp');

  // Don't forget to tell the query object how to find the header information.
  $result = $query
    //->orderByHeader($header)
    ->execute();

  $rows = [];
  foreach ($result as $row) {
    // Normally we would add some nice formatting to our rows
    // but for our purpose we are simply going to add our row
    // to the array.
    $row = (array) $row;

    // External Uri.
    //use Drupal\Core\Url;
    $url = Url::fromUri( $base_url . '/form_example/' . $row['id'] );
    $ver_link = \Drupal::l(t('Ver'), $url);
    $row['ver'] = $ver_link;

    $url = Url::fromUri( $base_url . '/form_example/' . $row['id'] . '/edit');
    $editar_link = \Drupal::l(t('Editar'), $url);
    $row['editar'] = $editar_link;

    $url = Url::fromUri( $base_url . '/form_example/' . $row['id'] . '/delete' );
    $eliminar_link = \Drupal::l(t('Eliminar'), $url);
    $row['eliminar'] = $eliminar_link;

    $rows[] = $row;
    //$rows[] = ['data' => (array) $row];
  }

  return $rows;

}
