<?php

class DbManager
{
  // PDOクラスのインスタンスを配列で保持する
  protected $connections = [];

  protected $repositoryConnectionMap = [];
  protected $repositories = [];

  // 接続を行う
  public function connect($name, $params)
  {
    $params = array_merge([
      'dsn'      => null,
      'user'     => '',
      'password' => '',
      'options'  => [],$params
    ]);

    $con = new PDO(
      $params['dsn'],
      $params['user'],
      $params['password'],
      $params['options']
    );

    // PDOの内部でエラーが起きた場合に例外を発生させるようにするためのもの
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $this->connections[$name] = $con;
  }

  // 名前の指定がなければ最初に作成したPDOクラスのインスタンスを返す
  public function getConnection($name = null)
  {
    if (is_null($name)) {
      return current($this->connections);
    }

    return $this->connections[$name];
  }

  // 最初に作成したもの以外を利用するときに使う
  public function setRepositoryConnectionMap($repositoryName, $name)
  {
    $this->repositoryConnectionMap[$repositoryName] = $name;
  }

  public function getConnectionForRepository($repositoryName)
  {
    if (isset($this->repositoryConnectionMap[$repositoryName])) {
      $name = $this->repositoryConnectionMap[$repositoryName];
      $con = $this->getConnection($name);
    } else {
      $con = $this->getConnection();
    }

    return $con;
  }

  public function get($repositoryName)
  {
    if (!isset($this->repositories[$repositoryName])) {
      // Repositoryのクラス名を指定
      $repositoryClass = $repositoryName . 'Repository';
      // コネクションを取得
      $con = $this->getConnectionForRepository($repositoryName);

      // 変数にクラス名を文字列で入れておくことで動的なクラス生成が可能になっている
      $repository = new $repositoryClass($con);

      // 作成したインスタンスを保持するため$repositoriesに格納する
      $this->repositories[$repositoryName] = $repository;
    }

    return $this->repositories[$repositoryName];
  }

  public function __destruct()
  {
    foreach($this->repositories as $repository) {
      unset($repository);
    }

    foreach($this->connections as $con) {
      unset($con);
    }
  }
}