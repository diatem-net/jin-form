<?php

/**
 * Jin Framework
 * Diatem
 */

namespace Jin2\Form\Components;

/**
 * Composant <input type="text">
 */
class InputText extends AbstractComponent
{

  /**
   * Implémente la fonction AbstractComponent::getType()
   *
   * @return string
   */
  public static function getType()
  {
    return 'inputtext';
  }

}
