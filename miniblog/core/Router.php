<?php

class Router 
{
  protected $routes;

  public function __construct($definitions)
  {
    $this->routes = $this->compileRoutes($definitions);
  }

  // 渡されたルーティング定義配列のそれぞれのキーに含まれる動的パラメータを正規表現でキャプチャできる形式に変換
  public function compileRoutes($definitions)
  {
    $routes = array();

    foreach($definitions as $url => $params) {
      // explode()関数を用いてスラッシュごとに分割
      $tokens = explode('/', ltrim($url, '/'));
      // 分割した値の中にコロンで始まる文字列があった場合は、正規表現の形式に変換
      foreach($tokens as $i => $token) {
          if (0 === strpos($token, ':')) {
          $name = substr($token, 1);
          $token = '(?P<' .  $name . '>[^/]+)';
        }
        $tokens[$i] = $token;
      }
      $pattern = '/' . implode('/', $tokens);
      // 分割したURLを再度スラッシュで繋げ、変換済の値として$routes変数に格納
      $routes[$pattern] = $params;
    }
    return $routes;
  }

  // マッチングを行う
  // Applicationからパスを渡される
  public function resolve($path_info)
  {
    // PATH_INFOの先頭がスラッシュでない場合、先頭にスラッシュをつける
    if ('/' !== substr($path_info, 0, 1)) {
      $path_info = '/' . $path_info;
    }

    foreach ($this->routes as $pattern => $params) {
      // 変換済みのルーティング定義配列は$routesプロパティに格納されているので、正規表現を用いてマッチング
      if (preg_match('#^' . $pattern . '$#', $path_info, $matches)) {
        // マッチした場合、定義された値とキャプチャした値をマージし$params変数に1つのルーティングパラメータとして格納し、返す
        $params = array_merge($params, $matches);

        return $params;
      }
    }
    return false;
  }
}