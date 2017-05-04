<?php

/**
 * Jin Framework
 * Diatem
 */

namespace Jin2\Form\Validators;

use Jin2\Language\Translation;

/**
 * Validateur : teste si les caractères d'une valeurs sont parmis une liste de caractères autorisés
 */
class CharsInList extends AbstractValidator
{

  /**
   * Constructeur
   *
   * @param type $args    Tableau d'arguments. chars (caractères autorisés, séparés par des virgules)
   */
  public function __construct($args)
  {
    parent::__construct($args, array('chars'));
  }

  /**
   * Implémente la fonction AbstractValidator::getType()
   *
   * @return string
   */
  public static function getType()
  {
    return 'charsinlist';
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
    if (empty($valeur) || parent::getArgValue('chars') == '') {
      return true;
    }

    $chars = explode(',', parent::getArgValue('chars'));
    $stringChars = str_split($valeur);
    foreach ($stringChars AS $char) {
      if (array_search($char, $chars) === false || array_search($char, $chars) === null) {
        $eMsg = Translation::get('validatorerror_charsinlist');
        $eMsg = str_replace('%chars%', parent::getArgValue('chars'), $eMsg);
        parent::addError($eMsg);
        return false;
      }
    }
    return true;
  }

}
