<?php

require_once('views/View.php');

class Router {

  private $_controller = 'ControllerArticle';
  private $_method = 'index';
  private $_params = [];
  private $_view;

  public function __construct() {
    // Chargement automatique des classes du modele
    spl_autoload_register(function($classe) {
      require_once('models/'. $classe . '.php');
    });
  }

  // Pour la gestion du routage
  public function router() {
    try {
      $url = array();
      if(isset($_GET['url'])) {
        $url = $this->parseURL();
        $this->setController($url);
        $this->setMethod($url);
        $this->_params = $url ? array_values(array_splice($url, 2)) : [];
      } else {
        require_once('controllers/' . $this->_controller . '.php');
        $this->_controller = new $this->_controller($url);
      }
      // Appel de la methode du controleur avec ses parametres
      call_user_func_array(array($this->_controller, $this->_method), $this->_params);
    }
    catch(Exception $e) {
      $errorMsg = $e->getMessage();
      $this->_view = new View('Error');
      $this->_view->generate(array('errorMsg' => $errorMsg));
    }
  }

  private function parseURL() {
    return $url = explode(
      '/',
      filter_var(
        rtrim($_GET['url'], '/')
      , FILTER_SANITIZE_URL
      )
    );
  }

  private function setController($url) {
    if(!empty($url[0])) {
      $controller = ucfirst(strtolower($url[0]));
      $controllerClass = 'Controller' . $controller;
      $controllerFile = 'controllers/' . $controllerClass . '.php';
      if(file_exists($controllerFile)) {
        require_once($controllerFile);
        $this->_controller = new $controllerClass($url);
      } else {
        throw new Exception('Page introuvable.');
      }
    }
  }

  private function setMethod($url) {
    if(!empty($url[1])) {
      $method = strtolower($url[1]);
      if(isset($method)) {
        if(method_exists($this->_controller, $method)) {
          $this->_method = $method;
        } else {
          throw new Exception('Page introuvable.');
        }
      }
    }
  }
}
