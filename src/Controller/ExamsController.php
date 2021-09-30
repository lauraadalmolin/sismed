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

            $xmlFile = simplexml_load_file("src/xml/Exams.xml");

            $exame = $xmlFile->addChild("exam");
            $exame->addChild("id", md5(uniqid(rand(), true)));
            $exame->addChild("date", $date);
            $exame->addChild("laboratory", $labName);
            $exame->addChild("laboratoryID", $labID);
            $exame->addChild("patient", $patientName);
            $exame->addChild("patientID", $patientID);
            $exame->addChild("type", $type);
            $exame->addChild("result", $result);
            
            $xmlFile->asXML("src/xml/Exams.xml");

            header("Location: /sismed/exames/");
        }

        public function editarExame(){
            $this->loadAllExams();
            
            $patientID = $_POST["patientID-edit"];
            $id = $_POST["examID"];
            $patientName = $_POST["patient"];
            $type = $_POST["type-edit"];
            $result = $_POST["result-edit"];
            $date = $_POST["date-edit"]." ".$_POST["timepicker-edit"];

            $key = array_search($id, array_column($this->exams, 'id'));

            if($key === 0 || $key > 0) {
                $xmlFile = simplexml_load_file("src/xml/Exams.xml");
    
                $exame = $xmlFile->exam[$key];

                $exame->patientID = $patientID;
                $exame->patient = $patientName;
                $exame->type = $type;
                $exame->result = $result;
                $exame->date = $date;
    
                $xmlFile->asXML("src/xml/Exams.xml");

                header("Location: /sismed/exames/");
            } else {
                header("Location: /sismed/exames/");
            }
        }

        public function getExamByID(){
            $this->loadAllExams();

            if(isset($_POST['queryString'])) {
                $queryString = $_POST['queryString'];

                if(strlen($queryString) > 0) {
                    $key = array_search($queryString, array_column($this->exams, 'id'));
                    if($key === 0 || $key > 0) {
                        $exame = array(
                            "id" => $this->exams[$key]["id"],
                            "date"=> $this->exams[$key]["date"],
                            "patient" => $this->exams[$key]["patient"],
                            "patientID" => $this->exams[$key]["patientID"],
                            "type" => $this->exams[$key]["type"],
                            "result" => $this->exams[$key]["result"],
                        );
                        return json_encode($exame);
                    } 
                }
            }
            return null;
        }


        private function loadAllExams(){
            $xmlFile = simplexml_load_file("src/xml/Exams.xml");

            foreach ($xmlFile->children() as $exam) {

                array_push($this->exams, array(
                    "id" => $exam->id->__toString(),
                    "date" => $exam->date->__toString(),
                    "laboratory" => $exam->laboratory->__toString(),
                    "patient" => $exam->patient->__toString(),
                    "patientID" => $exam->patientID->__toString(),
                    "type" => $exam->type->__toString(),
                    "result" => $exam->result->__toString(),
                ));
            }  
        }

        private function loadAllExamsByLabID($labID){
            $xmlFile = simplexml_load_file("src/xml/Exams.xml");

            foreach ($xmlFile->children() as $exam) {
                if($exam->laboratoryID->__toString() === $labID){
                    array_push($this->exams, array(
                        "id" => $exam->id->__toString(),
                        "date" => $exam->date->__toString(),
                        "laboratory" => $exam->laboratory->__toString(),
                        "patient" => $exam->patient->__toString(),
                        "patientID" => $exam->patientID->__toString(),
                        "type" => $exam->type->__toString(),
                        "result" => $exam->result->__toString(),
                    ));
                }
            }  
        }
        private function loadAllExamsByPatientID($patientID){
            $xmlFile = simplexml_load_file("src/xml/Exams.xml");

            foreach ($xmlFile->children() as $exam) {
                if($exam->patientID->__toString() === $patientID){
                    array_push($this->exams, array(
                        "id" => $exam->id->__toString(),
                        "date" => $exam->date->__toString(),
                        "laboratory" => $exam->laboratory->__toString(),
                        "patient" => $exam->patient->__toString(),
                        "patientID" => $exam->patientID->__toString(),
                        "type" => $exam->type->__toString(),
                        "result" => $exam->result->__toString(),
                    ));
                }
            }  
        }
    }
