<?php

/**
 * Jin Framework
 * Diatem
 */

namespace Jin2\Form\Components;

/**
 * Composant wysiwyg
 */
class Wysiwyg extends AbstractComponent
{

  public static $assetsLoaded = false;

  /**
   * Implémente la fonction AbstractComponent::getType()
   *
   * @return string
   */
  public static function getType()
  {
    return 'wysiwyg';
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
      $html = str_replace('%tinymcefile%', $this->tinyMCEfile(), $html);
    }
    $html .= parent::render();
    return $html;
  }

  /**
   * Retourne l'url de TinyMCE
   *
   * @return string
   */
  public function tinyMCEfile()
  {
    $reflector = new \ReflectionClass('Jin2\Form\Components\Wysiwyg');
    $tinyMCEUrl = dirname($reflector->getFileName());
    $tinyMCEUrl .= '..'
      . DIRECTORY_SEPARATOR .'..'
      . DIRECTORY_SEPARATOR .'..'
      . DIRECTORY_SEPARATOR .'..'
      . DIRECTORY_SEPARATOR .'assets'
      . DIRECTORY_SEPARATOR. 'wysiwyg';
    $tinyMCEUrl = preg_replace('#^.*(\\'. DIRECTORY_SEPARATOR .'vendor\\'. DIRECTORY_SEPARATOR .'.+)$#', '$1', $tinyMCEUrl);
    $tinyMCEUrl = str_replace(DIRECTORY_SEPARATOR, '/', $tinyMCEUrl);
    return $tinyMCEUrl . '/tinymce/tinymce.min.js';
  }

  /**
   * Charge les assets
   *
   * @return string
   */
  private static function loadAssets()
  {
    self::$assetsLoaded = true;
    return static::getAssetContent('js');
  }

}
