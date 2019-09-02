<?php

// レスポンスを表すクラス
// HTTPヘッダとHTMLなどのコンテンツを返すのが主な役割
class Response
{
  protected $content;
  protected $statusCode  = 200;
  protected $statusText  = 'OK';
  protected $httpHeaders = [];

  // 各プロパティに接待された値を基にレスポンスの送信を行う
  public function send()
  {
    // ステータスコードの指定
    header('HTTP/1.1' . $this->statusCode . ' ' . $this->statusText);

    // $httpHeadersプロパティにHTTPレスポンスヘッダの指定があればheader()関数を用いて送信
    foreach($this->httpHeaders as $name => $value) {
      header($name . ': ' . $value);
    }

    // レスポンスの内容を送信(echoを用いて出力するだけで送信される)
    echo $this->content;
  }

  public function setContent($content)
  {
    $this->content = $content;
  }

  public function setStatusCode($statusCode, $statusText = '')
  {
    $this->statusCode = $statusCode;
    $this->statusText = $statusText;
  }

  public function setHttpHeader($name, $value)
  {
    $this->httpHeaders[$name] = $value;
  }
}

