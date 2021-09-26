<?php 
    
    require_once "src/Core.php";

    class MedicalAppointmentsController extends Core {

        private $medical_appointments = [];

        public function showMedicalAppointmentsView(){
            $this->loadAllAppointments();

            return $this->twig->render("MedicalAppointmentsView.twig", ["medical_appointments" => $this->medical_appointments]);
        }

        public function novaConsulta(){
            $patientID = $_POST["patientID"];
            $patientName = $_POST["patient"];
            $doctorID = $this->user["id"];
            $doctorName = $this->user["name"];
            $prescription = $_POST["prescription"];
            $observations = $_POST["observations"];
            $date = $_POST["date"]." ".$_POST["timepicker"]; 
           
            $xmlFile = simplexml_load_file("src/xml/MedicalAppointments.xml");

            $consulta = $xmlFile->addChild("medical_appointment");
            $consulta->addChild("id", md5(uniqid(rand(), true)));
            $consulta->addChild("date", $date);
            $consulta->addChild("doctor", $doctorName);
            $consulta->addChild("doctorID", $doctorID);
            $consulta->addChild("patient", $patientName);
            $consulta->addChild("patientID", $patientID);
            $consulta->addChild("prescription", $prescription);
            $consulta->addChild("observations", $observations);
            
            $xmlFile->asXML("src/xml/MedicalAppointments.xml");

            header("Location: /sismed/consultas/");
        }

        private function loadAllAppointments(){
            $xmlFile = simplexml_load_file("src/xml/MedicalAppointments.xml");

            foreach ($xmlFile->children() as $medical_appointment) {
                array_push($this->medical_appointments, array(
                    "id" => $medical_appointment->id->__toString(),
                    "date" => $medical_appointment->date->__toString(),
                    "doctor" => $medical_appointment->doctor->__toString(),
                    "doctorID" => $medical_appointment->doctorID->__toString(),
                    "patient" => $medical_appointment->patient->__toString(),
                    "patientID" => $medical_appointment->patientID->__toString(),
                    "prescription" => $medical_appointment->prescription->__toString(),
                    "observations" => $medical_appointment->observations->__toString(),
                ));
            }  

        }
    }
