<?php

/**
* Jin Framework
* Diatem
*/

namespace Jin2\Form;

use Jin2\Form\Components\AttachementFile;

/**
 * Gestion souple de formulaires
 */
class DForm
{

  /**
   * @var array   Champs contenus dans le formulaire
   */
  protected $fields = array();

  /**
   * @var array   Champs pièce jointe contenus dans le formulaire
   */
  protected $attachementFields = array();

  /**
   * @var string  Classe erreur appliquée aux composants ayant généré une erreur de validation
   */
  protected $errorClassName = 'error';

  /**
   * Ajout d'un champ
   *
   * @param  mixed        $field             Identifiant du champ, ou composant de type FormComponent
   * @param  string       $dataSourceColumn  Si le DForm est lié à une DataSource, précise dans quel champ rechercher les données
   * @param  string       $validateurs       Structure JSON définissant les validateurs à utiliser. Ex : {"NotNull":"","NumRange":{"minValue":0,"maxValue":100}}
   * @return boolean                         Succès ou echec
   * @throws \Exception
   */
  public function addField($field, $dataSourceColumn = null, $validateurs = null)
  {
    // Pour gérer les composants
    if (is_string($field)) {
      $fieldName = $field;
    } else {
      $fieldName = $field->getName();
    }

    // Teste si le champ n'existe pas déjà
    if (array_key_exists($fieldName, $this->fields)) {
      throw new \Exception('Impossible d\'ajouter le champ' . $fieldName . ' : celui ci est déjà défini dans le DForm');
      return false;
    }

    // Ajout des validateurs
    $validat = array();
    if ($validateurs) {
      $jsonV = json_decode($validateurs, true);
      if (!is_null($validateurs) && is_null($jsonV)) {
        throw new \Exception('Le format JSon fourni pour les validateurs n\'est pas conforme : ' . json_last_error());
        return false;
      }
      foreach ($jsonV as $key => $value) {
        $className = 'Jin2\Form\Validators\\'. $key;
        $c = new $className($value);
        $validat[] = $c;
      }
    }

    // Finalisation
    $newline = array(
      'dataSourceColumn' => $dataSourceColumn,
      'validateurs' => $validat,
      'errors' => array(),
      'value' => null
    );
    if (!is_string($field)) {
      $newline['component'] = $field;
    }
    $this->fields[$fieldName] = $newline;
    return true;
  }

  /**
   * Ajout d'un champ de type AttachementFile
   *
   * @param  AttachementFile $fileComponent  Composant AttachementFile
   * @param  string          $uploadFolder   Dossier de destination (chemin absolu) ou nom forcé de fichier
   * @param  string          $validateurs    Structure JSON définissant les validateurs de type FILE à utiliser. Ex : {"NotNull":"","MinSize":{"minsize":2000}}
   * @return boolean
   * @throws \Exception
  */
  public function addAttachementField(AttachementFile $fileComponent, $uploadFolder, $validateurs = null)
  {
    $fieldName = $fileComponent->getName();

    // Teste si le champ n'existe pas déjà
    if (array_key_exists($fieldName, $this->attachementFields)) {
      throw new \Exception('Impossible d\'ajouter la pièce jointe ' . $fieldName . ' : celle ci est déjà définie dans le DForm');
      return false;
    }

    $validat = array();
    if ($validateurs) {
      $jsonV = json_decode($validateurs, true);
      if (!is_null($validateurs) && is_null($jsonV)) {
        throw new \Exception('Le format JSon fourni pour les validateurs n\'est pas conforme : ' . json_last_error());
        return false;
      }
      foreach ($jsonV as $key => $value) {
        $className = 'Jin2\Form\Validators\File\\'. $key;
        $c = new $className($value);
        $validat[] = $c;
      }
    }

    // Finalisation
    $newline = array(
      'outputfile' => '',
      'component' => $fileComponent,
      'validateurs' => $validat,
      'errors' => array(),
      'uploadfolder' => $uploadFolder
    );
    $this->attachementFields[$fieldName] = $newline;
    return true;
  }

  /**
   * Supprime les fichiers attachés transférés sur le serveur
   */
  public function deleteAttachementFiles()
  {
    foreach ($this->attachementFields as $fieldName => $v) {
      if ($v['outputfile'] != '' && file_exists($v['outputfile'])) {
        unlink($v['outputfile']);
      }
    }
  }

  /**
   * Permet de tester la validité des valeurs du formulaire
   *
   * @return boolean TRUE si le formulaire est valide
   */
  public function isValid()
  {
    $errors = $this->checkForm(true);
    return count($errors) == 0;
  }

  /**
   * Retourne un tableau associatif array(array('field'=>'','error'=>'')) contenant l'ensemble des erreurs rencontrées sur le formulaire
   *
   * @return array
   */
  public function getAllErrors()
  {
    $errors = array();
    foreach($this->fields AS $k => $f){
      foreach($f['errors'] AS $e){
        $errors[] = array('field' => $k, 'error' => $e);
      }
    }
    foreach($this->attachementFields AS $k => $f){
      foreach($f['errors'] AS $e){
        $errors[] = array('field' => $k, 'error' => $e);
      }
    }
    return $errors;
  }

  /**
   * Teste la validité des données et renvoie un tableau associatif contenant erreurs recontrées array(array('field'=>'','error'=>''))
   *
   * @param  boolean $saveFiles    (defaut : FALSE) Définit si le système doit procéder à l'enregistrement des champs de type FILE
   * @return array
   * @throws \Exception
   */
  protected function checkForm($saveFiles = false)
  {
    $valide = true;
    $allErrors = array();

    // On passe dans les champs de type FIELD
    foreach ($this->fields as $fieldName => $v) {

      // Champs standard
      // On réinitialise les erreurs
      $this->fields[$fieldName]['errors'] = array();

      // On détermine la nouvelle valeur
      if (isset($_POST[trim($fieldName, '[]')])) {
        $this->fields[$fieldName]['value'] = $_POST[trim($fieldName, '[]')];
      } else {
        $this->fields[$fieldName]['value'] = '';
      }

      // Erreurs de niveau 2
      $errors = array();
      // Erreurs de niveau 1
      $priorErrors = array();

      // On passe par tous les validateurs pour checker la valeur
      foreach ($v['validateurs'] as $v) {
        $vv = $v->isValid($this->fields[$fieldName]['value']);
        if (!$vv) {
          $valide = false;
          if ($v->isPrior()) {
            $priorErrors = array_merge($priorErrors, $v->getErrors());
          } else {
            $errors = array_merge($errors, $v->getErrors());
          }
        }
      }

      // On prend en considération les erreurs de niveau 1 ou 2 en fonction
      if (count($priorErrors) > 0) {
        $this->fields[$fieldName]['errors'] = $priorErrors;
      } else {
        $this->fields[$fieldName]['errors'] = $errors;
      }

      foreach($this->fields[$fieldName]['errors'] AS $error){
        $allErrors[] = array('field' => $fieldName, 'error' => $error);
      }
    }

    // On passe dans les pièces-jointe
    foreach ($this->attachementFields as $fieldName => $v) {
      $val = '';
      if (isset($_FILES[$fieldName])) {
        $val = $_FILES[$fieldName];
      }
      // On réinitialise les erreurs
      $this->attachementFields[$fieldName]['errors'] = array();

      // Erreurs de niveau 2
      $errors = array();
      // Erreurs de niveau 1
      $priorErrors = array();

      // On passe par tous les validateurs pour checker la valeur
      foreach ($v['validateurs'] as $v) {
        $vv = $v->isValid($val);
        if (!$vv) {
          $valide = false;
          if ($v->isPrior()) {
            $priorErrors = array_merge($priorErrors, $v->getErrors());
          } else {
            $errors = array_merge($errors, $v->getErrors());
          }
        }
      }

      // On prend en considération les erreurs de niveau 1 ou 2 en fonction
      if (count($priorErrors) > 0) {
        $this->attachementFields[$fieldName]['errors'] = $priorErrors;
      } else {
        $this->attachementFields[$fieldName]['errors'] = $errors;
      }

      foreach($this->attachementFields[$fieldName]['errors'] AS $error){
        $allErrors[] = array('field' => $fieldName, 'error' => $error);
      }
    }

    // Si tout est valide : enregistrer les pièces jointes
    if ($valide && $saveFiles) {
      foreach ($this->attachementFields as $fieldName => $v) {
        if (isset($_FILES[$fieldName]) && !empty($_FILES[$fieldName]['name'])) {
          if (substr($v['uploadfolder'], strlen($v['uploadfolder']) - 1, 1) != '/') {
            // Nom de fichier forcé
            $uploadfile = $v['uploadfolder'];
          } else {
            // On conserve le nom du fichier originel
            $basenameparts = explode('/', $_FILES[$fieldName]['name']);
            $basename = end($basenameparts);
            $uploadfile = $v['uploadfolder'] . $basename;
          }
          $this->attachementFields[$fieldName]['value'] = array(
            'filename' => $_FILES[$fieldName]['name'],
            'path' => $uploadfile
          );
          if (!move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadfile)) {
            throw new \Exception('Erreur de transfert du fichier joint ' . $fieldName);
          } else {
            $this->attachementFields[$fieldName]['outputfile'] = $uploadfile;
          }
        }
      }
    }

    return $allErrors;
  }

  /**
   * Retourne la valeur actuelle d'un champ
   *
   * @param  string $fieldName Identifiant du champ ou du composant
   * @return string
   */
  public function getFieldValue($fieldName)
  {
    if (isset($this->fields[$fieldName])) {
      if (isset($_FILES[$fieldName])) {
        // Cas particulier AttachementFile
        return $this->fields[$fieldName]['value'];
      } else if(!isset($this->fields[$fieldName]['value']) && !empty($_POST)) {
        // Cas particulier checkbox
        return array();
      } else if(isset($this->fields[$fieldName]['value'])) {
        return $this->fields[$fieldName]['value'];
      }
    } else if(isset($this->attachementFields[$fieldName])) {
      return $this->attachementFields[$fieldName]['value'];
    }
    return '';
  }

  /**
   * Retourne l'erreur formattée d'un champ (nécessite l'appel à isValid au préalable)
   *
   * @param  string $fieldName     Identifiant du champ ou du composant
   * @param  string $globalFormat  Template d'affichage autour des erreurs. %texte% est le mot clé permettant de spécifier où positionner les items générés
   * @param  string $itemFormat    Template d'affichage de chaque erreur. %texte% est le mot clé permettant de spécifier ou positionner le texte de l'erreur
   * @return string
  */
  public function getFieldError($fieldName, $globalFormat = '<div class="error">%texte%</div>', $itemFormat = '<span>%texte%</span>')
  {
    if (isset($this->fields[$fieldName])) {
      if (count($this->fields[$fieldName]['errors']) > 0) {
        $toadd = '';
        foreach ($this->fields[$fieldName]['errors'] as $err) {
          $toadd .= preg_replace('/%texte%/', $err, $itemFormat);
        }
        return preg_replace('/%texte%/', $toadd, $globalFormat);
      }
    } else if (isset($this->attachementFields[$fieldName])) {
      if (count($this->attachementFields[$fieldName]['errors']) > 0) {
        $toadd = '';
        foreach ($this->attachementFields[$fieldName]['errors'] as $err) {
          $toadd .= preg_replace('/%texte%/', $err, $itemFormat);
        }
        return preg_replace('/%texte%/', $toadd, $globalFormat);
      }
    }
    return '';
  }

  /**
   * Retourne si un champ est en erreur ou non (nécessite l'appel à isValid au préalable)
   *
   * @param  string $fieldName  Identifiant du champ
   * @return boolean|null       TRUE si la validation du champ a rencontré une erreur
   */
  public function isFieldError($fieldName)
  {
    if (isset($this->fields[$fieldName])) {
      return count($this->fields[$fieldName]['errors']) > 0;
    } else if (isset($this->attachementFields[$fieldName])) {
      return count($this->attachementFields[$fieldName]['errors']) > 0;
    }
    return null;
  }

  /**
   * Retourne les erreurs d'un champ sous forme de tableau
   *
   * @param  string $fieldName  Identifiant du champ ou du composant
   * @return array
   */
  public function getFieldErrorInArray($fieldName)
  {
    if (isset($this->fields[$fieldName])) {
      return $this->fields[$fieldName]['errors'];
    } else if (isset($this->attachementFields[$fieldName])) {
      return $this->attachementFields[$fieldName]['errors'];
    }
    return array();
  }

  /**
   * Force une erreur dans un composant du formulaire
   *
   * @param  string $fieldName  Nom du champ
   * @param  string $errorTxt   Erreur à forcer
   * @throws \Exception
   */
  public function setFieldError($fieldName, $errorTxt)
  {
    if (isset($this->fields[$fieldName])) {
      $this->fields[$fieldName]['errors'][] = $errorTxt;
    } else if (isset($this->attachementFields[$fieldName])) {
      $this->attachementFields[$fieldName]['errors'][] = $errorTxt;
    } else {
      throw new \Exception('Le champ '.$fieldName.' n\'existe pas');
    }
  }

  /**
   * Force la valeur d'un champ
   *
   * @param string $fieldName  Identifiant du champ ou du composant
   * @param mixed  $value
   */
  public function forceFieldValue($fieldName, $value)
  {
    $this->fields[$fieldName]['value'] = $value;
  }

  /**
   * Force la valeur d'une pièce jointe
   *
   * @param string $fieldName  Identifiant du champ ou du composant
   * @param mixed $value
   */
  public function forceAttachementFieldValue($fieldName, $value)
  {
    $this->attachementFields[$fieldName]['value'] = $value;
  }

  /**
   * Permet d'effectuer le rendu d'un champ de type component.
   *
   * @param  string $fieldName     Identifiant du champ ou du composant
   * @param  string $globalFormat  Template d'affichage autour des erreurs. %texte% est le mot clé permettant de spécifier où positionner les items générés
   * @param  string $itemFormat    Template d'affichage de chaque erreur. %texte% est le mot clé permettant de spécifier ou positionner le texte de l'erreur
   * @return type
   * @throws \Exception
   */
  public function renderComponentField($fieldName, $globalErrorFormat = '<div class="error">%texte%</div>', $itemErrorFormat = '<span>%texte%</span>')
  {
    if (isset($this->fields[$fieldName]['component'])) {
      // Champ de type field
      $this->fields[$fieldName]['component']->setValue($this->fields[$fieldName]['value']);
      $this->fields[$fieldName]['component']->setError($this->getFieldError($fieldName, $globalErrorFormat, $itemErrorFormat));
      if ($this->isFieldError($fieldName)) {
        $this->fields[$fieldName]['component']->addClass($this->errorClassName);
      } else {
        $this->fields[$fieldName]['component']->removeClass($this->errorClassName);
      }
      return $this->fields[$fieldName]['component']->render();
    } else if (isset($this->attachementFields[$fieldName]['component'])) {
      // Champ de type attachementField
      $this->attachementFields[$fieldName]['component']->setError($this->getFieldError($fieldName, $globalErrorFormat, $itemErrorFormat));
      if ($this->isFieldError($fieldName)) {
        $this->attachementFields[$fieldName]['component']->addClass($this->errorClassName);
      } else {
        $this->attachementFields[$fieldName]['component']->removeClass($this->errorClassName);
      }
      return $this->attachementFields[$fieldName]['component']->render();
    } else {
      throw new \Exception('Le champ ' . $fieldName . ' n\'existe pas ou n\'est pas lié à un composant');
      return;
    }
  }

  /**
   * Retourne les données issues du formulaire sous forme de tableau
   *
   * @param  boolean $withAttachementsFields (optional) Définit si on souhaite voir apparaitre les données sur les pièces jointes (TRUE par défaut)
   * @param  boolean $withStandardFields   (optional) Définit si on souhaite voir apparaître les données des champs standard. (TRUE par défaut)
   * @return type
   */
  public function getDataInArray($withStandardFields = true, $withAttachementsFields = true)
  {
    $data = array();
    if ($withStandardFields) {
      foreach ($this->fields as $fieldName => $v) {
        $data[$fieldName] = $v['value'];
      }
    }
    if ($withAttachementsFields) {
      foreach ($this->attachementFields as $fieldName => $v) {
        $data[$fieldName] = $v['outputfile'];
      }
    }
    return $data;
  }

  /**
   * Redéfinit la classe CSS appliquée aux composants pour lesquels une erreur de validation a été relevée
   *
   * @param string $className Nom de la classe à appliquer
   */
  public function setErrorClassName($className)
  {
    $this->errorClassName = $className;
  }

  /**
   * Retourne la classe CSS actuellement appliquable aux composants pour lesquels une erreur de validation a été relevée
   *
   * @return type
   */
  public function getErrorClassName()
  {
    return $this->errorClassName;
  }

  /**
   * Injecte les données d'une datasource dans le formulaire (utilise le paramètre dataSourceColumn)
   *
   * @param type $queryResult
   */
  public function setDataSource($queryResult)
  {
    $values = array();
    $currentValue = current($queryResult);
    foreach ($this->fields as $fieldName => $v) {
      $values[$fieldName] = $currentValue[0][$v['dataSourceColumn']] ;
    }
    $this->populate($values);
  }

  /**
   * Injecte un tableau de données dans le formulaire
   *
   * @param  array $values  Valeurs à injecter
   * @return boolean        Succès/Echec
   */
  public function populate($values)
  {
    if (!is_array($values)) {
      return false;
    }
    foreach ($this->fields as $fieldName => $v) {
      if (isset($values[$fieldName])) {
        $this->fields[$fieldName]['value'] = $values[$fieldName];
      }
    }
    foreach ($this->attachementFields as $fieldName => $v) {
      if (isset($values[$fieldName])) {
        $this->attachementFields[$fieldName]['value'] = $values[$fieldName];
      }
    }
    return true;
  }

  /**
  * Supprime toutes les données du formulaire
   */
  public function clear()
  {
    $data = array();
    foreach ($this->fields as $fieldName => $v) {
      $data[$fieldName] = null;
    }
    foreach ($this->attachementFields as $fieldName => $v) {
      $data[$fieldName] = null;
    }
  }

  /**
   * Supprime toutes les erreurs
   */
  public function clearAllErrors()
  {
    foreach ($this->fields as $fieldName => $v) {
      $this->fields[$fieldName]['errors'] = array();
    }
    foreach ($this->attachementFields as $fieldName => $v) {
      $this->fields[$fieldName]['errors'] = array();
    }
  }

  /**
   * Retourne les données du formulaire à des fins de debug
   */
  public function getFieldsData()
  {
    return $this->fields;
  }

  /**
   * Retourne les données du formulaire à des fins de debug
   */
  public function getAttachementFieldsData()
  {
    return $this->attachementFields;
  }

}


