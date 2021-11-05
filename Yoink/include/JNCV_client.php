<?php

require_once __DIR__ . '/vendor/autoload.php';

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class FibonacciRpcClient
{
    private $connection;
    private $channel;
    private $callback_queue;
    private $response;
    private $corr_id;

    public function __construct()
    {
        $this->connection = new AMQPStreamConnection(
            'localhost',
            5672,
            'guest',
            'guest'
        );
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
            '',
            false,
            true,
            false,
            false,
            array(
                $this,
                'onResponse'
            )
        );
    }

    public function onResponse($rep)
    {
        if ($rep->get('correlation_id') == $this->corr_id) {
            $this->response = $rep->body;
        }
    }
	
	
	
		
	
    public function call($n)
    {
        $this->response = null;
        $this->corr_id = uniqid();

        $msg = new AMQPMessage(
            (string) $n,
            array(
                'correlation_id' => $this->corr_id,
                'reply_to' => $this->callback_queue
            )
        );
        $this->channel->basic_publish($msg, '', 'rpc_queue');
        while (!$this->response) {
            $this->channel->wait();
        }
        return intval($this->response);
    }
    
    // JNCV starts here
    
    // test being able to see what is in the db
    public function seeFoo(){
    	$this->response = null;
        $this->corr_id = uniqid();

        $msg = new AMQPMessage(
            // (string) ,
            
            // build your array here
            json_encode ([
            	"func"=> "seeFoo",// call the function in the switch statement on server side
            	"params"=> [] // this is where you put in params
            ]),
            
            
            array( // dont touch this start
                'correlation_id' => $this->corr_id,
                'reply_to' => $this->callback_queue
            ) // dont touch this end :)
        );
        $this->channel->basic_publish($msg, '', 'rpc_queue');
        while (!$this->response) {
            $this->channel->wait();
        }
        return ($this->response);
    	
    	
    }
    

    // testfoo test interaction with inputing into db
    public function testFoo($username, $password){
    	$this->response = null;
        $this->corr_id = uniqid();

        $msg = new AMQPMessage(
            // (string) ,
            
            // build your array here
            json_encode ([
            	"func"=> "testFoo",// call the function in the switch statement on server side
            	"params"=> [
            		$username,$password
            	] // this is where you put in params
            ]),
            
            
            array( // dont touch this start
                'correlation_id' => $this->corr_id,
                'reply_to' => $this->callback_queue
            ) // dont touch this end :)
        );
        $this->channel->basic_publish($msg, '', 'rpc_queue');
        while (!$this->response) {
            $this->channel->wait();
        }
        return ($this->response);
    	
    	
    }


    public function seeFoo2(){
    	$this->response = null;
        $this->corr_id = uniqid();

        $msg = new AMQPMessage(
            // (string) ,
            
            // build your array here
            json_encode ([
            	"func"=> "seeFoo",// call the function in the switch statement on server side
            	"params"=> [] // this is where you put in params
            ]),
            
            
            array( // dont touch this start
                'correlation_id' => $this->corr_id,
                'reply_to' => $this->callback_queue
            ) // dont touch this end :)
        );
        $this->channel->basic_publish($msg, '', 'rpc_queue');
        while (!$this->response) {
            $this->channel->wait();
        }
        return ($this->response);
    	
    	
    }
    
    
}

$fibonacci_rpc = new FibonacciRpcClient();
$response = $fibonacci_rpc->testFoo("name1","passowrd2"); // function is called here :)

echo ' [.] Got ', $response, "\n";