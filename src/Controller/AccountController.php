<?php 
    
    require_once "src/Core.php";

    class AccountController extends Core {
        
        private $email;
        private $password;

        private $users = [];

        public function showLoginView(){
            $parameters["error"] = $_SESSION["msg_error"]["msg"] ?? null;
    
            return $this->twig->render("LoginView.twig", $parameters);

        }

        public function entrar(){
            $xmlFile = simplexml_load_file("src/xml/Users.xml");
            
            foreach ($xmlFile->children() as $user) {
                array_push($this->users, array(
                    "id" => $user->id,
                    "email" => $user->email,
                    "password" => $user->password,
                    "name" => $user->name,
                ));
            } 
            
            $this->email = $_POST["email"];
            $this->password = $_POST["password"];
            
            if(isset($_POST["btn-action"])){
                $key = array_search($this->email, array_column($this->users, 'email'));
                
                if(($key === 0 || $key > 0) && ($this->email == $this->users[$key]["email"])){
                    if(password_verify($this->password, $this->users[$key]["password"])){
                        $_SESSION["user"] = json_encode($this->users[$key]);
                        header("Location: /sismed/");
                    } else {
                        $_SESSION["msg_error"] = array("msg" => "Senha Inválida", "count" => 0);
                        header("Location: /sismed/login/");
                    }
                } else {
                    $_SESSION["msg_error"] = array("msg" => "Email Inválido", "count" => 0);
                   header("Location: /sismed/login/");
                }
            }
            
        }

        public function sair(){
            unset($this->user);
            session_destroy();
            header("Location: /sismed/login/");
        }
    }
?>