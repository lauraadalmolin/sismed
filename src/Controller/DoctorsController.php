<?php 
    
    require_once "src/Core.php";

    class DoctorsController extends Core {
        
        private $doctors = [];

        public function showDoctorsView(){
            $this->loadAllDoctors();
            
            return $this->twig->render("DoctorsView.twig", ["doctors" => $this->doctors]);
        }


        public function novoMedico(){
            $crm = $_POST["crm"]; //CHAVE ÚNICA
            $specialty = $_POST["specialty"];
            $name = $_POST["name"];
            $email = $_POST["email"];
            $address = $_POST["address"];
            $phone = $_POST["phone"];
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
            
            $dbData = $this->get("SELECT * FROM doctors WHERE crm='$crm'");
            
            if(count($dbData) > 0) {
                header("Location: /sismed/medicos/");
            } else {                
                $id = md5(uniqid(rand(), true));
                
                $data = array('id' => $id, 'crm' => $crm, 'name' => $name, 'email' => $email, 'address' => $address, 'phone' => $phone, 'specialty' => $specialty);
                $this->insert($data, 'INSERT INTO doctors(id,crm,name,email,address,phone,specialty) VALUES (:id,:crm,:name,:email,:address,:phone,:specialty);');
                $userData = array('id' => $id, 'name' => $name, 'email' => $email, 'password' => $password, 'role' => 'doctor', );
                $this->insert($userData, "INSERT INTO users(id, name, email, password, role) VALUES(:id,:name,:email,:password,:role);");
                header("Location: /sismed/medicos/");
            }

        }
        
        public function editarMedico(){
            $crm = $_POST["crm-edit"]; //CHAVE ÚNICA
            $id = $_POST["doctorID-edit"];
            $specialty = $_POST["specialty-edit"];
            $name = $_POST["name-edit"];
            $email = $_POST["email-edit"];
            $address = $_POST["address-edit"];
            $phone = $_POST["phone-edit"];
            
            $doc = $this->get("SELECT * FROM doctors WHERE id='$id'");
            
            if(count($doc) > 0) {
                $data = array('crm' => $crm,'id' => $id,'specialty' => $specialty,'name' => $name,'email' => $email,'address' => $address,'phone' => $phone);
                $this->update($data, 'UPDATE doctors SET id=:id,crm=:crm,name=:name,specialty=:specialty,email=:email,address=:address,phone=:phone WHERE id=:id');
                $data = array('email' => $email, 'id' => $id);
                $this->update($data, 'UPDATE users SET email=:email WHERE id=:id');
                header("Location: /sismed/medicos/");
            } else {
                header("Location: /sismed/medicos/");
            }

        }

        public function getDoctorByID(){

            if(isset($_POST['queryString'])) {
                $queryString = $_POST['queryString'];
                
                if(strlen($queryString) > 0) {
                    $doc = $this->get("SELECT * FROM doctors WHERE id='$queryString'");
                    if(count($doc) > 0) {
                        $key = array_search($queryString, array_column($doc, 'id'));
                        return json_encode($doc[$key]);
                    } 
                }
            }
            return null;
        }

        private function loadAllDoctors(){
            $dbDoctors = $this->get("SELECT * FROM doctors");

            foreach ($dbDoctors as $doctor) {
                array_push($this->doctors, $doctor);
            }

        }
    }
?>