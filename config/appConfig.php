<?php
/**
 * 应用配置
 */
return  array(

     //地址池
     "webPools" => array(
         array(
             "url"   => "192.168.54.177",
             "port"  => 9091,
             "key"   => "5vdPkltIi#7m9E#CvqHrq2W3g55Vj^u*"
         )
     ),
     //进程数
     "processNum" => 1,
     //redis配置
     "redis" => array(
         'host' => '192.168.54.177',
         'port' => 7000,
         'password' => ''
     ),
     //REDIS KEY
     "taskKey"  =>"RONGHE.HOUSE.ASYNC.QUEUE",
     //监听间隔
     "taskTime" => 5 * 1000,
     //开启重试
     "retry"    => false,
     //HTTP
     "http" => array(
         "timeout" => 10
     )

);
