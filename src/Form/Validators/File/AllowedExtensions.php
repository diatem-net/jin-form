<?php

/**
 * Jin Framework
 * Diatem
 */

namespace Jin2\Form\Validators\File;

use Jin2\Form\Validators\AbstractValidator;
use Jin2\Language\Translation;

/**
 * Validateur : teste l'extension d'un fichier
 */
class AllowedExtensions extends AbstractValidator
{

  /**
   * Implémente la fonction AbstractValidator::getType()
   *
   * @return string
   */
  public static function getType()
  {
    return 'extensionList';
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

    if (empty(parent::getArgValue('extensionList'))) {
      return true;
    }
    $currentExt = ListTools::last($value['name'], '.');
    if ($currentExt && !ListTools::containsNoCase(parent::getArgValue('extensionList'), $currentExt)) {
      $eMsg = Translation::get('validatorerror_extensionList');
      $eMsg = str_replace('%extensionList%', parent::getArgValue('extensionList'), $eMsg);
      parent::addError($eMsg);
      return false;
    }

    return true;
  }

}
