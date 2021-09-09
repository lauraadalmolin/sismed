<?php 
    
    require_once "src/Core.php";

    class DoctorsController extends Core {
        
        private $doctors = [];

        public function showDoctorsView(){

            $xmlFile = simplexml_load_file("src/xml/Doctors.xml");

            foreach ($xmlFile->children() as $doctor) {

                array_push($this->doctors, array(
                    "crm" => $doctor->crm,
                    "name" => $doctor->name,
                    "address" => $doctor->address,
                    "phone" => $doctor->phone,
                    "email" => $doctor->email,
                    "specialty" => $doctor->specialty,
                ));
            }  
            return $this->twig->render("DoctorsView.twig", ["doctors" => $this->doctors]);
        }

    }
?>