<?php 
    require_once "src/Core.php";
    require_once 'vendor/autoload.php';
    
    require_once "src/Controller/IndexController.php";
    require_once "src/Controller/DoctorsController.php";
    require_once "src/Controller/LaboratoriesController.php";
    require_once "src/Controller/ExamsController.php";
    require_once "src/Controller/PatientsController.php";
    require_once "src/Controller/MedicalAppointmentsController.php";

    $core = new Core;
    $core->start($_GET);

?>