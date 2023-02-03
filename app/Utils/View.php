<?php

namespace App\Utils;

class View
{
    /**
     * Variaveis padrões da View
     * @var array
     */
    private static $vars = [];

    /**
     * @param array $vars
     */
    public static function init($vars = [])
    {
        self::$vars = $vars;
    }

    /**
     * Método responsavel por retornar o conteúdo de uma view
     * @param string $view
     * @return string
    */
    private static function getContentView($view)     
    {
        $file = __DIR__.'/../../resources/view/' . $view. '.html';

        return file_exists($file) ? file_get_contents($file) : '';
    }

    /**
     * Método responsável por retornar o conteúdo renderizado de uma view
     * @param string $view
     * @param array $vars (string/numericos)
     * @return string
    */
    public static function render($view, $vars = [])
    {        
        //CONTEÚDO DA VIEW
        $contentView = self::getContentView($view);

        //MERGE DE VARIAVEIS DO VIEW
        $vars = array_merge(self::$vars, $vars);

        //CHAVES DO ARRAY DE VARIAVEIS
        $keys = array_keys($vars);
        $keys = array_map(function($item){
            return '{{' .$item. '}}';
        }, $keys);

        //RETORNA O CONTEÚDO RENDERIZADO
        return str_replace($keys, array_values($vars), $contentView);
    }
}