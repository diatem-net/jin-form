<?php

/**
 * Jin Framework
 * Diatem
 */

namespace Jin2\Form\Validators;

use Jin2\Language\Translation;

/**
 * Validateur : teste si une valeur est numérique
 */
class IsNumeric extends AbstractValidator
{

  /**
   * Implémente la fonction AbstractValidator::getType()
   *
   * @return string
   */
  public static function getType()
  {
    return 'isnumeric';
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
    if (!empty($value) && !is_numeric($value)) {
      parent::addError(Translation::get('validatorerror_isnumeric'));
      return false;
    }
    return true;
  }

}
