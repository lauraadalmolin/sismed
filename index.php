<?php 
    session_start();
    require_once "src/Core.php";
    require_once 'vendor/autoload.php';
    
    require_once "src/Controller/IndexController.php";
    require_once "src/Controller/DoctorsController.php";
    require_once "src/Controller/LaboratoriesController.php";
    require_once "src/Controller/ExamsController.php";
    require_once "src/Controller/PatientsController.php";
    require_once "src/Controller/MedicalAppointmentsController.php";
    require_once "src/Controller/AccountController.php";

    $user_session = $_SESSION["user"] ?? null;
    $user_role = "admin"; //DESENVOLVER ESSA PARTE DE SALVAR A FUNÇÂO NA SESSÃO
    
    $user_permissions = array(
        array(
            "role"=> "patient",
            "permissions"=> [
                "/",
                "consultas/",
                "exames/"
            ]
        ),
        array(
            "role"=> "doctor",
            "permissions"=> [
                "/",
                "consultas/",
                "consultas/searchPatients/",
                "consultas/salvar/",
            ]
        ),
        array(
            "role"=> "admin",
            "permissions"=> [
                "/",
                "medicos/",
                "medicos/salvar/",
                "medicos/editar/",
                "medicos/buscar-by-id/",
                "pacientes/",
                "pacientes/salvar/",
                "pacientes/editar/",
                "pacientes/buscar-by-id/",
                "laboratorios/",
                "laboratorios/salvar/",
                "laboratorios/editar/",
                "laboratorios/buscar-by-id/",
            ]
        ),
    );
    
    $core = new Core;
    
    
    if(isset($_GET["url"])){
        $check = substr($_GET["url"], -1);
        if($check !== "/"){
            $url = $_GET["url"] . "/";
        } else {
            $url = $_GET["url"];
        }
        
    } else {
        $url = "/";
    }

    if($user_session){
        $key = array_search($user_role, array_column($user_permissions, 'role'));

        $not_permissions = [
            'login/',
            'login/entrar/'
        ];

        if(in_array($url, $not_permissions) || !in_array($url, $user_permissions[$key]["permissions"] )){
            header("Location: /sismed/");
        } 
    } else {
        $permissions = [
            'login/'
        ];
        if(!in_array($url, $permissions)){
            header("Location: /sismed/login/");
        }
    }

    $core->start($url);
?>