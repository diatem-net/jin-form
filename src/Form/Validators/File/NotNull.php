<?php

/**
 * Jin Framework
 * Diatem
 */

namespace Jin2\Form\Validators\File;

use Jin2\Form\Validators\AbstractValidator;
use Jin2\Language\Translation;

/**
 * Validateur : teste si le fichier est renseigné
 */
class NotNull extends AbstractValidator
{

  /**
   * Implémente la fonction AbstractValidator::getType()
   *
   * @return string
   */
  public static function getType()
  {
    return 'filenotnull';
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
    if (empty($value) || is_null($value)) {
      parent::addError(Translation::get('validatorerror_filenotnull'));
      return false;
    }
    return true;
  }

  /**
   * Vérifie si le validateur est prioritaire
   *
   * @return boolean
   */
  public function isPrior()
  {
    return true;
  }

}

