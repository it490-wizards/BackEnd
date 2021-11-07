<?php

require_once __DIR__ . "/vendor/autoload.php";
require ('./getters.php');

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$ini = parse_ini_file("rabbitmq.ini");

if ($ini)
    [
        "HOST" => $host,
        "PORT" => $port,
        "USER" => $user,
        "PASSWORD" => $password,
        "VHOST" => $vhost
    ] = $ini;
else
    die("Failed to parse rabbitmq.ini");

$connection = new AMQPStreamConnection($host, $port, $user, $password, $vhost);
$channel = $connection->channel();

$channel->queue_declare("rpc_queue", false, false, false, false);

echo " [x] Awaiting RPC requests", PHP_EOL;
$callback = function ($req) {
    echo $req->body, PHP_EOL;
    $req_obj = json_decode($req->body);
    $ret = call_user_func_array($req_obj->func, $req_obj->args);

    $msg = new AMQPMessage(
        json_encode($ret),
        [
            "correlation_id" => $req->get("correlation_id")
        ]
    );

    $req->delivery_info["channel"]->basic_publish(
        $msg,
        "",
        $req->get("reply_to")
    );
    $req->ack();
};

$channel->basic_qos(null, 1, null);
$channel->basic_consume("rpc_queue", "", false, false, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();
