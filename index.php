<?php

require __DIR__ . '/vendor/autoload.php';

use \App\Http\Router;
use \App\Utils\View;

define('URL', 'http://localhost/mvc');

//DEFINE O VALOR PADRÃO DAS VARIÁVEIS
View::init([
  'URL' => URL
]);

$obRouter = new Router(URL);

//INCLUIR AS ROTAS DE PÁGINAS
include __DIR__ . '/routes/pages.php';

//IMPRIME O RESPONSE DA ROTA
$obRouter->run()
         ->sendResponse();