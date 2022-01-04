<?php 
    
    require_once "src/Core.php";

    class AccountController extends Core {
        
        private $email;
        private $password;

        public function showLoginView(){
            $parameters["error"] = $_SESSION["msg_error"]["msg"] ?? null;
    
            return $this->twig->render("LoginView.twig", $parameters);

        }

        public function entrar(){
            $this->email = $_POST["email"];
            $this->password = $_POST["password"];
            
            if(isset($_POST["btn-action"])){
                $user = $this->get("SELECT * FROM users WHERE email='$this->email'");
                $key = array_search($this->email, array_column($user, 'email'));
                if(($key === 0 || $key > 0) && ($this->email == $user[$key]["email"])){
                    if(password_verify($this->password, $user[$key]["password"])){
                        $_SESSION["user"] = json_encode($user[$key]);
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