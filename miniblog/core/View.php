<?php

class View
{
  protected $baseDir;
  protected $defaults;
  protected $layoutVariables = [];

  /**
   * $baseDir : ビューファイルを格納しているviewsディレクトリへの絶対パスを指定
   */
  public function __construct($baseDir, $defaults = [])
  {
    $this->baseDir  = $baseDir;
    $this->defaults = $defaults;
  }

  public function setLayoutVar($name, $value)
  {
    $this->layoutVariables[$name] = $value;
  }

  public function render($_path, $_variables = [], $_layout = false)
  {
    $_file = $this->baseDir . '/' . $_path . '.php';

    extract(array_merge($this->defaults, $_variables));

    ob_start();
    ob_implicit_flush(0);
    
    require $_file;

    $content = ob_get_clean();

    if ($_layout) {
      $content = $this->render($_layout,
        array_merge($this->layoutVariables, [
          '_content' => $content,
        ]));
    }
    return $content;
  }
  public function escape($string)
  {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
  }
}