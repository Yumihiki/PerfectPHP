<?php

// RequestクラスやRouterクラス、Responseクラス、Sessionクラスなどのオブジェクトの管理を行う他、
// ルーティングの定義、コントローラの実行、レスポンスの送信などアプリケーション全体の流れを司る
abstract class Application
{
  protected $debug = false;
  protected $request;
  protected $response;
  protected $session;
  protected $db_manager;
  protected $login_action = [];
  
  public function __construct($debug = false)
  {
    $this->setDebugMode($debug);
    $this->initialize();
    $this->configure();
  }

  // デバッグモードに応じてエラー表示処理を変更
  protected function setDebugMode($debug)
  {
    if ($debug) {
      $this->debug = true;
      ini_set('display_errors', 1);
      error_reporting(-1);
    } else {
      $this->debug = false;
      ini_set('display_errors', 0);
    }
  }

  // クラスの初期化処理
  protected function initialize()
  {
    $this->request  = new Request();
    $this->response = new Response();
    $this->session  = new Session();
    $this->db_manager = new DbManager();
    $this->router = new Router($this->registerRoutes());
  }

  // 個別のアプリケーションで様々な設定をできるように定義
  protected function configure()
  {
  }

  // アプリケーションのルートディレクトリへのパスを返す
  // アプリケーションごとに設定するよう抽象メソッドとして定義
  abstract public function getRootDir();

  // 抽象メソッドとして定義　=> 呼び出し側は変える必要がなく、個別のアプリケーションでregisterRoutes()メソッドを定義漏れもなくなる
  abstract protected function registerRoutes();

  public function isDebugMode()
  {
    return $this->debug;
  }

  public function getRequest()
  {
    return $this->request;
  }

  public function getResponse()
  {
    return $this->response;
  }

  public function getSession()
  {
    return $this->session;
  }

  public function getDbManager()
  {
    return $this->db_manager;
  }

  public function getControllerDir()
  {
    return $this->getRootDir() . '/controllers';
  }

  public function getViewDir()
  {
    return $this->getRootDir() . '/views';
  }

  public function getModelDir()
  {
    return $this->getRootDir() . '/models';
  }

  public function getWebDir()
  {
    return $this->getRootDir() . '/web';
  }

  // Routerクラスのresolve()メソッドを呼び出してルーティングパラメータを取得し、
  // コントローラ名とアクション名を特定
  // それらの値を基にrunAction() メソッドを呼び出してアクションを実行
  // これを実行することでApplicationクラスの内部にもつオブジェクトを流れに沿って呼び出し
  // レスポンスを返す
  public function run()
  {
    try {
      // Routerクラスをrouterとするのがちょっとわかんなかった
      $params = $this->router->resolve($this->request->getPathInfo());
      if ($params === false) {
        throw new HttpNotFoundException('No route found for ' . $this->request->getPathInfo());
      }

      $controller = $params['controller'];
      $action = $params['action'];

      $this->runAction($controller, $action, $params);

    } catch (HttpNotFoundException $e) {
      $this->render404Page($e);

    } catch (UnauthorizedActionException $e) {
      
      list($controller, $action) = $this->login_action;
      $this->runAction($controller, $action);
    }
    $this->response->send();
  }

  // runメソッドによってアクションを実行するメソッド
  // ルーティングにはコントローラ名の先頭を小文字で指定するようにしたので
  // ucfirst関数を用いて先頭を大文字にする
  // 内部で別のアクションを呼び出したい場合はこのメソッドにコントローラ名とアクション名を
  // 直接指定して呼び出すことも可能
  public function runAction($controller_name, $action, $params = [])
  {
    $controller_class = ucfirst($controller_name) . 'Controller';

    $controller = $this->findController($controller_class);
    if ($controller === false) {
      throw new HttpNotFoundException($controller_class . ' controller is not found.');
    }

    $content = $controller->run($action, $params);

    $this->response->setContent($content);
  }

  protected function findController($controller_class)
  {
    if (!class_exists($controller_class)) {
      $controller_file = $this->getControllerDir() . '/' . $controller_class . '.php';

      if (!is_readable($controller_file)) {
        return false;
      } else {
        require_once $controller_file;

        if (!class_exists($controller_class)) {
          return false;
        }
      }
    }

    return new $controller_class($this);
  }
  
  protected function render404Page($e)
  {
    $this->response->setStatusCode(404, 'Not Found');
    $message = $this->isDebugMode() ? $e->getMessage() : 'Page not found.';
    $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

    $this->response->setContent(<<<EOF
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>404</title>
</head>
<body>
  {$message}
</body>
</html>
EOF
    );
  }
}
