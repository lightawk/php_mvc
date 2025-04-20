<?php

class ArticleManager extends Model {

  public function __construct() {
    $this->getBDD();
  }

  public function getArticles() {
    return $this->SelectAll('articles', 'Article');
  }

  public function getArticle($id) {
    return $this->SelectOne('articles', 'Article', $id);
  }
}
