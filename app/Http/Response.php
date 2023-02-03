<?php

namespace App\Http;

class Response
{

  /**
   * Código do status HTTP
   * @var integer
   */
  private $httpCode = 200;

  /**
   * Cabeçalho do Response
   * @var array
   */
  private $headers = [];

  /**
   * Tipo de conteudo que está sendo retornado
   * @var string
   */
  private $contentType = 'text/html';

   /**
   * Conteudo do response
   * @var mixed
   */
  private $content = '';

  /**
   * Método responsável por iniciar a classe e definir os valores
   * @param integer $httpCode
   * @param mixed $content
   * @param string $contentType
   */
  public function __construct($httpCode, $content, $contentType = 'text/html')
  { 
    $this->httpCode = $httpCode;
    $this->content = $content;    
    $this->setContentType($contentType);
  }

  /**
   * Método responsavel por alterar o content type do reponse
   * @param string $contentType
   */
  public function setContentType($contentType)
  {
    $this->contentType = $contentType;
    $this->addHeader('Content-Type', $contentType);
  }

  /**
   * Método responsavel por adicionar um registro no cabeçalho de response
   * @param string $key
   * @param string $value
   */
  public function addHeader($key, $value)
  {
    $this->headers[$key] = $value;    
  }

  /**
   * Método responsável por enviar os headers para o navegador
   */

  private function sendHeaders()
  {
    //STATUS
    http_response_code($this->httpCode);

    //ENVIA HEADERS
    foreach($this->headers as $key => $value){
      header($key.': '. $value);
    }
  }

  /**
   * Método responsavel por enviar a resposta para o usuario
   */
  public function sendResponse()
  {
    $this->sendHeaders();

    //IMPRIME CONTEUDO
    switch($this->contentType) {
      case 'text/html':
        echo $this->content;
        exit;      
    }
  }
}