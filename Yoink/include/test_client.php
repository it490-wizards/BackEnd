<?php

require_once __DIR__ . "/vendor/autoload.php";

use DatabaseRpcClient as GlobalDatabaseRpcClient;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class DatabaseRpcClient
{
    private $connection;
    private $channel;
    private $callback_queue;
    private $response;
    private $corr_id;

    public function __construct()
    {
        $ini = parse_ini_file(__DIR__ . "/rabbitmq.ini");

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

        $this->connection = new AMQPStreamConnection($host, $port, $user, $password, $vhost);
        $this->channel = $this->connection->channel();
        list($this->callback_queue,,) = $this->channel->queue_declare(
            "",
            false,
            false,
            true,
            false
        );
        $this->channel->basic_consume(
            $this->callback_queue,
            "",
            false,
            true,
            false,
            false,
            [
                $this,
                "onResponse"
            ]
        );
    }

    public function onResponse($rep)
    {
        if ($rep->get("correlation_id") == $this->corr_id) {
            $this->response = $rep->body;
        }
    }

    public function call($func, ...$args)
    {
        $this->response = null;
        $this->corr_id = uniqid();

        $msg = new AMQPMessage(
            json_encode([
                "func" => $func,
                "args" => $args
            ]),
            [
                "correlation_id" => $this->corr_id,
                "reply_to" => $this->callback_queue
            ]
        );
        $this->channel->basic_publish($msg, "", "rpc_queue");
        while (is_null($this->response)) {
            $this->channel->wait();
        }

        return json_decode($this->response);
    }
}

// here you can call for the functions that you wanna call. Make sure to print or dump that stuff :)

// call the the class
$client = new GlobalDatabaseRpcClient();
// now call your stuff

//print_r($client->call("testfoo", "username1", "password1"));
// print_r($client->call("register", "crit1", "password10", "email1lol@email.com"));
// print_r($client->call("setMovie", "0019283sw2", "Sussy Baka but in spanish", "So who is it AmongUs? is it you?", "image.png", "Comedy", 60, 1970, "Spanish"));
// print_r($client->call("addSaved", 1, 1));
//print_r($client->call("addReview", 8, 7, 3, "funny but I dont speak spanish"));
//print_r($client->call("addForm", 19, "Comedy", 60, 1970, "Spanish"));
// print_r($client->call("setSession", 2));
// print_r(($client->call("getSaved", 19)));
//print_r($client->call("seefoo"));
//print_r($client->call("getReviews", 1));
//print_r($client->call("getRecommended", 19));
print_r($client->call("login", "crit1", "password10"));
// print_r($client->call("session_to_userid", "D8OgyQi50vi3jmn"));
// print_r($client->call("logout", "D8OgyQi50vi3jmn"));

//print_r($client->call("search_movie", "pokemon"));
// print_r($client->call("title", "tt8856470"));
