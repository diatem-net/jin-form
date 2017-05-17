<?php

/**
 * Jin Framework
 * Diatem
 */

namespace Jin2\Form\Validators;

use Jin2\Language\Translation;

/**
 * Validateur : teste si le nombre de caractères du valeur entre dans une plage autorisée
 */
class Length extends AbstractValidator
{

  /**
   * Constructeur
   *
   * @param type $args    Tableau d'arguments. (min : nombre max de caractères (-1 : pas de minimum) max : nombre max de caractères (-1 : pas de maximum)
   */
  public function __construct($args)
  {
    parent::__construct($args, array('min', 'max'));
  }

  /**
   * Implémente la fonction AbstractValidator::getType()
   *
   * @return string
   */
  public static function getType()
  {
    return 'length';
  }

  /**
   * Teste la validité
   *
   * @param mixed $valeur Valeur à tester
   * @return boolean
   */
  public function isValid($valeur)
  {
    parent::resetErrors();

    $min = $this->getArgValue('min');
    $max = $this->getArgValue('max');

    if ($min == $max) {
      if (strlen($valeur) != $min) {
        parent::addError($this->prepareError('valisatorerror_length_exact'));
        return false;
      }
    } else if ($min == -1) {
      if (strlen($valeur) > $max) {
        parent::addError($this->prepareError('valisatorerror_length_max'));
        return false;
      }
    } else if ($max == -1) {
      if (strlen($valeur) < $min) {
        parent::addError($this->prepareError('valisatorerror_length_min'));
        return false;
      }
    } else {
      if (strlen($valeur) < $min || strlen($valeur) > $max) {
        parent::addError($this->prepareError('valisatorerror_length_range'));
        return false;
      }
    }

    return true;
  }

  /**
   * Formatte un texte d'erreur
   *
   * @param string $code  Code erreur (cf. fichier langue formvalidators.ini)
   * @return string
   */
  protected function prepareError($code)
  {
    $min = $this->getArgValue('min');
    $max = $this->getArgValue('max');
    $error = Translation::get($code);
    $error = str_replace('%min%', $min, $error);
    $error = str_replace('%max%', $max, $error);
    return $error;
  }

}
