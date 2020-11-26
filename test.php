<?php

require_once "vendor/autoload.php";

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('phpLink');

$path  = dirname(__FILE__)."/log/app.log";
    
$log->pushHandler(new StreamHandler($path, Logger::DEBUG));

$file_exist = false;

while (1){

    $file_exist = file_exists($path);
    if(!$file_exist) {
        $log->pushHandler(new StreamHandler($path, Logger::DEBUG));
    }
    var_dump($file_exist);
    $file_exist = false;
    echo "logging \n";
    sleep(5);
    $log->notice("123",array("test"));

}

