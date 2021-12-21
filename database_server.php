#!/bin/php
<?php

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/include/getters.php";
require_once __DIR__ . "/include/inserts.php";
require_once __DIR__ . "/include/joseCode.php";

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$ini = parse_ini_file(__DIR__ . "/config.ini");

if ($ini)
    [
        "host" => $host,
        "port" => $port,
        "user" => $user,
        "password" => $password,
        "vhost" => $vhost
    ] = $ini;
else
    die("Failed to parse config.ini");

$connection = new AMQPStreamConnection($host, $port, $user, $password, $vhost);
$channel = $connection->channel();

$channel->queue_declare("rpc_queue", false, false, false, false);

echo "Awaiting RPC requests", PHP_EOL;
$callback = function ($req) {
    echo $req->body, PHP_EOL;

    try {
        $req_obj = json_decode($req->body);
        $ret = call_user_func_array($req_obj->func, $req_obj->args);
    } catch (Throwable $e) {
        error_log($e->getMessage());
        $ret = null;
    }
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
