<?php

// レスポンスを表すクラス
// HTTPヘッダとHTMLなどのコンテンツを返すのが主な役割
class Response
{
  protected $content;
  protected $status_code  = 200;
  protected $status_text  = 'OK';
  protected $http_headers = [];

  // 各プロパティに接待された値を基にレスポンスの送信を行う
  public function send()
  {
    // ステータスコードの指定
    header('HTTP/1.1' . $this->status_code . ' ' . $this->status_text);

    // $http_headersプロパティにHTTPレスポンスヘッダの指定があればheader()関数を用いて送信
    foreach($this->http_headers as $name => $value) {
      header($name . ': ' . $value);
    }

    // レスポンスの内容を送信(echoを用いて出力するだけで送信される)
    echo $this->content;
  }

  public function setContent($content)
  {
    $this->content = $content;
  }

  public function setStatusCode($status_code, $status_text = '')
  {
    $this->status_code = $status_code;
    $this->status_text = $status_text;
  }

  public function setHttpHeader($name, $value)
  {
    $this->http_headers[$name] = $value;
  }
}

