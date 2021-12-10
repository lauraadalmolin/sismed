<?php 
    
    require_once "src/Core.php";

    class LaboratoriesController extends Core {

        private $laboratories = [];
        private $users = [];

        public function showLaboratoriesView(){
            $this->loadAllLaboratories();

            return $this->twig->render("LaboratoriesView.twig", ["laboratories" => $this->laboratories]);
        }

        public function novoLaboratorio(){
            $this->loadAllLaboratories();
            $cnpj = $_POST["cnpj"]; //CHAVE ÚNICA
            $name = $_POST["name"];
            $email = $_POST["email"];
            $address = $_POST["address"];
            $phone = $_POST["phone"];
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);


            $key = array_search($cnpj, array_column($this->laboratories, 'cnpj'));
       
            if($key === 0 || $key > 0) {
                header("Location: /sismed/laboratorios/");

            } else {
                $xmlFile = simplexml_load_file("src/xml/Laboratories.xml");
                
                $id = md5(uniqid(rand(), true));

                $lab = $xmlFile->addChild("laboratory");
                $lab->addChild("id", $id);
                $lab->addChild("cnpj", $cnpj);
                $lab->addChild("name", $name);
                $lab->addChild("email", $email);
                $lab->addChild("address", $address);
                $lab->addChild("phone", $phone);
    
                $xmlFile->asXML("src/xml/Laboratories.xml");

                $xmlFile_users = simplexml_load_file("src/xml/Users.xml");

                $user = $xmlFile_users->addChild("user");
                
                $user->addChild("id", $id);
                $user->addChild("email", $email);
                $user->addChild("password", $password);
                $user->addChild("role", 'laboratorie');

                $xmlFile_users->asXML("src/xml/Users.xml");

                $lab = array('id' => $id, 'cnpj' => $cnpj, 'name' => $name, 'email' => $email, 'address' => $address, 'phone' => $phone);
                $this->insertLab($lab);
                header("Location: /sismed/laboratorios/");
            }

        }

        public function editarLaboratorio(){
            $this->loadAllLaboratories();
            $this->loadAllUsers();

            $cnpj = $_POST["cnpj-edit"]; //CHAVE ÚNICA
            $id = $_POST["labID-edit"];
            $name = $_POST["name-edit"];
            $email = $_POST["email-edit"];
            $address = $_POST["address-edit"];
            $phone = $_POST["phone-edit"];

            $key = array_search($id, array_column($this->laboratories, 'id'));

            if($key === 0 || $key > 0) {
                $xmlFile = simplexml_load_file("src/xml/Laboratories.xml");
                
                $lab = $xmlFile->laboratory[$key];

                $lab->cnpj = $cnpj;
                $lab->name = $name;
                $lab->email = $email;
                $lab->address = $address;
                $lab->phone = $phone;
    
                $xmlFile->asXML("src/xml/Laboratories.xml");

                $key = array_search($id, array_column($this->users, 'id'));

                if($key === 0 || $key > 0) {
                    $xmlFile_users = simplexml_load_file("src/xml/Users.xml");
                    
                    $user = $xmlFile_users->user[$key];
                    
                    $user->email = $email;

                    $xmlFile_users->asXML("src/xml/Users.xml");

                }

                header("Location: /sismed/laboratorios/");

            } else {

                header("Location: /sismed/laboratorios/");
            }

        }

        public function getLaboratoryByID(){
            $this->loadAllLaboratories();

            if(isset($_POST['queryString'])) {
                $queryString = $_POST['queryString'];
                
                if(strlen($queryString) > 0) {
                    $key = array_search($queryString, array_column($this->laboratories, 'id'));
                    
                    if($key === 0 || $key > 0) {
                        $lab = array(
                            "id" => $this->laboratories[$key]["id"],
                            "name"=> $this->laboratories[$key]["name"],
                            "address" => $this->laboratories[$key]["address"],
                            "phone" => $this->laboratories[$key]["phone"],
                            "email" => $this->laboratories[$key]["email"],
                            "cnpj" => $this->laboratories[$key]["cnpj"],
                        );

                        return json_encode($lab);
                    } 
                }
            }
            return null;
        }

        private function loadAllLaboratories(){
            $xmlFile = simplexml_load_file("src/xml/Laboratories.xml");

            foreach ($xmlFile->children() as $laboratory) {

                array_push($this->laboratories, array(
                    "id" => $laboratory->id->__toString(),
                    "cnpj" => $laboratory->cnpj->__toString(),
                    "name" => $laboratory->name->__toString(),
                    "address" => $laboratory->address->__toString(),
                    "phone" => $laboratory->phone->__toString(),
                    "email" => $laboratory->email->__toString(),
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