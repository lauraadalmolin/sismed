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
                'alias' => 'medicos',
                'class' => 'DoctorsController',
                'method' => 'showDoctorsView',
            ),
            array(
                'alias' => 'laboratorios',
                'class' => 'LaboratoriesController',
                'method' => 'showLaboratoriesView',
            ),
            array(
                'alias' => 'exames',
                'class' => 'ExamsController',
                'method' => 'showExamsView',
            ),
            array(
                'alias' => 'pacientes',
                'class' => 'PatientsController',
                'method' => 'showPatientsView',
            ),

            array(
                'alias' => 'consultas',
                'class' => 'MedicalAppointmentsController',
                'method' => 'showMedicalAppointmentsView',
            ),
        );

        private $controller = "";
        private $method = "";

        protected $twig;
        private $loader;


        public function __construct() {
            $this->loader = new \Twig\Loader\FilesystemLoader('src/View');
            $this->twig = new \Twig\Environment($this->loader);
        }

        public function start ($request) {
            if($_SERVER['REQUEST_URI'] !== '/'){
                $url = str_replace('/', '', $_SERVER['REQUEST_URI']);
                foreach ($this->routes as $route) {
                    if($route["alias"] === $url ){
                        $this->controller = $route["class"];
                        $this->method = $route["method"];
                        break;
                    }
                }
            } else {
                $this->controller = "IndexController";
                $this->method = "showHomeView";
            }

            echo call_user_func(array(new $this->controller, $this->method));
        }
    }
?>