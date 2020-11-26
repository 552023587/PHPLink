<?php


class Request {


    protected  $set;
    protected  $host;
    protected  $header;
    protected  $authKey;
    protected  $port;

    public  function __construct($host,$port,$header,$set,$authKey)
    {
            $this->host = $host;
            $this->port = $port;
            $this->header = $header;
            $this->set = $set;
            $this->authKey = $authKey;
    }
    public function get(&$client,$path = "/")
    {
        $client->get($path);
    }
    public function post(&$client,$path = "/",$data = array())
    {
        $client->post($path,$data);
    }
    public function action($method,$path,$data)
    {
        $client = new Swoole\Coroutine\Http\Client($this->host, $this->port);
        $client->setHeaders($this->header);
        $client->set($this->set);
        $path = $this->checkPath($path);
//        var_dump($method);
//          var_dump($path);
//        var_dump($this->host);

//        if($method == "GET") {
//            $this->get($client,$path);
//        }elseif($method == "POST") {
        $this->post($client,$path,$data);
//        }
        return $client;
    }
    protected function checkPath($path)
    {
        preg_match_all("/\?/",$path,$match);
        if(count($match) > 0 && count($match[0]) > 0) {
             $path .= "&authKey=".$this->authKey;
        }else{
             $path .= "?authKey=".$this->authKey;
        }
        return $path;
    }
}