<?php 
    
    require_once "src/Core.php";

    class DoctorsController extends Core {
        
        private $doctors = [];
        private $users = [];

        public function showDoctorsView(){
            $this->loadAllDoctors();
            
            return $this->twig->render("DoctorsView.twig", ["doctors" => $this->doctors]);
        }

        public function novoMedico(){
            $this->loadAllDoctors();
            $crm = $_POST["crm"]; //CHAVE ÚNICA
            $specialty = $_POST["specialty"];
            $name = $_POST["name"];
            $email = $_POST["email"];
            $address = $_POST["address"];
            $phone = $_POST["phone"];
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
            
            $key = array_search($crm, array_column($this->doctors, 'crm'));
            
            if($key === 0 || $key > 0) {
                header("Location: /sismed/medicos/");
            } else {
                $xmlFile = simplexml_load_file("src/xml/Doctors.xml");
                
                $id = md5(uniqid(rand(), true));
                
                $doc = $xmlFile->addChild("doctor");
                $doc->addChild("id", $id);
                $doc->addChild("crm", $crm);
                $doc->addChild("specialty", $specialty);
                $doc->addChild("name", $name);
                $doc->addChild("email", $email);
                $doc->addChild("address", $address);
                $doc->addChild("phone", $phone);
                
                $xmlFile->asXML("src/xml/Doctors.xml");

                $xmlFile_users = simplexml_load_file("src/xml/Users.xml");

                $user = $xmlFile_users->addChild("user");
                
                $user->addChild("id", $id);
                $user->addChild("name", $name);
                $user->addChild("email", $email);
                $user->addChild("password", $password);
                $user->addChild("role", 'doctor');

                $xmlFile_users->asXML("src/xml/Users.xml");

                header("Location: /sismed/medicos/");
            }

        }

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
            $xmlFile = simplexml_load_file("src/xml/Doctors.xml");

            foreach ($xmlFile->children() as $doctor) {

                array_push($this->doctors, array(
                    "id" => $doctor->id->__toString(),
                    "crm" => $doctor->crm->__toString(),
                    "name" => $doctor->name->__toString(),
                    "address" => $doctor->address->__toString(),
                    "phone" => $doctor->phone->__toString(),
                    "email" => $doctor->email->__toString(),
                    "specialty" => $doctor->specialty->__toString(),
                ));
            }  
        }

        private function loadAllUsers(){
            $xmlFile_users = simplexml_load_file("src/xml/Users.xml");

            foreach ($xmlFile_users->children() as $user) {

                array_push($this->users, array(
                    "id" => $user->id->__toString(),
                    "name" => $user->name->__toString(),
                    "email" => $user->email->__toString(),
                    "role" => $user->role->__toString(),
                ));
            } 
        }
    }
?>