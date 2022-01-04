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
            $cpf = $_POST["cpf"]; //CHAVE ÚNICA
            $gender = $_POST["gender"];
            $age = $_POST["age"];
            $name = $_POST["name"];
            $email = $_POST["email"];
            $address = $_POST["address"];
            $phone = $_POST["phone"];
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

            $dbData = $this->get("SELECT * FROM patients WHERE cpf='$cpf'");
           
            if(count($dbData) > 0) {
                header("Location: /sismed/pacientes/");
            } else {                
                $id = md5(uniqid(rand(), true));

                $data = array('cpf' => $cpf,'id' => $id,'name' => $name,'email' => $email,'address' => $address,'phone' => $phone,'gender' => $gender,'age' => $age);
                $this->insert($data, 'INSERT INTO patients(id,cpf,name,email,address,phone,gender,age) VALUES (:id,:cpf,:name,:email,:address,:phone,:gender,:age);');
                $userData = array('id' => $id, 'name' => $name, 'email' => $email, 'password' => $password, 'role' => 'patient', );
                $this->insert($userData, "INSERT INTO users(id, name, email, password, role) VALUES(:id,:name,:email,:password,:role);");


                header("Location: /sismed/pacientes/");
            }

        }

        public function editarPaciente(){
            $cpf = $_POST["cpf-edit"]; //CHAVE ÚNICA
            $id = $_POST["patientID-edit"];
            $gender = $_POST["gender-edit"];
            $age = $_POST["age-edit"];
            $name = $_POST["name-edit"];
            $email = $_POST["email-edit"];
            $address = $_POST["address-edit"];
            $phone = $_POST["phone-edit"];

            $paciente = $this->get("SELECT * FROM patients WHERE id='$id'");
            
            if(count($paciente) > 0) {
                $data = array('cpf' => $cpf,'id' => $id,'name' => $name,'email' => $email,'address' => $address,'phone' => $phone,'gender' => $gender,'age' => $age);
                $this->update($data, 'UPDATE patients SET id=:id,cpf=:cpf,name=:name,email=:email,address=:address,phone=:phone,gender=:gender,age=:age WHERE id=:id');
                $data = array('email' => $email, 'id' => $id);
                $this->update($data, 'UPDATE users SET email=:email WHERE id=:id');  

                header("Location: /sismed/pacientes/");
            } else {
                header("Location: /sismed/pacientes/");
            }
        }

        public function getPacienteByID(){
            if(isset($_POST['queryString'])) {
                $queryString = $_POST['queryString'];
                
                if(strlen($queryString) > 0) {
                    $paciente = $this->get("SELECT * FROM patients WHERE id='$queryString'");
                    
                    if(count($paciente) > 0) {
                        $key = array_search($queryString, array_column($paciente, 'id'));

                        return json_encode($paciente[$key]);
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
    }
