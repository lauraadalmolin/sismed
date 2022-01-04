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
            $id = md5(uniqid(rand(), true));
            
            $data = array('id' => $id, 'patientId' => $patientID,'patient' => $patientName,'prescription' => $prescription,'observations' => $observations,'doctorId' => $doctorID,'doctor' => $doctorName,'date' => $date);
            $this->insert($data, 'INSERT INTO medical_appointments(id,patientId,patient,doctor,doctorId,date,prescription,observations) VALUES (:id,:patientId,:patient,:doctor,:doctorId,:date,:prescription,:observations);' );

            header("Location: /sismed/consultas/");
        }

        public function getConsultaByID(){

            if(isset($_POST['queryString'])) {
                $queryString = $_POST['queryString'];
                
                if(strlen($queryString) > 0) {
                    
                    $consulta = $this->get("SELECT * FROM medical_appointments WHERE id='$queryString';");
                    if(count($consulta) > 0) {
                        $key = array_search($queryString, array_column($consulta, 'id'));
                        return json_encode($consulta[$key]);
                    } 
                }
            }
            return null;
        }

        public function editarConsulta(){
            $consultaID = $_POST["consultaID-edit"];
            $patientID = $_POST["patientID-edit"];
            $patientName = $_POST["patient"];
            $prescription = $_POST["prescription-edit"];
            $observations = $_POST["observations-edit"];
            $date = $_POST["date-edit"]." ".$_POST["timepicker-edit"]; 

            $consulta = $this->get("SELECT * FROM medical_appointments WHERE id='$consultaID';");

            if(count($consulta) > 0) {
                $data = array('id' => $consultaID, 'patientId' => $patientID,'patient' => $patientName,'prescription' => $prescription,'observations' => $observations,'date' => $date);
                $this->update($data, "UPDATE medical_appointments SET id=:id,patientId=:patientId,patient=:patient,prescription=:prescription,observations=:observations,date=:date WHERE id=:id;");

                header("Location: /sismed/consultas/");
            } else {
                header("Location: /sismed/consultas/");
            }

        }

        private function loadAllAppointmentsByDoctorID($doctorID){
            $dbData = $this->get("SELECT * FROM medical_appointments WHERE doctorId='$doctorID';");

            foreach ($dbData as $consulta) {
                array_push($this->medical_appointments, $consulta);
            }

        }

        private function loadAllAppointmentsByPatientID($patientID){
            $dbData = $this->get("SELECT * FROM medical_appointments WHERE patientId='$patientID';");

            foreach ($dbData as $consulta) {
                array_push($this->medical_appointments, $consulta);
            }

        }
    }
