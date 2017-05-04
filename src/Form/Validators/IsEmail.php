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
class IsEmail extends AbstractValidator
{

  /**
   * Implémente la fonction AbstractValidator::getType()
   *
   * @return string
   */
  public static function getType()
  {
    return 'isemail';
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
    if (!is_null($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
      parent::addError(Translation::get('validatorerror_isemail'));
      return false;
    }
    return true;
  }

}
