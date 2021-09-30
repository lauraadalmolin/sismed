<?php 
    
    require_once "src/Core.php";

    class MedicalAppointmentsController extends Core {

        private $medical_appointments = [];

        public function showMedicalAppointmentsView(){
            if($this->user["role"] === "doctor"){
                $this->loadAllAppointmentsByDoctorID($this->user["id"]);
            } else {
                $this->loadAllAppointmentsByPatientID($this->user["id"]);
            }

            return $this->twig->render("MedicalAppointmentsView.twig", ["medical_appointments" => $this->medical_appointments]);
        }

        public function novaConsulta(){
            $patientID = $_POST["patientID"];
            $patientName = $_POST["patient"];
            $prescription = $_POST["prescription"];
            $observations = $_POST["observations"];
            $doctorID = $this->user["id"];
            $doctorName = $this->user["name"];
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

        public function getConsultaByID(){
            $this->loadAllAppointments();

            if(isset($_POST['queryString'])) {
                $queryString = $_POST['queryString'];
                
                if(strlen($queryString) > 0) {
                    $key = array_search($queryString, array_column($this->medical_appointments, 'id'));
                    
                    if($key === 0 || $key > 0) {
                        $consulta = array(
                            "id" => $this->medical_appointments[$key]["id"],
                            "date"=> $this->medical_appointments[$key]["date"],
                            "patient" => $this->medical_appointments[$key]["patient"],
                            "patientID" => $this->medical_appointments[$key]["patientID"],
                            "prescription" => $this->medical_appointments[$key]["prescription"],
                            "observations" => $this->medical_appointments[$key]["observations"],
                        );

                        return json_encode($consulta);
                    } 
                }
            }
            return null;
        }

        public function editarConsulta(){
            $this->loadAllAppointments();

            $consultaID = $_POST["consultaID-edit"];
            $patientID = $_POST["patientID-edit"];
            $patientName = $_POST["patient"];
            $prescription = $_POST["prescription-edit"];
            $observations = $_POST["observations-edit"];
            $date = $_POST["date-edit"]." ".$_POST["timepicker-edit"]; 

            $key = array_search($consultaID, array_column($this->medical_appointments, 'id'));

            if($key === 0 || $key > 0) {
                $xmlFile = simplexml_load_file("src/xml/MedicalAppointments.xml");
    
                $consulta = $xmlFile->medical_appointment[$key];
                $consulta->patientID = $patientID;
                $consulta->patient = $patientName;
                $consulta->prescription = $prescription;
                $consulta->observations = $observations;
                $consulta->date = $date;
    
                $xmlFile->asXML("src/xml/MedicalAppointments.xml");

                header("Location: /sismed/consultas/");
            } else {
                header("Location: /sismed/consultas/");
            }

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

        private function loadAllAppointmentsByDoctorID($doctorID){
            $xmlFile = simplexml_load_file("src/xml/MedicalAppointments.xml");

            foreach ($xmlFile->children() as $medical_appointment) {
                if($medical_appointment->doctorID->__toString() === $doctorID){
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

        private function loadAllAppointmentsByPatientID($patientID){
            $xmlFile = simplexml_load_file("src/xml/MedicalAppointments.xml");

            foreach ($xmlFile->children() as $medical_appointment) {
                if($medical_appointment->patientID->__toString() === $patientID){
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
    }
