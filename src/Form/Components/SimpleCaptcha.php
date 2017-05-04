<?php

/**
 * Jin Framework
 * Diatem
 */

namespace Jin2\Form\Components;

use Jin2\Language\Translation;
use \Securimage;

/**
 * Composant captcha
 */
class SimpleCaptcha extends AbstractComponent
{

  /**
   * Implémente la fonction AbstractComponent::getType()
   *
   * @return string
   */
  public static function getType()
  {
    return 'simplecaptcha';
  }

  /**
   * Retourne l'url de Securimage
   *
   * @return stirng
   */
  public function securimageUrl()
  {
    $reflector = new \ReflectionClass('Securimage');
    $securimageUrl = dirname($reflector->getFileName());
    $securimageUrl = preg_replace('#^.*(\\'. DIRECTORY_SEPARATOR .'vendor\\'. DIRECTORY_SEPARATOR .'.+)$#', '$1', $securimageUrl);
    $securimageUrl = str_replace(DIRECTORY_SEPARATOR, '/', $securimageUrl);
    return $securimageUrl . '/securimage_show.php';
  }

  /**
   * Rendu du composant
   *
   * @return type
   */
  public function render()
  {
    $html = parent::render();
    $html = str_replace('%securimagefile%', $this->securimageUrl(), $html);
    $html = str_replace('%txtchangecaptcha%', Translation::get('simplecaptcha_change'), $html);
    return $html;
  }

}
