<?php 
    
    require_once "src/Core.php";

    class PatientsController extends Core {

        private $patients = [];

        public function showPatientsView(){
            $this->loadAllPatients();    

            return $this->twig->render("PatientsView.twig", ["patients" => $this->patients]);
        }

        public function searchPatients(){
            $this->loadAllPatients();    

            if(isset($_POST['queryString'])) {
                $queryString = $_POST['queryString'];
                
                if(strlen($queryString) > 0) {
                    $filtered = array_filter($this->patients, function($value) use ($queryString){

                        return str_contains(strtolower($value["name"]), strtolower($queryString));
                    });
                    $formatted_filtered = [];
                    foreach ($filtered as $patient) {
                        array_push($formatted_filtered, [
                            "id" => $patient["id"],
                            "name"=> $patient["name"],
                            "address" => $patient["address"],
                            "phone" => $patient["phone"],
                            "email" => $patient["email"],
                            "gender" => $patient["gender"],
                            "age" => $patient["age"],
                            "cpf" => $patient["cpf"],
                        ]);
                    };
                    
                    return json_encode($formatted_filtered);
                }
            }
        }

        public function novoPaciente(){
            $this->loadAllPatients();
            $cpf = $_POST["cpf"]; //CHAVE ÚNICA
            $gender = $_POST["gender"];
            $age = $_POST["age"];
            $name = $_POST["name"];
            $email = $_POST["email"];
            $address = $_POST["address"];
            $phone = $_POST["phone"];
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);


            $key = array_search($cpf, array_column($this->patients, 'cpf'));
           
            if($key === 0 || $key > 0) {
                header("Location: /sismed/pacientes/");
            } else {
                $xmlFile = simplexml_load_file("src/xml/Patients.xml");
                
                $id = md5(uniqid(rand(), true));

                $lab = $xmlFile->addChild("patient");
                $lab->addChild("id", $id);
                $lab->addChild("cpf", $cpf);
                $lab->addChild("gender", $gender);
                $lab->addChild("age", $age);
                $lab->addChild("name", $name);
                $lab->addChild("email", $email);
                $lab->addChild("address", $address);
                $lab->addChild("phone", $phone);
    
                $xmlFile->asXML("src/xml/Patients.xml");

                $xmlFile_users = simplexml_load_file("src/xml/Users.xml");

                $user = $xmlFile_users->addChild("user");
                
                $user->addChild("id", $id);
                $user->addChild("email", $email);
                $user->addChild("password", $password);
                $user->addChild("role", 'patient');

                $xmlFile_users->asXML("src/xml/Users.xml");

                header("Location: /sismed/pacientes/");
            }

        }

        public function editarPaciente(){
            $this->loadAllPatients();
            $this->loadAllUsers();

            $cpf = $_POST["cpf-edit"]; //CHAVE ÚNICA
            $id = $_POST["patientID-edit"];
            $gender = $_POST["gender-edit"];
            $age = $_POST["age-edit"];
            $name = $_POST["name-edit"];
            $email = $_POST["email-edit"];
            $address = $_POST["address-edit"];
            $phone = $_POST["phone-edit"];

            $key = array_search($id, array_column($this->patients, 'id'));

            if($key === 0 || $key > 0) {
                $xmlFile = simplexml_load_file("src/xml/Patients.xml");

                $paciente = $xmlFile->patient[$key];

                $paciente->cpf = $cpf;
                $paciente->gender = $gender;
                $paciente->age = $age;
                $paciente->name = $name;
                $paciente->email = $email;
                $paciente->phone = $phone;
                $paciente->address = $address;

                $xmlFile->asXML("src/xml/Patients.xml");

                $key = array_search($id, array_column($this->users, 'id'));

                if($key === 0 || $key > 0) {
                    $xmlFile_users = simplexml_load_file("src/xml/Users.xml");
                    
                    $user = $xmlFile_users->user[$key];
                    
                    $user->email = $email;

                    $xmlFile_users->asXML("src/xml/Users.xml");

                }

                header("Location: /sismed/pacientes/");
            } else {
                header("Location: /sismed/pacientes/");
            }
        }

        public function getPacienteByID(){
            $this->loadAllPatients();

            if(isset($_POST['queryString'])) {
                $queryString = $_POST['queryString'];
                
                if(strlen($queryString) > 0) {
                    $key = array_search($queryString, array_column($this->patients, 'id'));
                    
                    if($key === 0 || $key > 0) {
                        $paciente = array(
                            "id" => $this->patients[$key]["id"],
                            "name"=> $this->patients[$key]["name"],
                            "address" => $this->patients[$key]["address"],
                            "phone" => $this->patients[$key]["phone"],
                            "email" => $this->patients[$key]["email"],
                            "cpf" => $this->patients[$key]["cpf"],
                            "gender" => $this->patients[$key]["gender"],
                            "age" => $this->patients[$key]["age"],
                        );

                        return json_encode($paciente);
                    } 
                }
            }
            return null;
        }

        private function loadAllPatients(){
            $dbPatients = $this->get("SELECT * FROM patients");

            foreach ($dbPatients as $patient) {
                array_push($this->patients, $patient);
            }
        }
        // private function loadAllUsers(){
        //     $xmlFile_users = simplexml_load_file("src/xml/Users.xml");

        //     foreach ($xmlFile_users->children() as $user) {

        //         array_push($this->users, array(
        //             "id" => $user->id->__toString(),
        //             "name" => $user->name->__toString(),
        //             "email" => $user->email->__toString(),
        //             "role" => $user->role->__toString(),
        //         ));
        //     } 
        // }
    }
