<?php

require_once('views/View.php');

class ControllerArticle {

  private $_articleManager;
  private $_view;

  public function __construct() {
  }

  public function index() {
    if(isset($url) && count($url) > 1) {
      throw new Exception('Page introuvable.');
    } else {
      $this->_articleManager = new ArticleManager();
      $articles = $this->_articleManager->getArticles();
      $this->_view = new View('Articles');
      $this->_view->generate(array('articles' => $articles));
    }
  }

  public function view($id) {
    $this->_articleManager = new ArticleManager();
    $article = $this->_articleManager->getArticle($id);
    $this->_view = new View('Article');
    $this->_view->generate(array('article' => $article));
  }
}
