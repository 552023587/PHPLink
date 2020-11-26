<?php

define("APP_PATH",dirname(__FILE__));

require_once  APP_PATH."/class/Redis.php";
require_once  APP_PATH."/class/Request.php";
require_once  APP_PATH."/class/LinkData.php";
require_once  APP_PATH."/class/Log.php";
require_once  APP_PATH.'/class/App.php';


$App = new PHPLink();

if(count($argv) > 1) {
    if($argv[1] == "--stop") {
        $App->stop();
    }else if($argv[1] == "--start") {
        $App->start();
    }
}else{
    exit();
}



