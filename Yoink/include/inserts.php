<?php

/*
    JNCV:
        put all the functions that insert into the tables in here
*/


function testfoo($username, $password){ // testing inserting stuff LOL?
	try {
        
		require 'config.php'; // to get the sensitive stuff
		$db = new PDO ("mysql:host=$servername;dbname=FourTestingP", $dbusername, $dbpassword);
	/*	$connect -> setAttribute (PDO::ARRT_ERRMODE, PDO::ERRMODE_EXCEPTION);*/
		echo "YOU DID IT POGU you are connected to sql!\n";
		
		
		$testInsert = $db->prepare("INSERT INTO `testingcon` (username, password) values (:username,:password)");
		$varis = array (":username"=>$username, ":password"=>$password);
		
		$testInsert->execute($varis);
		
	}catch(PDOException $e){
		echo "Gosh you couldn't connect :( " .$e->getMessage ();
        // probably make sure to catch stuff
		}	
	
} // testfoo end







?>