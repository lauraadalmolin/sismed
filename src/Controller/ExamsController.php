<?php 
    
    require_once "src/Core.php";

    class ExamsController extends Core {

        private $exams = [];
       
        public function showExamsView() {
            $idType = 'patientId';
            
            if ($this->user["role"] === "laboratorie"){
                $idType = 'laboratoryId';
            } 
            
            $userId = $this->user["id"];
            $dbData = $this->get("SELECT * FROM exams WHERE $idType='$userId';");

            foreach ($dbData as $exam) {
                array_push($this->exams, $exam);
            }

            $current_month = $this->get("SELECT COUNT(date) as result from exams where MONTH(date)=MONTH(now()) and YEAR(date)=YEAR(now()) and $idType='$userId';")[0]["result"];
            $current_year = $this->get("SELECT COUNT(date) as result from exams where MONTH(date)<=MONTH(now()) and YEAR(date)=YEAR(now()) and $idType='$userId';")[0]["result"];
            $avg_per_month = $current_year / date('m');

            $month = $this->getFullMonth();
    
            return $this->twig->render("ExamsView.twig", ["exams" => $this->exams, "current_month" => $current_month, "current_year" => $current_year, "avg_per_month" => $avg_per_month, "month" => $month]);
        }

        public function getFullMonth() {
            $months = ['Janeiro', 'Fevereiro','Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Setembro', 'Novembro', 'Dezembro'];
            return $months[date('m') - 1];
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
    }
