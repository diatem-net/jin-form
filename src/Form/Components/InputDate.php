<?php

/**
 * Jin Framework
 * Diatem
 */

namespace Jin2\Form\Components;

/**
 * Composant <input type="date">
 */
class InputDate extends AbstractComponent
{

  /**
   * Implémente la fonction AbstractComponent::getType()
   *
   * @return string
   */
  public static function getType()
  {
    return 'inputdate';
  }

  /**
   * Surcharger la méthode AbstracComponent::getValue() pour formatter la date
   *
   * @return srintg
   */
  public function getValue()
  {
    $value = parent::getValue();

    if (is_null($value) || empty($value)) {
      return null;
    }

    if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $value)) {
      return $value;
    }

    $dt = new \DateTime($value);
    return $dt->format('d/m/Y');
  }

}
