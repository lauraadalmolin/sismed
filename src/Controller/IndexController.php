<?php 
    
    require_once "src/Core.php";

    class IndexController extends Core {
        private $home_cards;

        public function showHomeView(){
            if($this->user["role"] === "patient"){
                $this->home_cards = array(
                    array(
                        'title' => "Consultas",
                        'link' => '/sismed/consultas/',
                        'icon' => "fa-calendar"
                    ),
                    array(
                        'title' => "Exames",
                        'link' => '/sismed/exames/',
                        'icon' => "fa-diagnoses"
                    ),
                );
            } elseif($this->user["role"] === "doctor"){
                $this->home_cards = array(
                    array(
                        'title' => "Consultas",
                        'link' => '/sismed/consultas/',
                        'icon' => "fa-calendar"
                    ),
                );
            } elseif($this->user["role"] === "admin"){
                $this->home_cards = array(
                    array(
                        'title' => "Laboratórios",
                        'link' => '/sismed/laboratorios/',
                        'icon' => "fa-clinic-medical"
                    ),
                    array(
                        'title' => "Pacientes",
                        'link' => '/sismed/pacientes/',
                        'icon' => "fa-user"
                    ),
                    array(
                        'title' => "Médicos",
                        'link' => '/sismed/medicos/',
                        'icon' => "fa-user-md"
                    ),
                );
            }

            return $this->twig->render("IndexView.twig", ["cards" => $this->home_cards]);
        }

    }
?>