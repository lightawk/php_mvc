<?php

abstract class Model {

  private static $_bdd;

  // Instancier la connexion
  private static function setBDD() {
    self::$_bdd = new PDO('mysql:host=localhost;dbname=app;charset=utf8', 'root', '');
    self::$_bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
  }

  // Recupere la connexion
  protected function getBDD() {
    if(self::$_bdd == null) {
      self::setBDD();
      return self::$_bdd;
    }
  }

  // Recuperer toutes les donnees d'une table
  protected function SelectAll($table, $obj) {
    $var = [];
    $req = self::$_bdd->prepare('SELECT * FROM ' . $table . ' ORDER BY id DESC');
    $req->execute();
    while($data = $req->fetch(PDO::FETCH_ASSOC)) {
      $var[] = new $obj($data);
    }
    return $var;
    $req->close();
  }

  /*
   * Recuperer une ligne d'une table via l'ID
   * @return $obj Objet hydrate avec les donnees
   */
  protected function SelectOne($table, $obj, $id) {
    $var = null;
    $req = self::$_bdd->prepare('SELECT * FROM ' . $table . ' WHERE id = :id ORDER BY id DESC');
    $req->bindParam(':id', $id, PDO::PARAM_INT);
    $req->execute();
    return new $obj($req->fetch(PDO::FETCH_ASSOC));
    $req->close();
  }
}
