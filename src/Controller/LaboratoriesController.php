<?php 
    
    require_once "src/Core.php";

    class LaboratoriesController extends Core {

        private $laboratories = [];

        public function showLaboratoriesView(){
            $this->loadAllLaboratories();

            return $this->twig->render("LaboratoriesView.twig", ["laboratories" => $this->laboratories]);
        }

        public function novoLaboratorio(){
            $cnpj = $_POST["cnpj"]; //CHAVE ÚNICA
            $name = $_POST["name"];
            $email = $_POST["email"];
            $address = $_POST["address"];
            $phone = $_POST["phone"];
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);


            $dbData = $this->get("SELECT * FROM laboratories WHERE cnpj='$cnpj'");
       
            if(count($dbData)) {
                header("Location: /sismed/laboratorios/");

            } else {                
                $id = md5(uniqid(rand(), true));

                $data = array('cnpj' => $cnpj,'id' => $id,'name' => $name,'email' => $email,'address' => $address,'phone' => $phone);
                $this->insert($data, 'INSERT INTO laboratories(id,cnpj,name,email,address,phone) VALUES (:id,:cnpj,:name,:email,:address,:phone);');
                $userData = array('id' => $id, 'name' => $name, 'email' => $email, 'password' => $password, 'role' => 'laboratorie', );
                $this->insert($userData, "INSERT INTO users(id, name, email, password, role) VALUES(:id,:name,:email,:password,:role);");

                header("Location: /sismed/laboratorios/");
            }

        }

        public function editarLaboratorio(){
            $cnpj = $_POST["cnpj-edit"]; //CHAVE ÚNICA
            $id = $_POST["labID-edit"];
            $name = $_POST["name-edit"];
            $email = $_POST["email-edit"];
            $address = $_POST["address-edit"];
            $phone = $_POST["phone-edit"];

            $lab = $this->get("SELECT * FROM laboratories WHERE id='$id'");

            if(count($lab) > 0) {
                $data = array('cnpj' => $cnpj,'id' => $id,'name' => $name,'email' => $email,'address' => $address,'phone' => $phone);
                $this->update($data, 'UPDATE laboratories SET id=:id,cnpj=:cnpj,name=:name,email=:email,address=:address,phone=:phone WHERE id=:id');
                $data = array('email' => $email, 'id' => $id);
                $this->update($data, 'UPDATE users SET email=:email WHERE id=:id');               
                
                header("Location: /sismed/laboratorios/");

            } else {

                header("Location: /sismed/laboratorios/");
            }

        }

        public function getLaboratoryByID(){
            if(isset($_POST['queryString'])) {
                $queryString = $_POST['queryString'];
                
                if(strlen($queryString) > 0) {
                    $lab = $this->get("SELECT * FROM laboratories WHERE id='$queryString'");
                    if(count($lab) > 0) {
                        $key = array_search($queryString, array_column($lab, 'id'));
                        return json_encode($lab[$key]);
                    } 
                }
            }
            return null;
        }

        private function loadAllLaboratories(){
            $dbLabs = $this->get("SELECT * FROM laboratories");

            foreach ($dbLabs as $lab) {
                array_push($this->laboratories, $lab);
            }
        }

    }
    
?>