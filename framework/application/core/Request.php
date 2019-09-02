<?php

class Request
{
  public function isPost()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      return true;
    }
    return false;
  }

  public function getGet($name, $default = null)
  {
    if (isset($_GET[$name])) {
      return $_GET[$name];
    }
    return $default;
  }

  public function getPost($name, $default = null)
  {
    if (isset($_POST[$name])) {
      return $_POST[$name];
    }
    return $default;
  }

  public function getHost() {
    if (!empty($_SERVER['HTTP_HOST'])) {
      return $_SERVER['HTTP_HOST'];
    }
    return $_SERVER['SERVER_NAME'];
  }

  public function isSsl()
  {
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
      return true;
    }
    return false;
  }

  public function getRequestUri()
  {
    return $_SERVER['REQUEST_URI'];
  }

  // ベースURLを取得する
  public function getBaseUrl()
  {
    $scriptName = $_SERVER['SCRIPT_NAME'];

    $requestUri = $this->getRequestUri();

    // フロントコントローラがURLに含まれる場合
    // SCRIPT_NAMEの値がベースURLと同じになるため、SCRIPT_NAMEをそのまま返す
    if (0 === strpos($requestUri, $scriptName)) {
      return $scriptName;
      
    } elseif (0 === strpos($requestUri, dirname($scriptName))) {
      return rtrim(dirname($scriptName), '/');
    }
    return '';
  }

  // PATH_INFOを取得する
  public function getPathInfo()
  {
    $baseUrl = $this->getBaseUrl();
    $requestUri = $this->getRequestUri();

    if (false !== ($pos = strpos($requestUri, '?'))) {
      $requestUri = substr($requestUri, 0, $pos);
    }

    $pathInfo = (string)substr($requestUri, strlen($baseUrl));

    return $pathInfo;
  }
}