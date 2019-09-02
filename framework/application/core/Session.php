<?php

// セッション情報を管理するクラス
// $_SESSION変数のラッパークラス
class Session
{
  protected static $sessionStarted = false;
  protected static $sessionIdRegenerated = false;

  public function __construct()
  {
    if (!self::$sessionStarted) {
      // セッションの作成、クッキーなどから受け取ったセッションIDを基にセッションの復元を行う関数
      session_start();

      self::$sessionStarted = true;
    }
  }

  // $_SESSIONへの設定
  public function set($name, $value)
  {
    $_SESSION[$name] = $value;
  }

  // $_SESSIONへの取得
  public function get($name, $default = null)
  {
    if (isset($_SESSION[$name])) {
      return $_SESSION[$name];
    }

    return $default;
  }

  // $_SESSIONから指定した値を削除する
  public function remove($name)
  {
    unset($_SESSION[$name]);
  }

  // $_SESSIONを空にする
  public function clear()
  {
    $_SESSION = [];
  }

  // セッションIDを新しく発行するためのsession_regenerate_id()関数を実行
  // 一度のリクエスト中に複数回呼び出されることが内容、静的プロパティでチェック
  public function regenerate($destroy = true)
  {
    if (!self::$sessionIdRegenerated) {
      session_regenerate_id($destroy);

      self::$sessionIdRegenerated = true;
    }
  }

  // ユーザがログイン状態を制御するメソッド
  public function setAuthenticated($bool)
  {
    $this->set('_authenticated', (bool)$bool);

    $this->regenerate();
  }

  public function isAuthenticated()
  {
    return $this->get('_authenticated', false);
  }
}