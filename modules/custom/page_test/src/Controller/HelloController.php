<?php

namespace Drupal\page_test\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Defines HelloController class.
 */
class HelloController extends ControllerBase {

  /**
   * Display the markup.
   *
   * @return array
   *   Return markup array.
   */
  public function content() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Hello, World!'),
    ];
  }

  public function pagina() {
    return [
      '#type' => 'markup',
      '#markup' => 'Esta es mi nueva página',
    ];
  }

  public function pagina1() {
    return [
      '#type' => 'markup',
      '#markup' => 'Esta es la página 1',
    ];
  }

}
