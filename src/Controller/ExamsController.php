<?php 
    
    require_once "src/Core.php";

    class ExamsController extends Core {

        private $exams = [];

        public function showExamsView(){
            $xmlFile = simplexml_load_file("src/xml/Exams.xml");

            foreach ($xmlFile->children() as $exam) {

                array_push($this->exams, array(
                    "date" => $exam->date,
                    "laboratory" => $exam->laboratory,
                    "patient" => $exam->patient,
                    "type" => $exam->type,
                    "result" => $exam->result,
                ));
            }  

            return $this->twig->render("ExamsView.twig", ["exams" => $this->exams]);
        }

    }
