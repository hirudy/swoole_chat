<?php
/**
 *
 * swoole chat example
 *
 * @author: rudy
 * @date: 2016/11/16
 */

include_once "extension/RobotRequest.php";

$config = include_once "conf/main.php";
$server = new Swoole\Websocket\Server($config["host"], $config["port"]);

$server->set(array(
    "daemonize" => true,
    "worker_num" => 4,
//    ""
));

$server->on("WorkerStart",function($server,$frame){
    global $global_num;
    $global_num ++;
    echo "worker start".$server->worker_id, "| ". $global_num ."\n";
});

$server->on('Open', function($server, $req) {
    global $global_num;
    $global_num ++;
    echo "connection open: ".$server->worker_id. "|" .$global_num ."\n";
});


$server->on('Message', function($server, $frame) {
    $request = array(
        "position" => "left",
        "message" => $frame->data
    );
    $server->push($frame->fd, json_encode($request));
    $data = RobotRequest::request($frame->fd,$frame->data);
    $data["position"] = "right";
    $server->push($frame->fd, json_encode($data));
});

$server->on('Close', function($server, $fd) {
    echo "connection close: ".$fd, "\n";
});

$global_num = 0;
$server->start();