<?php

/**
 * Jin Framework
 * Diatem
 */

namespace Jin2\Form\Validators;

use Jin2\Language\Translation;

/**
 * Validateur : teste si une valeur est un numérique compris entre minValue et maxValue (bornes inclues)
 */
class NumRange extends AbstractValidator
{

  /**
   * Constructeur
   *
   * @param type $args    Tableau d'arguments. minValue (valeur minimale testée) et maxValue  (valeur maximale testée) requis
   */
  public function __construct($args)
  {
    parent::__construct($args, array('minValue', 'maxValue'));
  }

  /**
   * Implémente la fonction AbstractValidator::getType()
   *
   * @return string
   */
  public static function getType()
  {
    return 'numrange';
  }

  /**
   * Teste la validité
   *
   * @param  mixed $value Valeur à tester
   * @return boolean
   */
  public function isValid($value)
  {
    parent::resetErrors();
    if (!is_numeric($value)){
      return true;
    }
    if (!is_numeric($value) || $value < parent::getArgValue('minValue') || $value > parent::getArgValue('maxValue')) {
      $eMsg = Translation::get('validatorerror_numrange');
      $eMsg = str_replace('%minValue%', parent::getArgValue('minValue'), $eMsg);
      $eMsg = str_replace('%maxValue%', parent::getArgValue('maxValue'), $eMsg);
      parent::addError($eMsg);
      return false;
    }
    return true;
  }

}
