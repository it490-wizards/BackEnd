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
	}catch(Exception $e){
		echo "Gosh you couldn't connect :( " .$e->getMessage ();
        // probably make sure to catch stuff
		}	
	
} // testfoo end


// insert a new user into the uesrLogin table
function register ($username, $password, $email){
	try{

		require 'config.php'; // to get the sensitive stuff
		$db = new PDO ("mysql:host=$servername;dbname=FourTestingP", $dbusername, $dbpassword);

		$insert = $db->prepare ("INSERT INTO `userLogin` (username, password, email) values (:username, :password, :email)");
		$v = array (":username"=>$username, ":password"=>$password, ":email"=>$email);
		$insert->execute($v);
	}catch (Exception $e){
			echo "this is quite unfortunate. You cant add users at the momment. :(" . $e->getMessage();

	}



}

//insert into the reivews table
function setReview ($userID, $movieID, $ratingReview, $reviewText){

	try{
		require 'config.php'; // to get the sensitive stuff
		$db = new PDO ("mysql:host=$servername;dbname=FourTestingP", $dbusername, $dbpassword); // get into db
		$rInsert = $db->prepare("INSERT INTO `reviews` (userID, movie_id, reviewRating, reviewText) values (:userID, :movieID, :ratingReview, :reviewText");
		$varis = array (":userID"=>$userID, ":movie_id"=>$movieID,":reviewRating"=>$ratingReview, ":reviewText"=>$reviewText);
		$rInsert->execute($varis);
		echo "insert into reivew complete".PHP_EOL;
	}catch(Exception $e){
		echo "you were not able to insert a reivew into the table". $e->getMessage ().PHP_EOL;

	}

}


// add movies to the userInv
function setMovie ($userID, $movieID){

	try {
		require 'config.php'; // to get the sensitive stuff
		$db = new PDO ("mysql:host=$servername;dbname=FourTestingP", $dbusername, $dbpassword); // get into db
		$rInsert = $db->prepare("INSERT INTO `userInv` (userID, movie_id) values (:userID, :movieID");
		$varis = array (":userID"=>$userID, ":movie_id"=>$movieID);
		$rInsert->execute($varis);
		echo "insert into reivew complete".PHP_EOL;
		
	} catch (Exception $e) {
		echo "you wre not able to put the movie into the userINV ". $e->getMessage(). PHP_EOL;	
	}

}







?>