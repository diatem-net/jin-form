<?php

/**
* Jin Framework
* Diatem
*/

namespace Jin2\Form\Components;

/**
 * Composant <select multiple>
 */
class SelectMultiple extends AbstractComponent
{

  /**
   * @var array
   */
  protected $values;

  /**
   * @var array  Valeurs sélectionnées. (Tableau de la forme array('value',...))
   */
  protected $selectedValues = array();

  /**
   * @var array Attributs ajoutés sur select
   */
  protected $selectattributes = array();

  /**
   * Implémente la fonction AbstractComponent::getType()
   *
   * @return string
   */
  public static function getType()
  {
    return 'selectmultiple';
  }

  /**
  * Rendu du composant
  * @return string
  */
  public function render()
  {
    $optionHtml = static::getAssetContent('option');

    $addContent = '';
    foreach ($this->values as $k => $option) {
      $ac = $optionHtml;
      $attributes = '';
      if (count($option['attributes']) > 0) {
        foreach ($option['attributes'] as $att_k => $att_v) {
          $attributes .= ' '.$att_k.'="'.$att_v.'"';
        }
      }
      $ac = str_replace('%value%', $option['value'], $ac);
      $ac = str_replace('%attributes%', $attributes, $ac);
      $ac = str_replace('%label%', $k, $ac);

      $selected = false;
      if (array_search($option['value'], $this->selectedValues) !== false) {
        $selected = true;
      }

      if ($selected) {
        $ac = str_replace('%selected%', 'selected="selected"', $ac);
      } else {
        $ac = str_replace('%selected%', '', $ac);
      }

      $addContent .= $ac;
    }

    $html = parent::render();
    $strAttributes = '';
    foreach ($this->selectattributes as $key => $value) {
      $strAttributes .= ' ' . $key . '="' . $value . '"';
    }
    $html = str_replace('%selectattributes%', $strAttributes, $html);
    $html = str_replace('%items%', $addContent, $html);

    return $html;
  }

  /**
   * Ajoute un nouvel attribut sur le select
   *
   * @param  string $attributeName   Nom de l'attribut
   * @param  string $attributeValue  Value de l'attribut
   * @return boolean                 Retourne FALSE si cet atribut était déjà ajouté
   */
  public function addSelectAttribute($attributeName, $attributeValue)
  {
    if (!array_key_exists($attributeName, $this->selectattributes)) {
      $this->selectattributes[$attributeName] = $attributeValue;
      return true;
    }
    return false;
  }

  /**
   * Supprime un attribut ajouté sur le select
   *
   * @param string $attributeName Nom de l'attribut
   * @return boolean              Retourne FALSE si cet attribut n'était pas ajouté
   */
  public function removeSelectAttribute($attributeName)
  {
    if (isset($this->selectattributes[$attributeName])) {
      unset($this->selectattributes[$attributeName]);
      return true;
    }
    return false;
  }

  /**
   * Retourne un tableau des attributs ajoutés
   *
   * @return array
   */
  public function getSelectAttributes()
  {
    return $this->selectattributes;
  }

  /**
   * Ajoute un choix dans la liste
   *
   * @param string $value      Valeur du choix
   * @param string $label      Label affiché
   * @param array  $attributes Attributs supplémentaires pour l'option
   */
  public function addValue($value, $label, $attributes = array())
  {
    $this->values[$label] = array(
      'value'      => $value,
      'attributes' => $attributes
    );
  }

  /**
   * Définit les valeurs du SelectMultiple
   *
   * @param array $values Tableau de valeurs (clé/valeur) ex. array('label'=>'val','label','val');
   */
  public function setValues($values)
  {
    if (count($values) > 0) {
      foreach ($values as $label => $value) {
        $this->addValue($value, $label);
      }
    }
  }

  /**
   * Définit les value à mettre à selected
   *
   * @param array $valuesToSelect  Tableau de values array(1,'toto'...)
   */
  public function forceSelectedValues($valuesToSelect)
  {
    $this->selectedValues = $valuesToSelect;
  }

}
