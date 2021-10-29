#!/usr/bin/php
<?php
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

$wtf = "xxx";


function doLogin($username,$password)
{
    // lookup username in databas
    // check password
    return true;
    //return false if not valid
}


function seefoo (){

$servername = "127.0.0.1";
$dbusername = "test";
$dbpassword = "SuperSad4u32:(";

try{
	global $wtf;
	$db = new PDO ("mysql:host=$servername;dbname=FourTestingP", $dbusername, $dbpassword);
	echo "YOU DID IT POGU you are connected to sql!\n";
	$testshow = $db->prepare("SELECT * from testingcon");
	
	$testshow->execute();
	
	$results = $testshow->fetchall (PDO::FETCH_ASSOC); // gets all the data from query
	echo "<pre>" . var_export ($results, true) . "</pre>".PHP_EOL;
	//global $CS;
	//$CS = $results;
	return true;


}catch(PDOException $e){
	echo "Gosh you couldn't connect :( " .$e->getMessage ();
	}	
}

function requestProcessor($request)
{
	global $wtf;
  echo "received request".PHP_EOL;
  var_dump($request);
  if(!isset($request['type']))
  {
    return "ERROR: unsupported message type";
  }
  switch ($request['type'])
  {
    case "login":
      return doLogin($request['username'],$request['password']);
    case "validate_session":
      return doValidate($request['sessionId']);
     case "seePlz":
     	return seefoo();
      
      
      
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed", "data" =>  $wtf);
}

$server = new rabbitMQServer("testRabbitMQ.ini","testServer");

echo "testRabbitMQServer BEGIN".PHP_EOL;
$server->process_requests('requestProcessor');
echo "testRabbitMQServer END".PHP_EOL;
exit();
?>

