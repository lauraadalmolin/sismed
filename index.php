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

    $user_session = json_decode($_SESSION["user"], true) ?? null;    
    $user_role = $user_session["role"]; 

    $user_permissions = array(
        array(
            "role"=> "patient",
            "permissions"=> [
                "/sismed/",
                "/sismed/consultas/",
                "/sismed/exames/"
            ]
        ),
        array(
            "role"=> "doctor",
            "permissions"=> [
                "/sismed/",
                "/sismed/consultas/",
                "/sismed/consultas/searchPatients/",
                "/sismed/consultas/salvar/",
                "/sismed/consultas/editar/",
                "/sismed/consultas/buscar-by-id/",
            ]
        ),
        array(
            "role"=> "laboratorie",
            "permissions"=> [
                "/sismed/",
                "/sismed/exames/",
                "/sismed/exames/searchPatients/",
                "/sismed/exames/salvar/",
                "/sismed/exames/editar/",
                "/sismed/exames/buscar-by-id/",
            ]
        ),
        array(
            "role"=> "admin",
            "permissions"=> [
                "/sismed/",
                "/sismed/medicos/",
                "/sismed/medicos/salvar/",
                "/sismed/medicos/editar/",
                "/sismed/medicos/buscar-by-id/",
                "/sismed/pacientes/",
                "/sismed/pacientes/salvar/",
                "/sismed/pacientes/editar/",
                "/sismed/pacientes/buscar-by-id/",
                "/sismed/laboratorios/",
                "/sismed/laboratorios/salvar/",
                "/sismed/laboratorios/editar/",
                "/sismed/laboratorios/buscar-by-id/",
            ]
        ),
    );
    
    $core = new Core;
    
    if(isset($_SERVER["REQUEST_URI"])){
        $check = substr($_SERVER["REQUEST_URI"], -1);
        if($check !== "/"){
            $url = $_SERVER["REQUEST_URI"] . "/";
        } else {
            $url = $_SERVER["REQUEST_URI"];
        }
        
    } else {
        $url = "/sismed/";
    }

    if($user_session){
        $key = array_search($user_role, array_column($user_permissions, 'role'));

        $not_permissions = [
            '/sismed/login/',
            '/sismed/login/entrar/'
        ];

        if(in_array($url, $not_permissions) || !in_array($url, $user_permissions[$key]["permissions"] )){
            header("Location: /sismed/");
        } 
    } else {
        $permissions = [
            '/sismed/login/'
        ];
        if(!in_array($url, $permissions)){
            header("Location: /sismed/login/");
        }
    }
    $core->start($url);  
?>