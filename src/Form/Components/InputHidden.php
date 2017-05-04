<?php

/**
 * Jin Framework
 * Diatem
 */

namespace Jin2\Form\Components;

/**
 * Composant <input type="hidden">
 */
class InputHidden extends AbstractComponent
{

  /**
   * Implémente la fonction AbstractComponent::getType()
   *
   * @return string
   */
  public static function getType()
  {
    return 'inputhidden';
  }

}
