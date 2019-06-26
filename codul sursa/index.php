<?php

require_once('routes.php');

function __autoload($class_name) {
    if(file_exists('./classes/'.$class_name.'.php')){
        require_once './classes/'.$class_name.'.php';
    }else if(file_exists('./controllers/'.$class_name.'.php')){
        require_once './controllers/'.$class_name.'.php';
    }else if(file_exists('./controllers/student/'.$class_name.'.php')){
        require_once './controllers/student/'.$class_name.'.php';
    }else if(file_exists('./controllers/professor/'.$class_name.'.php')){
        require_once './controllers/professor/'.$class_name.'.php';
    }else if(file_exists('./controllers/admin/'.$class_name.'.php')){
        require_once './controllers/admin/'.$class_name.'.php';
    }
}

?>
