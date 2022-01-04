<?php 
    
    require_once "src/Core.php";

    class ExamsController extends Core {

        private $exams = [];

        public function showExamsView(){
            if($this->user["role"] === "laboratorie"){
                $this->loadAllExamsByLabID($this->user["id"]);
            } else {
                $this->loadAllExamsByPatientID($this->user["id"]);
            }

            return $this->twig->render("ExamsView.twig", ["exams" => $this->exams]);
        }
        public function novoExame(){
            $patientID = $_POST["patientID"];
            $patientName = $_POST["patient"];
            $type = $_POST["type"];
            $result = $_POST["result"];
            $date = $_POST["date"]." ".$_POST["timepicker"];
            $labID = $this->user["id"];
            $labName = $this->user["name"];
            $id = md5(uniqid(rand(), true));
            
            $data = array('id' => $id, 'patientId' => $patientID, 'patient' => $patientName, 'type' => $type, 'result' => $result, 'date' => $date, 'laboratoryId' => $labID, 'laboratory' => $labName,);
            $this->insert($data, 'INSERT INTO exams(id,patientId,patient,laboratory,laboratoryId,date,type,result) VALUES (:id,:patientId,:patient,:laboratory,:laboratoryId,:date,:type,:result);' );

            header("Location: /sismed/exames/");
        }

        public function editarExame(){            
            $patientID = $_POST["patientID-edit"];
            $id = $_POST["examID"];
            $patientName = $_POST["patient"];
            $type = $_POST["type-edit"];
            $result = $_POST["result-edit"];
            $date = $_POST["date-edit"]." ".$_POST["timepicker-edit"];

            $exame = $this->get("SELECT * FROM exams WHERE id='$id';");

            if(count($exame) > 0) {                
                $data = array('id' => $id, 'patientId' => $patientID, 'patient' => $patientName, 'type' => $type, 'result' => $result, 'date' => $date);
                $this->update($data, "UPDATE exams SET id=:id,patientId=:patientId,patient=:patient,type=:type,result=:result,date=:date WHERE id=:id;");
                header("Location: /sismed/exames/");
            } else {
                header("Location: /sismed/exames/");
            }
        }

        public function getExamByID(){

            if(isset($_POST['queryString'])) {
                $queryString = $_POST['queryString'];

                if(strlen($queryString) > 0) {
                    $exame = $this->get("SELECT * FROM exams WHERE id='$queryString';");
                    if(count($exame) > 0) {
                        $key = array_search($queryString, array_column($exame, 'id'));
                        return json_encode($exame[$key]);
                    } 
                }
            }
            return null;
        }

        private function loadAllExamsByLabID($labID){
            $dbData = $this->get("SELECT * FROM exams WHERE laboratoryId='$labID';");

            foreach ($dbData as $exam) {
                array_push($this->exams, $exam);
            }
        }
        private function loadAllExamsByPatientID($patientID){
            $dbData = $this->get("SELECT * FROM exams WHERE patientId='$patientID';");

            foreach ($dbData as $exam) {
                array_push($this->exams, $exam);
            }
        }
    }
