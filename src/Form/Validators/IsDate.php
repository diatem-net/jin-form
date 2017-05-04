<?php

/**
 * Jin Framework
 * Diatem
 */

namespace Jin2\Form\Validators;

use Jin2\Language\Translation;

/**
 * Validateur : teste si une valeur est un email valide
 */
class IsDate extends AbstractValidator
{

  /**
   * Constructeur
   *
   * @param  array $args          Tableau d'arguments array('arg1'=>'val1','arg2'=>'val2)
   * @throws \Exception
   */
  public function __construct($args)
  {
    return parent::__construct($args, array('format'));
  }

  /**
   * Implémente la fonction AbstractValidator::getType()
   *
   * @return string
   */
  public static function getType()
  {
    return 'isdate';
  }

  /**
   * Teste la validité
   *
   * @param mixed $value Valeur à tester
   * @return boolean
   */
  public function isValid($value)
  {
    parent::resetErrors();
    if (empty($value)) {
      return true;
    }

    $format = $this->getArgValue('format');
    $date = \DateTime::createFromFormat($format, $value);
    $res = $date && $date->format($format) == $value;

    if (!$res) {
      $eMsg = Translation::get('validatorerror_isdate');
      $eMsg = str_replace('%format%', parent::getArgValue('format'), $eMsg);
      parent::addError($eMsg);
    }
    return $res;
  }

}
