<?php
namespace Modules\Blog\Http\Shortcodes;

class ItalicShortcode {

  public function register($shortcode, $content, $compiler, $name, $viewData='')
  {
    return sprintf('<i class="%s">%s</i>', $shortcode->class, $content);
  }
  
}