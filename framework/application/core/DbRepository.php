<?php

// データベースへのアクセスを行うクラスで、テーブルごとにDbRepositoryクラスの子クラスを作成する
abstract class DbRepository
{
  protected $con;

  // DbManagerクラスからPDOクラスのインスタンスを受け取って内部に保持するためのメソッド
  public function __construct($con)
  {
    $this->setConnection($con);
  }

  // DbManagerクラスからPDOクラスのインスタンスを受け取って内部に保持するためのメソッド
  public function setConnection($con)
  {
    $this->con = $con;
  }

  public function execute($sql, $params = [])
  {
    $stmt = $this->con->prepare($sql);
    $stmt->execute($params);

    return $stmt;
  }

  // 1行のみ取得するメソッド
  public function fetch($sql, $params = [])
  {
    return $this->execute($sql, $params)->fetch(PDO::FETCH_ASSOC);
  }

  // 全ての行を取得するメソッド
  public function fetchAll($sql, $params = [])
  {
    return $this->execute($sql, $params)->fetchAll(PDO::FETCH_ASSOC);
  }
}