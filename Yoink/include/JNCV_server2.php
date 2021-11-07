<?php

require_once __DIR__ . '/vendor/autoload.php';
require 'config.php';
//require ("getters.php");
//require ("inserts.php");


use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

$connection = new AMQPStreamConnection('localhost', 5672, 'guest', 'guest');
$channel = $connection->channel();

$channel->queue_declare('rpc_queue', false, false, false, false);

function fib($n)
{
    if ($n == 0) {
        return 0;
    }
    if ($n == 1) {
        return 1;
    }
    return fib($n - 1) + fib($n - 2);
}

//JNCV: when you have time, write out the functions in a seperate file and then use require_once(); so that the script can call them :) 

function seefoo (){



	// put this into a config.php and use a require once :)

	try{
		
		require 'config.php'; // to get the sensitive stuff :^)

		$db = new PDO ("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
		// echo "YOU DID IT POGU you are connected to sql!\n";
		$testshow = $db->prepare("SELECT * from testingcon");
		$testshow->execute();
		
		$results = $testshow->fetchall (PDO::FETCH_ASSOC); // gets all the data from query
		
		$readable = json_encode($results);
		
		echo $readable.PHP_EOL;	
		return $readable;
		
		


	}catch(PDOException $e){
		echo "Gosh you couldn't connect :( " .$e->getMessage ();
		}	
}


function testfoo($username, $password){ // testing inserting stuff LOL?



	try {

		require 'config.php'; // to get the sensitive stuff

		$db = new PDO ("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
	/*	$connect -> setAttribute (PDO::ARRT_ERRMODE, PDO::ERRMODE_EXCEPTION);*/
		echo "YOU DID IT POGU you are connected to sql!\n";
		
		
		$testInsert = $db->prepare("INSERT INTO `testingcon` (username, password) values (:username,:password)");
		$varis = array (":username"=>$username, ":password"=>$password);
		
		$testInsert->execute($varis);
		
		



	}catch(PDOException $e){
		echo "Gosh you couldn't connect :( " .$e->getMessage ();
		}	
	
} // testfoo end


echo " [x] Awaiting RPC requests\n";
$callback = function ($req) { // it calls the function here 
    // $n = intval($req->body); // pretend that this is the json text
    echo "we see you".PHP_EOL;
    echo $req->body;
    $obj = json_decode($req->body);
    $params = $obj->params;
    
    // SWTICH HEREEEEEEEEEEEEEEEEEEEEEEe
	// ASK ANDREW WTF AM I SUPPOSE TO DO HERE LUL TO MAKE IS NOT SWITCH STATEMENT
    // ---------------------------->>>>>>>>>>>>>>>>. we have to replace this dude
    switch ($obj->func){
    
    	case "seeFoo":
    		echo "we are in see foo";
    		$ans = seeFoo();
    		echo $ans;
    		break;
    		
    		
    	case "testFoo":
    		echo "were are in test foo";
    		[$username,$password] = $params;
    		$ans = testFoo($username,$password);
    		echo $ans;
    		break;
    
    } // replace with a func array here
    
    echo ' [.] fib(', 0, ")\n";

    $msg = new AMQPMessage(
        $ans, // calls the function here 
        array('correlation_id' => $req->get('correlation_id'))
    );

    $req->delivery_info['channel']->basic_publish(
        $msg,
        '',
        $req->get('reply_to')
    );
    $req->ack();
};

$channel->basic_qos(null, 1, null);
$channel->basic_consume('rpc_queue', '', false, false, false, false, $callback);

while ($channel->is_open()) {
    $channel->wait();
}

$channel->close();
$connection->close();