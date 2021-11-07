<?php

require_once __DIR__ . "/../vendor/autoload.php";

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
        $ini = parse_ini_file(__DIR__ . "/../rabbitmq.ini");

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

        // i assume what I am chaing is what is in the message.
        // in this case I will be calling the server that andrew 
        // ??? i need the details or is 

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