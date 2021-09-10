<?php 
    
    require_once "src/Core.php";

    class MedicalAppointmentsController extends Core {

        private $medical_appointments = [];

        public function showMedicalAppointmentsView(){
            $xmlFile = simplexml_load_file("src/xml/MedicalAppointments.xml");

            foreach ($xmlFile->children() as $medical_appointment) {
                array_push($this->medical_appointments, array(
                    "date" => $medical_appointment->date,
                    "doctor" => $medical_appointment->doctor,
                    "patient" => $medical_appointment->patient,
                    "prescription" => $medical_appointment->prescription,
                    "observations" => $medical_appointment->observations,
                ));
            }  

            return $this->twig->render("MedicalAppointmentsView.twig", ["medical_appointments" => $this->medical_appointments]);
        }

    }
