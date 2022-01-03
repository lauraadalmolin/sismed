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

        // public function novoMedico(){
        //     $this->loadAllDoctors();
        //     $crm = $_POST["crm"]; //CHAVE ÚNICA
        //     $specialty = $_POST["specialty"];
        //     $name = $_POST["name"];
        //     $email = $_POST["email"];
        //     $address = $_POST["address"];
        //     $phone = $_POST["phone"];
        //     $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
            
        //     $key = array_search($crm, array_column($this->doctors, 'crm'));
            
        //     if($key === 0 || $key > 0) {
        //         header("Location: /sismed/medicos/");
        //     } else {
        //         $xmlFile = simplexml_load_file("src/xml/Doctors.xml");
                
        //         $id = md5(uniqid(rand(), true));
                
        //         $doc = $xmlFile->addChild("doctor");
        //         $doc->addChild("id", $id);
        //         $doc->addChild("crm", $crm);
        //         $doc->addChild("specialty", $specialty);
        //         $doc->addChild("name", $name);
        //         $doc->addChild("email", $email);
        //         $doc->addChild("address", $address);
        //         $doc->addChild("phone", $phone);
                
        //         $xmlFile->asXML("src/xml/Doctors.xml");

        //         $xmlFile_users = simplexml_load_file("src/xml/Users.xml");

        //         $user = $xmlFile_users->addChild("user");
                
        //         $user->addChild("id", $id);
        //         $user->addChild("name", $name);
        //         $user->addChild("email", $email);
        //         $user->addChild("password", $password);
        //         $user->addChild("role", 'doctor');

        //         $xmlFile_users->asXML("src/xml/Users.xml");

        //         header("Location: /sismed/medicos/");
        //     }

        // }

        public function editarMedico(){
            $this->loadAllDoctors();
            $this->loadAllUsers();

            $crm = $_POST["crm-edit"]; //CHAVE ÚNICA
            $id = $_POST["doctorID-edit"];
            $specialty = $_POST["specialty-edit"];
            $name = $_POST["name-edit"];
            $email = $_POST["email-edit"];
            $address = $_POST["address-edit"];
            $phone = $_POST["phone-edit"];
            
            $key = array_search($id, array_column($this->doctors, 'id'));
            
            if($key === 0 || $key > 0) {
                $xmlFile = simplexml_load_file("src/xml/Doctors.xml");
    
                $doc = $xmlFile->doctor[$key];
                $doc->crm = $crm;
                $doc->name = $name;
                $doc->email = $email;
                $doc->phone = $phone;
                $doc->address = $address;
                $doc->specialty = $specialty;
    
                $xmlFile->asXML("src/xml/Doctors.xml");

                $key = array_search($id, array_column($this->users, 'id'));

                if($key === 0 || $key > 0) {
                    $xmlFile_users = simplexml_load_file("src/xml/Users.xml");
                    
                    $user = $xmlFile_users->user[$key];
                    
                    $user->email = $email;

                    $xmlFile_users->asXML("src/xml/Users.xml");

                }

                header("Location: /sismed/medicos/");
            } else {
                header("Location: /sismed/medicos/");
            }

        }

        public function getDoctorByID(){
            $this->loadAllDoctors();

            if(isset($_POST['queryString'])) {
                $queryString = $_POST['queryString'];
                
                if(strlen($queryString) > 0) {
                    $key = array_search($queryString, array_column($this->doctors, 'id'));
                    
                    if($key === 0 || $key > 0) {
                        $doc = array(
                            "id" => $this->doctors[$key]["id"],
                            "name"=> $this->doctors[$key]["name"],
                            "address" => $this->doctors[$key]["address"],
                            "phone" => $this->doctors[$key]["phone"],
                            "email" => $this->doctors[$key]["email"],
                            "crm" => $this->doctors[$key]["crm"],
                            "specialty" => $this->doctors[$key]["specialty"],
                        );

                        return json_encode($doc);
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