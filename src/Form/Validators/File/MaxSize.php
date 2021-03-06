<?php

/**
 * Jin Framework
 * Diatem
 */

namespace Jin2\Form\Validators\File;

use Jin2\Form\Validators\AbstractValidator;
use Jin2\Language\Translation;

/**
 * Validateur : teste si le fichier a une taille (en octets) maximum
 */
class MaxSize extends AbstractValidator
{

  /**
   * Implémente la fonction AbstractValidator::getType()
   *
   * @return string
   */
  public static function getType()
  {
    return 'maxsize';
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

    if (isset($value['size'])) {
      if (parent::getArgValue('maxsize') <= 0) {
        return true;
      }

      if ($value['size'] > parent::getArgValue('maxsize')) {
        $eMsg = Translation::get('validatorerror_maxsize');

        $o = parent::getArgValue('maxsize');
        $ko = parent::getArgValue('maxsize') / 1024;
        $mo = parent::getArgValue('maxsize') / 1024 / 1024;

        $msize = $o .' octets';
        if ($mo > 1) {
          $msize = number_format($mo, 2).' Mo';
        } else if($ko > 1) {
          $msize = number_format($ko, 2).' Ko';
        }

        $eMsg = str_replace('%maxsize%', $msize, $eMsg);
        parent::addError($eMsg);

        return false;
      }
    }

    return true;
  }

}
