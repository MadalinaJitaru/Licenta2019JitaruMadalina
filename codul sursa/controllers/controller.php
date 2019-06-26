<?php

class Controller {
    public static function CreateView($viewName){
        if(file_exists("./pages/student/$viewName.php")){
            require_once "./pages/student/$viewName.php";
        }else
        if(file_exists("./pages/professor/$viewName.php")){
            require_once "./pages/professor/$viewName.php";
        }else
        if(file_exists("./pages/admin/$viewName.php")){
            require_once "./pages/admin/$viewName.php";
        }
    }
}

?>