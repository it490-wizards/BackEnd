<?php

/*
This will be a test to see if I can get a request of some sort and then create a table from that reuqest

REMEMBER TO DEVELOP KEY WORDS
cases: 
	if there is a new user 
		- use the request type [newUser]
			- FNTEND needs to send to the following over:
				- username
				- password (will have to edit script with andrew for the login/authentication system)
				- email
	
	the table should emcompass all of the things and other:
	


*/






// note that this uses/references the dummy code

// i think this needs to be in the folder for rabbit ... cuz the paths might work like that?
require_once('path.inc');
require_once('get_host_info.inc');
require_once('rabbitMQLib.inc');

function doLogin($username,$password) // to get into rabbit
{
    // lookup username in databas
    // check password
    return true;
    //return false if not valid
}

function testfoo($username, $password){
	
	$testInsert = $db->prepare(
	"INSERT INTO `testingcon` (username, password) values (:username,:password)"
	);
	$varis = array (":username"=>$username, ":password"=>$password);
	
	$testInsert->execute($varis);
	
}

// this one is the make a table for the new user and some other stuff
function newUserLogin ($feUn, $fePw, $feEm){
	$newtab = $db ->prepare (
	" create table if not exisits `testUserLoginTable`
	(
	'userID' int not null auto_increment,
	`username` varchar (50) not null unique,
	`password` vharchar (255) not null,
	`email` varchar (200) not null,
	primary key (`userID`)
	
	)"
	);
	
	$newtab-> execute(); // this will run the code above if it can
	
	echo "New table was made or it exist already ... nothing failed \n"; // debug
	
	/* if the table is made, just insert the new user into 
	it via the variables that should have come in the form $request[username].$request[email], etc.*/
	
	// set up the variables here?
	$un = $feUn;
	$password =  $fePw;
	$email = $feEm;
	
	echo "Variables are set and loaded! \n";

	$inTo = $db->prepare (
	"insert into `testUserLoginTable` (username, password, email) values (:username,:password,:email)"
	
	);	
	
	$params = array (":username"=> $un, ":password" => $password, ":email" => $email);
	
	$newtab->execute ($params);
	
	echo "the user is made! \n";
	
	return true; // just to end the process?
	
}

//----------------------------------- 

function requestProcessor($request) // this is where you would explain what to do with reach request data
{
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
      // ---------------------------------------------------------------------------------------------------//
      // I start adding my own ones here !!!!!!!!! JNCV
      
      case "newUser":
      	// so the variables pass 
      	
      	return newUserLogin ($request['username'], $request['password'],$request['email']);
      	
      	case "test":
      	 	return testfoo();
      
      
  }
  return array("returnCode" => '0', 'message'=>"Server received request and processed");
}



$servername = "127.0.0.1";
$username = "test";
$password = "SuperSad4u32:(";

try {
	$connect = new PDO ("mysql:host=$servername;dbname=myBD", $username, $password);
	$connect -> setAttribute (PDO::ARRT_ERRMODE, PDO::ERRMODE_EXCEPTION);
	echo "YOU DID IT POGU \n";
	



}catch(PDOException $e){
	echo "Gosh you couldn't connect :( " .$e->getMessage ();
}




$server = new rabbitMQServer("testRabbitMQ.ini","testServer");
$server->process_requests('requestProcessor');


// -----------------------> need to figure out a way to hide the variables








?>
