<?php 
    
    require_once "src/Core.php";

    class PatientsController extends Core {

        private $patients = [];

        public function showPatientsView(){
            $xmlFile = simplexml_load_file("src/xml/Patients.xml");

            foreach ($xmlFile->children() as $patient) {

                array_push($this->patients, array(
                    "name" => $patient->name,
                    "address" => $patient->address,
                    "phone" => $patient->phone,
                    "email" => $patient->email,
                    "gender" => $patient->gender,
                    "age" => $patient->age,
                    "cpf" => $patient->cpf,
                ));
            }  

            return $this->twig->render("PatientsView.twig", ["patients" => $this->patients]);
        }

    }
