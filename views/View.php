<?php

class View {

    private $_file;
    private $_title;

    public function __construct($action) {
        $this->_file = 'views/view' . $action . '.php';
    }

    // Generer et afficher la vue (injecter les donnes dans le fichier)
    public function generate($data) {
        // Partie specifique de la vue
        $content = $this->generateFile($this->_file, $data);
        // Template qui contient la partie specifique pour la vue genere plus haut
        $view = $this->generateFile('views/template.php',
            array(
                'title' => $this->_title,
                'content' => $content
            )
        );
        echo $view;
    }

    // Generer un fichier vue et renvoie le resultat produit
    private function generateFile($file, $data) {
        if(file_exists($file)) {
            // Defaire le tableau (remet mes valeurs dans les variables)
            extract($data);
            // Demarrer la mise en tampon (cree un buffer de sortie ; non affiche a l'ecran)
            ob_start();
            // Appel du fichier dans le buffer (en background)
            require_once $file;
            // Arreter la temporisation et renvoyer le tampon de sortie (stocke le contenu d'un buffer de sortie dans une variable)
            return ob_get_clean();
        } else {
            throw new Exception('Fichier ' . $file . ' introuvable !');
        }
    }
}