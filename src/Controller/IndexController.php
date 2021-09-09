<?php 
    
    require_once "src/Core.php";

    class IndexController extends Core {

        public function showHomeView(){
            return $this->twig->render("IndexView.twig");
        }

    }
?>