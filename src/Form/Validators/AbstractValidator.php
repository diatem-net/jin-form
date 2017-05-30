<?php

/**
 * Jin Framework
 * Diatem
 */

namespace Jin2\Form\Validators;

use Jin2\Language\Translation;

/**
 * Classe parent de tout validateur
 */
abstract class AbstractValidator
{

  /**
   *
   * @var array  Liste des erreurs
   */
  protected $errors = array();

  /**
   *
   * @var array  Paramètres
   */
  protected $args = array();

  /**
   * Constructeur
   *
   * @param  array $args          Tableau d'arguments array('arg1'=>'val1','arg2'=>'val2)
   * @param  array $requiredArgs  Tableau des arguments requis array('arg1','arg2')
   * @throws \Exception
   */
  public function __construct($args, $requiredArgs = array())
  {
    foreach ($requiredArgs as $a) {
      if (!isset($args[$a])) {
        throw new \Exception('Argument manquant pour le validateur : '.$a);
      }
    }
    $this->args = $args;
    $root =  __DIR__
      . DIRECTORY_SEPARATOR .'..'
      . DIRECTORY_SEPARATOR .'..'
      . DIRECTORY_SEPARATOR .'..'
      . DIRECTORY_SEPARATOR .'languages';
    Translation::addStorage($root, 0);
    Translation::loadFile('formvalidators');
  }

  /**
   * Retourne le type de validateur
   *
   * @return string
   */
  abstract public static function getType();

  /**
   * vérifie si le champ passe le validateur
   *
   * @return boolean
   */
  abstract public function isValid($valeur);

  /**
   * Vérifie si le validateur est prioritaire
   *
   * @return boolean
   */
  public function isPrior()
  {
    return false;
  }

  /**
   * Retourne la valeur d'un argument
   * @param string $argName	Nom de l'argument
   * @return string
   */
  protected function getArgValue($argName)
  {
    return $this->args[$argName];
  }

  /**
   * Ajoute une erreur
   * @param string $errorText	Texte de l'erreur
   */
  protected function addError($errorText)
  {
    $this->errors[] = $errorText;
  }

  /**
   * Réinitialise les erreurs
   */
  protected function resetErrors()
  {
    $this->errors = array();
  }

  /**
   * Retourne un tableau des erreurs rencontrées
   * @return array	Tableau d'erreurs
   */
  public function getErrors()
  {
    return $this->errors;
  }

}
