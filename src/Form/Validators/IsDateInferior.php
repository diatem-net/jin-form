<?php

/**
 * Jin Framework
 * Diatem
 */

namespace Jin2\Form\Validators;

use Jin2\Language\Translation;

/**
 * Validateur : teste si une date est intéfieure à une date de référence
 */
class IsDateInferior extends AbstractValidator
{

  /**
   * Constructeur
   *
   * @param  array $args          Tableau d'arguments array('arg1'=>'val1','arg2'=>'val2)
   * @throws \Exception
   */
  public function __construct($args)
  {
    return parent::__construct($args, array('format', 'date'));
  }

  /**
   * Implémente la fonction AbstractValidator::getType()
   *
   * @return string
   */
  public static function getType()
  {
    return 'isdateinferior';
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
    $dValidator = new Isdate(array('format' => $format));
    if (!$dValidator->isValid($value)) {
      $eMsg = Translation::get('validatorerror_isdateinferior');
      $eMsg = str_replace('%date%', parent::getArgValue('date'), $eMsg);
      parent::addError($eMsg);
      return false;
    } else {
      $date      = \DateTime::createFromFormat($format, $value)->format('U');
      $reference = \DateTime::createFromFormat($format, $this->getArgValue('date'))->format('U');
      if ($date >= $reference) {
        $eMsg = Translation::get('validatorerror_isdateinferior');
        $eMsg = str_replace('%date%', parent::getArgValue('date'), $eMsg);
        parent::addError($eMsg);
        return false;
      }
    }

    return true;
  }

}
