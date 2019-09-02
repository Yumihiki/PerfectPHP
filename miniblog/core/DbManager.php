<?php

class DbManager
{
  // PDOクラスのインスタンスを配列で保持する
  protected $connections = [];
  protected $repository_connection_map = [];
  protected $repositories = [];

  // 接続を行う
  public function connect($name, $params)
  {
    $params = array_merge([
      'dsn'      => null,
      'user'     => '',
      'password' => '',
      'options'  => [],
    ], $params);

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
  public function setRepositoryConnectionMap($repository_name, $name)
  {
    $this->repository_connection_map[$repository_name] = $name;
  }

  public function getConnectionForRepository($repository_name)
  {
    if (isset($this->repository_connection_map[$repository_name])) {
      $name = $this->repository_connection_map[$repository_name];
      $con = $this->getConnection($name);
    } else {
      $con = $this->getConnection();
    }

    return $con;
  }

  public function get($repository_name)
  {
    if (!isset($this->repositories[$repository_name])) {
      // Repositoryのクラス名を指定
      $repository_class = $repository_name . 'Repository';
      // コネクションを取得
      $con = $this->getConnectionForRepository($repository_name);

      // 変数にクラス名を文字列で入れておくことで動的なクラス生成が可能になっている
      $repository = new $repository_class($con);

      // 作成したインスタンスを保持するため$repositoriesに格納する
      $this->repositories[$repository_name] = $repository;
    }

    return $this->repositories[$repository_name];
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