<?php

/**
 * Jin Framework
 * Diatem
 */

namespace Jin2\Form\Validators;

use Jin2\Language\Translation;

/**
 * Validateur : teste si une valeur est supérieure ou égale à une valeur donnée
 */
class Operator extends AbstractValidator
{

  /**
   * Constructeur
   *
   * @param type $args Tableau d'arguments (operator -> opérateur de test, value -> valeur à comparer)
   */
  public function __construct($args) {
    parent::__construct($args, array('operator', 'value'));
  }

  /**
   * Implémente la fonction AbstractValidator::getType()
   *
   * @return string
   */
  public static function getType()
  {
    return 'operator';
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

    $vValue    = $this->getArgValue('value');
    $vOperator = $this->getArgValue('operator');
    $eMsg = '';

    if (!empty($value)) {
      if ($vOperator == '==' && !($value == $vValue)) {
        $error = true;
        $eMsg = Translation::get('validatorerror_operator_equal');
        $eMsg = str_replace('%value%', $vValue, $eMsg);
      } elseif ($vOperator == '!=' && !($value != $vValue)) {
        $error = true;
        $eMsg = Translation::get('validatorerror_operator_notequal');
        $eMsg = str_replace('%value%', $vValue, $eMsg);
      } elseif ($vOperator == '<' && !($value < $vValue)) {
        $error = true;
        $eMsg = Translation::get('validatorerror_operator_inferior');
        $eMsg = str_replace('%value%', $vValue, $eMsg);
      } elseif ($vOperator == '<=' && !($value <= $vValue)) {
        $error = true;
        $eMsg = Translation::get('validatorerror_operator_inferiororequal');
        $eMsg = str_replace('%value%', $vValue, $eMsg);
      } elseif ($vOperator == '>' && !($value > $vValue)) {
        $error = true;
        $eMsg = Translation::get('validatorerror_operator_superior');
        $eMsg = str_replace('%value%', $vValue, $eMsg);
      } elseif ($vOperator == '>=' && !($value >= $vValue)) {
        $error = true;
        $eMsg = Translation::get('validatorerror_operator_superiororequal');
        $eMsg = str_replace('%value%', $vValue, $eMsg);
      }

      if ($error) {
        parent::addError($eMsg);
        return false;
      }
    }

    return true;
  }

}
