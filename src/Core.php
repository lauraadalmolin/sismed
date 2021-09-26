<?php

    use Twig_Loader_Filesystem;
    use Twig_Environment;

    class Core {
        private $routes = array(
            array(
                'alias' => '/',
                'class' => 'IndexController',
                'method' => 'showHomeView',
            ),
            array(
                'alias' => 'medicos/',
                'class' => 'DoctorsController',
                'method' => 'showDoctorsView',
            ),
            array(
                'alias' => 'medicos/buscar-by-id/',
                'class' => 'DoctorsController',
                'method' => 'getDoctorByID',
            ),
            array(
                'alias' => 'medicos/salvar/',
                'class' => 'DoctorsController',
                'method' => 'novoMedico',
            ),
            array(
                'alias' => 'medicos/editar/',
                'class' => 'DoctorsController',
                'method' => 'editarMedico',
            ),
            array(
                'alias' => 'laboratorios/',
                'class' => 'LaboratoriesController',
                'method' => 'showLaboratoriesView',
            ),
            array(
                'alias' => 'laboratorios/salvar/',
                'class' => 'LaboratoriesController',
                'method' => 'novoLaboratorio',
            ),
            array(
                'alias' => 'laboratorios/editar/',
                'class' => 'LaboratoriesController',
                'method' => 'editarLaboratorio',
            ),
            array(
                'alias' => 'laboratorios/buscar-by-id/',
                'class' => 'LaboratoriesController',
                'method' => 'getLaboratoryByID',
            ),
            array(
                'alias' => 'exames/',
                'class' => 'ExamsController',
                'method' => 'showExamsView',
            ),
            array(
                'alias' => 'pacientes/',
                'class' => 'PatientsController',
                'method' => 'showPatientsView',
            ),
            array(
                'alias' => 'pacientes/salvar/',
                'class' => 'PatientsController',
                'method' => 'novoPaciente',
            ),
            array(
                'alias' => 'pacientes/editar/',
                'class' => 'PatientsController',
                'method' => 'editarPaciente',
            ),
            array(
                'alias' => 'pacientes/buscar-by-id/',
                'class' => 'PatientsController',
                'method' => 'getPacienteByID',
            ),
            
            array(
                'alias' => 'consultas/',
                'class' => 'MedicalAppointmentsController',
                'method' => 'showMedicalAppointmentsView',
            ),
            array(
                'alias' => 'consultas/searchPatients/',
                'class' => 'PatientsController',
                'method' => 'searchPatients',
            ),
            array(
                'alias' => 'consultas/salvar/',
                'class' => 'MedicalAppointmentsController',
                'method' => 'novaConsulta',
            ),
            array(
                'alias' => 'login/',
                'class' => 'AccountController',
                'method' => 'showLoginView',
            ),
            array(
                'alias' => 'login/entrar/',
                'class' => 'AccountController',
                'method' => 'entrar',
            ),
            array(
                'alias' => 'login/sair/',
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

            $this->user = array(
                "name" => "Talita Pastorini",
                "id" => 'f963b064b23370d4aae4c392193ffeb9',
                "role" => 'admin',
                "email" => 'talitapastorini@gmail.com',
            );
            
            $initials = $this->getInitialsName($this->user["name"]);
            
            $this->twig->addGlobal("initials", $initials);
            $this->twig->addGlobal("role", $this->user["role"]);
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

        // protected function setUser($data){
        //     echo"_--------------------setUser";
        //     $this->user = json_encode($data);
        //     var_dump($this->user);
        //     $initials = $this->getInitialsName($this->user["name"]);
        //     $this->twig->addGlobal("initials", $initials);
        // }

        private function getInitialsName($name){
            // FAZER A FUNÇẪO QUE RECEBE O NOME E RETORNAR AS INICIAIS
            return "TP";
        }
    }
?>