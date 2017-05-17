<?php

/**
 * Jin Framework
 * Diatem
 */

namespace Jin2\Form\Components;

/**
 * Composant <input type="checkbox">
 */
class Combo extends AbstractComponent
{

  /**
   * @var array   Valeurs des cases.
   */
  protected $values = array();

  /**
   * @var array  Valeurs a présélectionner. (Tableau de la forme array('value',...))
   */
  protected $checkedValues = array();

  /**
   * Implémente la fonction AbstractComponent::getType()
   *
   * @return string
   */
  public static function getType()
  {
    return 'combo';
  }

  /**
   * Rendu du composant
   *
   * @return type
   */
  public function render()
  {
    $itemHtml = static::getAssetContent('comboitem');

    $addContent = '';
    foreach ($this->values as $v) {
      $ac = $itemHtml;
      $ac = str_replace('%name%', $this->getName().'[]', $ac);
      $ac = str_replace('%item_label%', $v['label'], $ac);
      $ac = str_replace('%item_value%', $v['value'], $ac);
      $ac = str_replace('%uid%', uniqid(), $ac);
      $selected = '';

      $val = $this->getValue();
      if (is_array($val)) {
        if (is_numeric(array_search($v['value'], $val))) {
          $selected = 'checked="checked" ';
        }
      } else {
        if ($val == $v['value']) {
          $selected = 'checked="checked" ';
        }
      }

      if (is_numeric(array_search($v['value'], $this->checkedValues))) {
        $selected = 'checked="checked" ';
      }

      $ac = str_replace('%item_selected%', $selected, $ac);
      $addContent .= $ac;
    }
    $addContent = parent::replaceMagicFields($addContent);

    $html = parent::render();
    $html = str_replace('%items%', $addContent, $html);
    return $html;
  }

  /**
   * Ajoute une case à cocher
   *
   * @param string $value Valeur du choix
   * @param string $label (optional) Label affiché (Par défaut la valeur)
   */
  public function addValue($value, $label = null)
  {
    if (!$label) {
      $label = $value;
    }
    $this->values[] = array('value' => $value, 'label' => $label);
  }

  /**
   * Définit les value à mettre à checked
   *
   * @param array $valuesToChecked  Tableau de values array(1,'toto'...)
   */
  public function forceCheckedValues($valuesToChecked)
  {
    $this->checkedValues = $valuesToChecked;
  }

  /**
   * Définit l'ensemble des données des cases à cocher
   *
   * @param  array $values  Tableau associatif de la forme array(array('label'=>'','value'=>''),...)
   * @throws \Exception
   */
  public function setValues($values)
  {
    $vc = count($values);
    for ($i = 0; $i < $vc; $i++) {
      if (!isset($values[$i]['value']) || !isset($values[$i]['label'])) {
        throw new \Exception('Tableau de données non correct. Les clés value et label doivent être définis pour chaque ligne du tableau de données');
      }
    }
    $this->values = $values;
  }

}
