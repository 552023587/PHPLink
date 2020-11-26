<?php


class PHPLink {


    public function __construct()
    {

    }
    public function start()
    {
        $AppConfig = require_once APP_PATH."/config/appConfig.php";

        $this->showMessage();

        $Log = new Log();
        $Log->notice("PHP LINK START");
        $Redis = new RedisConnect($AppConfig);
        Swoole\Runtime::enableCoroutine($flags = SWOOLE_HOOK_ALL);

        Swoole\Timer::tick($AppConfig['taskTime'], function() use ($AppConfig,$Log,$Redis) {

            try {
                $processNum = $AppConfig["processNum"];
                $chan = new chan($processNum);
                $LinkData = new LinkData($Redis,$AppConfig);
                //$Log->notice("QueueID:".$LinkData->getQueueId());
                if($LinkData->isNull == false) {
                    foreach ($AppConfig["webPools"] as $web) {
                        $og = str_replace("www.","",$LinkData->getOriginHost());
                        $webUrl = str_replace("www.","",$web["url"]);
                        if ($og !== $webUrl) {
                            $tag = array("REQUEST");
                            $Log->notice($web["url"],$tag);
                            $Log->notice($LinkData->getMethod(),$tag);
                            $Log->notice($LinkData->getRoute(),$tag);
                            $Log->notice($web["key"],$tag);
                            $Log->notice($LinkData->getRequestData(),$tag);
                            go(function () use ($chan, $AppConfig, $web, $LinkData,$Log) {
                                $header = array(
                                    "host" => $web["url"]
                                );
                                $set = array(
                                    "timeout" => $AppConfig["http"]["timeout"]
                                );
//                                var_dump($web["url"]);
//                                var_dump($LinkData->getMethod());
//                                var_dump($LinkData->getRoute());
//                                var_dump($web["key"]);
                               // var_dump($LinkData->getRequestData());
                                $cli = new Request($web["url"],$web["port"], $header, $set, urlencode($web["key"]));
                                $ret = $cli->action($LinkData->getMethod(), $LinkData->getRoute(), $LinkData->getRequestData());
                                $chan->push([$web["url"] => $ret->body]);
                            });
                        }
                    }
                    $result = [];
                    for ($i = 0; $i < $processNum; $i++) {
                        $result += $chan->pop();
                    }
                    $tag = array("RESPONSE");
                    foreach($result as $k=>$ret) {
                        $Log->notice($k,$tag);
                        $Log->notice($k.":".$ret,$tag);
                        $ret = json_decode($ret);
                        // var_dump($ret);
                        if($ret->code == 200) {
                            $LinkData->clear();
                        }else{
                            if($AppConfig["retry"]) {
                                $LinkData->retry();
                            }else{
                                $LinkData->clear();
                            }
                            $Log->error("action fail",$tag);
                        }
                    }
                }

            }catch (Exception $e) {
                $Log->error($e->getMessage());
            }
            catch (\Swoole\ExitException $e){
                $Log->error($e->getMessage());
                $Log->error($e->getStatus());
                $Redis->close();
            }

        });
    }
    public function stop()
    {
        $command = "ps | grep main.php";
        exec($command,$output);
    }
    protected function listenSign()
    {

    }
    protected function showMessage()
    {
        $START_TIME = date("Y-m-d H:i:s");
        echo "\n";
        echo "===================================================================== \n";
        echo "=                              PHP LINK                             = \n";
        echo "=                                                                   = \n";
        echo "=                         DEV: ALLEN LEONG                          = \n";
        echo "=                                                                   = \n";
        echo "=                  START TIME: {$START_TIME}                  = \n";
        echo "=                                                                   = \n";
        echo "===================================================================== \n";

    }
}