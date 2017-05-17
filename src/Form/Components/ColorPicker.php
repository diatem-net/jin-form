<?php

/**
 * Jin Framework
 * Diatem
 */

namespace Jin2\Form\Components;

/**
 * Composant ColorPicker (Choix couleur)
 */
class ColorPicker extends AbstractComponent
{

  public static $assetsLoaded = false;

  /**
   * Implémente la fonction AbstractComponent::getType()
   *
   * @return string
   */
  public static function getType()
  {
    return 'colorpicker';
  }

  /**
   * Rendu du composant
   *
   * @return type
   */
  public function render()
  {
    $html = '';
    if (!self::$assetsLoaded) {
      $html = static::loadAssets();
    }
    $html .= parent::render();
    return $html;
  }

  /**
   * Charge les assets
   *
   * @return string
   */
  protected static function loadAssets()
  {
    self::$assetsLoaded = true;
    return static::getAssetContent('js') . static::getAssetContent('css');
  }

}