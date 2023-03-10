<?php

namespace App\Http;

use \Closure;
use \Exception;
use Reflection;
use ReflectionFiber;
use \ReflectionFunction;

class Router
{
  /**
   * URL completa do projeto
   * @var string 
   */
  private $url = '';

  /**
   * Prefixo de todas as rotas
   * @var string
   */
  private $prefix = '';

  /**
   * Indice de rotas 
   * @var array
   */
  private $routes = [];

  /**
   * Instancia de Request
   * @var Request
   */
  private $request;

  /**
   * Método responsável por iniciar a classe
   * @param string $url
   */
  public function __construct($url)
  {

   $this->request = new Request();
   $this->url = $url;
   $this->setPrefix();

  }

  /**
   * Método responsável por definir o prefixo das rotas
   */
  private function setPrefix()
  {
    //INFORMAÇÕES DA URL
    $parseUrl = parse_url($this->url);

    //DEFINE PREFIXO
    $this->prefix = $parseUrl['path'] ?? "";
  }

  /**
   * Método responsavel por adicionar uma rota na classe
   * @param string $method 
   * @param string $route
   * @param array $params
   */
  private function addRoute($method, $route, $params = []) 
  {
    //VALIDAÇÃO DOS PARAMETROS
    foreach($params as $key => $value)
    {
      if($value instanceof Closure) 
      {
        $params['controller'] = $value;
        unset($params[$key]);
        continue;
      }
    }
    
    //VARIAVEIS DA ROTA
    $params['variables'] = [];

    //PADRAO DE VALIDAÇÃO DAS VARIAVEIS DAS ROTAS
    $patternVariable = '/{(.*?)}/';
    if (preg_match_all($patternVariable, $route, $matches)) 
    {
      $route = preg_replace($patternVariable, '(.*?)', $route);
      $params['variables'] = $matches[1];
    }

    //PADRÃO DE VALIDAÇÃO DA URL
    $patternRoute = '/^'. str_replace('/', '\/', $route) . '$/';

    //ADICIONA A ROTA DENTRO DA CLASSE
    $this->routes[$patternRoute][$method] = $params;
  }

  /**
   * Método responsavel por definir uma rota de get
   * @param string $route
   * @param array $params
   */
  public function get($route, $params = [])
  {
    return $this->addRoute('GET', $route, $params);
  }

  /**
   * Método responsavel por definir uma rota de POST
   * @param string $route
   * @param array $params
   */
  public function post($route, $params = [])
  {
    return $this->addRoute('POST', $route, $params);
  }

  /**
   * Método responsavel por definir uma rota de PUT
   * @param string $route
   * @param array $params
   */
  public function put($route, $params = [])
  {
    return $this->addRoute('PUT', $route, $params);
  }

  /**
   * Método responsavel por definir uma rota de DELETE
   * @param string $route
   * @param array $params
   */
  public function delete($route, $params = [])
  {
    return $this->addRoute('DELETE', $route, $params);
  }

  /**
   * Método responsavel por retornar a URI desconsiderando o prefico
   * @return string 
   */
  private function getUri() {
    //URI DA REQUEST
    $uri = $this->request->getUri();

    //FATIAR O URI COM PREFIXO
    $xUri = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];

    //RETORNA A URI SEM PREFIXO
    return end($xUri);
  }

  /**
   * Método responsavel por retornar os dados da rota atual
   * @return array
   */
  private function getRoute()
  {
    //URI
    $uri = $this->getUri();

    //METODO 
    $httpMethod = $this->request->getHttpMethod();

    //VALIDA AS ROTAS 
    foreach($this->routes as $patternRoute => $methods)
    {
      //VERIFICA SE A URI BATE COM O PADRÃO
      if (preg_match($patternRoute, $uri, $matches)) 
      {
        //VERIFICAR O MÉTODO
        if (isset($methods[$httpMethod])) 
        {          
          //REMOVE A PRIMEIRA POSIÇÃO
          unset($matches[0]);

          //VARIAVEIS PROCESSADAS
          $keys = $methods[$httpMethod]['variables'];
          $methods[$httpMethod]['variables'] = array_combine($keys, $matches);          
          $methods[$httpMethod]['variables']['request'] = $this->request;

          //RETORNO DOS PARAMETROS DA ROTRA
          return $methods[$httpMethod];
        }

        //MÉTODO NÃO PERMITIDO/DEFINIDO
        throw new Exception('Método não é permitido', 405);
      }
    }

    //URL NÃO ENCONTRADA
    throw new Exception('URL não encontrada', 404);
  }

  /**
   * Método responsável por executar a rota atual
   * @return Response
   */
  public function run()
  {
    try 
    {
      //OBTEM A ROTA ATUAL
      $route = $this->getRoute();

      //VERIFICA O CONTROLADOR 
      if(!isset($route['controller']))
      {
        throw new Exception('A URL não pôde ser processada', 500);
      }

      //ARGUMENTOS DA FUNÇÃO
      $args = [];

      //REFLECTION
      $reflection = new ReflectionFunction($route['controller']);
      foreach($reflection->getParameters() as $parameter)
      {
        $name = $parameter->getName();
        $args[$name] = $route['variables'][$name] ?? '';
      }

      //RETORNA A EXECUÇÃO DA FUNÇÃO
      return call_user_func_array($route['controller'], $args);
      
    } catch(Exception $e)
    {
      return new Response($e->getCode(), $e->getMessage());

    }
  }
}