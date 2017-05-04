<?php

/**
 * Jin Framework
 * Diatem
 */

namespace Jin2\Form\Validators;

use Jin2\Language\Translation;
use \Securimage;

/**
 * Validateur : teste si une valeur issue d'un composant SimpleCaptcha est valide
 */
class SimpleCaptchaValidator extends AbstractValidator
{

  /**
   * Implémente la fonction AbstractValidator::getType()
   *
   * @return string
   */
  public static function getType()
  {
    return 'simplecaptcha';
  }

  /**
   * Teste la validité
   *
   * @param  mixed $value Valeur à tester
   * @return boolean
   */
  public function isValid($value)
  {
    $securimage = new \Securimage(array('session_name' => null));

    if (empty($value)) {
      parent::addError(Translation::get('validatorerror_simplecaptcha_required'));
      return false;
    }

    if ($securimage->check($value) == false) {
      parent::addError(Translation::get('validatorerror_simplecaptcha_check'));
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
