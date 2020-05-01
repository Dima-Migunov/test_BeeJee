<?php

class Route {
    function __construct()
    {
        $this->post();

        if(array_key_exists('exit', $_GET)) {
            Controller::adminExit();
        }

        if(isset($_GET['delete'])) {
            Controller::taskDelete();
        }

        Controller::index();
    }

    private function post()
    {
        // проверка на безопасность
        if(!isset($_POST['_token'])) {
            return;
        }

        // Входит админ ?
        if(isset($_POST['login'])) {
            return Controller::adminLogin();
        }
    
        Controller::createUpdateTask();
    }
}
