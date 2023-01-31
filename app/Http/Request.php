<?php

namespace App\Http;

class Request
{

  /**
   * Método HTTP da requisição
   * @var string
   */
  private $httpMethod;

  /**
   * URI da página
   * @var string
   */
  private $uri;

  /**
   * Paramentros da URL ($_GET)
   * @var array
   */
  private $queryParams = [];

  /**
   * Variaveis recebidas no POST da pagina ($_POST)
   * @var array
   */
  private $postVars = [];

  /**
   * Cabeçalho da requisão
   * @var array
   */
  private $headers = [];

   /**
   * Construtor da classe   
   */
  public function __construct()
  {
    $this->queryParams = $_GET ?? [];
    $this->postVars = $_POST ?? [];
    $this->headers = getallheaders();
    $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
    $this->uri = $_SERVER['REQUEST_URI'] ?? '';
  }


  
}