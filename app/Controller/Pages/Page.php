<?php

namespace App\Controller\Pages;

use \App\Utils\View;

class Page
{
  /**
    * Método resposanvel por retornar o topo da página
    * @return string
  */
  private static function getHeader()
  {
    return View::render('pages/header');      
  }

  /**
    * Método resposanvel por retornar o rodapé da página
    * @return string
  */
  private static function getFooter() 
  {
    return View::render('pages/footer');
  }

  /**
    * Método resposanvel por retornar o conteúdo {view} da nossa pagina genérica
    * @return string
  */
  public static function getPage($title, $content) 
  {            
    return View::render('pages/page', [
        'title' => $title,
        'header' => self::getHeader(),                
        'content' => $content,
        'footer' => self::getFooter()
    ]);
  }
}