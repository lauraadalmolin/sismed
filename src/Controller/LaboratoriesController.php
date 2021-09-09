<?php 
    
    require_once "src/Core.php";

    class LaboratoriesController extends Core {

        private $laboratories = [];

        public function showLaboratoriesView(){
            $xmlFile = simplexml_load_file("src/xml/Laboratories.xml");

            foreach ($xmlFile->children() as $laboratory) {

                array_push($this->laboratories, array(
                    "cnpj" => $laboratory->cnpj,
                    "name" => $laboratory->name,
                    "address" => $laboratory->address,
                    "phone" => $laboratory->phone,
                    "email" => $laboratory->email,
                ));
            }  

            return $this->twig->render("LaboratoriesView.twig", ["laboratories" => $this->laboratories]);
        }

    }
?>