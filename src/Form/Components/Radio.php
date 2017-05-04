<?php

/**
 * Jin Framework
 * Diatem
 */

namespace Jin2\Form\Components;

/**
 * Composant <input type="radio">
 */
class Radio extends AbstractComponent
{

  /**
   * @var array   Valeurs des cases.
   */
  private $values = array();

  /**
   * Implémente la fonction AbstractComponent::getType()
   *
   * @return string
   */
  public static function getType()
  {
    return 'radio';
  }

  /**
   * Rendu du composant
   *
   * @return type
   */
  public function render()
  {
    $itemHtml = static::getAssetContent('radioitem');

    $addContent = '';
    foreach ($this->values as $v) {
      $ac = $itemHtml;
      $ac = str_replace('%name%', $this->getName().'', $ac);
      $ac = str_replace('%item_label%', $v['label'], $ac);
      $ac = str_replace('%item_value%', $v['value'], $ac);
      $ac = str_replace('%uid%', uniqid(), $ac);
      $selected = '';

      $val = $this->getValue();
      if ($val == $v['value']) {
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
   * Définit l'ensemble des données des cases à cocher
   *
   * @param array $values  Tableau associatif de la forme array(array('label'=>'','value'=>''),...)
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

  /**
   * Définit une datasource permettant de définir des valeurs au radio
   *
   * @param type $queryResult
   * @param type $colNameForLabel
   * @param type $colNameForValue
   */
  public function setDataSource($queryResult, $colNameForLabel, $colNameForValue)
  {
    foreach ($queryResult as $v) {
      $this->values[] = array('value' => $v[$colNameForValue], 'label' => $v[$colNameForLabel]);
    }
  }

}
