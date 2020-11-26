<?php

require_once APP_PATH."/vendor/autoload.php";

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class Log
{

    protected  $log;
    protected  $path;

    public function __construct()
    {
        $this->log = new Logger('phpLink');
        $this->checkPath();
        $this->log->pushHandler(new StreamHandler($this->path, Logger::DEBUG));
    }
    public function warning($message,$context = [])
    {
        $this->checkPath();
        $this->log->warning($message,$context);
    }
    public function error($message,$context = [])
    {
        $this->checkPath();
        $this->log->error($message,$context);
    }
    public function notice($message,$context = [])
    {
        $this->checkPath();
        $this->log->notice($message,$context);
    }
    public function checkPath()
    {
        $this->path = APP_PATH."/log".DIRECTORY_SEPARATOR.date("Ymd").".log";
        $file_exist = file_exists($this->path);
        if($file_exist == false) {
            $fileHandle = fopen($this->path, "ab+");
            fwrite($fileHandle,date("Ymd")."\n");
            fclose($fileHandle);
            $this->log->pushHandler(new StreamHandler($this->path, Logger::DEBUG));
        }
    }
}