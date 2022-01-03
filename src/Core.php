<?php

    use Twig_Loader_Filesystem;
    use Twig_Environment;
    require_once "Database.php";

    class Core extends Database {
        private $routes = array(
            array(
                'alias' => '/sismed/',
                'class' => 'IndexController',
                'method' => 'showHomeView',
            ),
            array(
                'alias' => '/sismed/medicos/',
                'class' => 'DoctorsController',
                'method' => 'showDoctorsView',
            ),
            array(
                'alias' => '/sismed/medicos/buscar-by-id/',
                'class' => 'DoctorsController',
                'method' => 'getDoctorByID',
            ),
            array(
                'alias' => '/sismed/medicos/salvar/',
                'class' => 'DoctorsController',
                'method' => 'novoMedico',
            ),
            array(
                'alias' => '/sismed/medicos/editar/',
                'class' => 'DoctorsController',
                'method' => 'editarMedico',
            ),
            array(
                'alias' => '/sismed/laboratorios/',
                'class' => 'LaboratoriesController',
                'method' => 'showLaboratoriesView',
            ),
            array(
                'alias' => '/sismed/laboratorios/salvar/',
                'class' => 'LaboratoriesController',
                'method' => 'novoLaboratorio',
            ),
            array(
                'alias' => '/sismed/laboratorios/editar/',
                'class' => 'LaboratoriesController',
                'method' => 'editarLaboratorio',
            ),
            array(
                'alias' => '/sismed/laboratorios/buscar-by-id/',
                'class' => 'LaboratoriesController',
                'method' => 'getLaboratoryByID',
            ),
            array(
                'alias' => '/sismed/exames/',
                'class' => 'ExamsController',
                'method' => 'showExamsView',
            ),
            array(
                'alias' => '/sismed/exames/salvar/',
                'class' => 'ExamsController',
                'method' => 'novoExame',
            ),
            array(
                'alias' => '/sismed/exames/editar/',
                'class' => 'ExamsController',
                'method' => 'editarExame',
            ),
            array(
                'alias' => '/sismed/exames/buscar-by-id/',
                'class' => 'ExamsController',
                'method' => 'getExamByID',
            ),
            array(
                'alias' => '/sismed/exames/searchPatients/',
                'class' => 'PatientsController',
                'method' => 'searchPatients',
            ),
            array(
                'alias' => '/sismed/pacientes/',
                'class' => 'PatientsController',
                'method' => 'showPatientsView',
            ),
            array(
                'alias' => '/sismed/pacientes/salvar/',
                'class' => 'PatientsController',
                'method' => 'novoPaciente',
            ),
            array(
                'alias' => '/sismed/pacientes/editar/',
                'class' => 'PatientsController',
                'method' => 'editarPaciente',
            ),
            array(
                'alias' => '/sismed/pacientes/buscar-by-id/',
                'class' => 'PatientsController',
                'method' => 'getPacienteByID',
            ),
            
            array(
                'alias' => '/sismed/consultas/',
                'class' => 'MedicalAppointmentsController',
                'method' => 'showMedicalAppointmentsView',
            ),
            array(
                'alias' => '/sismed/consultas/searchPatients/',
                'class' => 'PatientsController',
                'method' => 'searchPatients',
            ),
            array(
                'alias' => '/sismed/consultas/salvar/',
                'class' => 'MedicalAppointmentsController',
                'method' => 'novaConsulta',
            ),
            array(
                'alias' => '/sismed/consultas/editar/',
                'class' => 'MedicalAppointmentsController',
                'method' => 'editarConsulta',
            ),
            array(
                'alias' => '/sismed/consultas/buscar-by-id/',
                'class' => 'MedicalAppointmentsController',
                'method' => 'getConsultaByID',
            ),
            array(
                'alias' => '/sismed/login/',
                'class' => 'AccountController',
                'method' => 'showLoginView',
            ),
            array(
                'alias' => '/sismed/login/entrar/',
                'class' => 'AccountController',
                'method' => 'entrar',
            ),
            array(
                'alias' => '/sismed/login/sair/',
                'class' => 'AccountController',
                'method' => 'sair',
            ),
        );

        private $controller = "";
        private $method = "";

        protected $twig;
        private $loader;

        protected $user;
        private $error;


        public function __construct() {
            $this->loader = new \Twig\Loader\FilesystemLoader('src/View');
            $this->twig = new \Twig\Environment($this->loader);
            try {
                $this->connection_start();
            } catch (PDOException $e) {
                echo $e->getMessage();
                throw new Exception("Error Processing Request", 1);
            }
            $user_session = json_decode($_SESSION["user"], true) ?? null;
            
            if(isset($user_session)){
                $this->user = array(
                    "name" => $user_session["name"][0],
                    "id" => $user_session["id"][0],
                    "role" => $user_session["role"][0],
                    "email" => $user_session["email"][0],
                );
                
                $initials = $this->getInitialsName($this->user["name"]);
                
                $this->twig->addGlobal("initials", $initials);
                $this->twig->addGlobal("role", $this->user["role"]);
            }
        }

        public function start ($url) {
            foreach ($this->routes as $route) {
                if($route["alias"] === $url ){
                    $this->controller = $route["class"];
                    $this->method = $route["method"];
                    break;
                }
            }
            
            echo call_user_func(array(new $this->controller, $this->method));
        }

        private function getInitialsName($name){
            $exp = explode(" ", $name); 

            
            if(count($exp) >= 2){
                $initials = $exp[0][0].$exp[1][0];
            } else {
                $initials = $name[0];
            }
            return $initials;
        }

        // protected function insertLab($lab){
        //     $sql_query = "INSERT INTO laboratories(id,cnpj,name,email,address,phone) VALUES(:id,:cnpj,:name,:email,:address,:phone);";
        //     $stmt = $this->connection->prepare($sql_query);
        //     $stmt->bindParam(":id", $lab['id']);
        //     $stmt->bindParam(":cnpj", $lab['cnpj']);
        //     $stmt->bindParam(":name", $lab['name']);
        //     $stmt->bindParam(":email", $lab['email']);
        //     $stmt->bindParam(":address", $lab['address']);
        //     $stmt->bindParam(":phone", $lab['phone']);

        //     $stmt->execute();
        // }
    }
?>