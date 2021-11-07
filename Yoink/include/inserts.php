<?php

/*
    JNCV:
        put all the functions that insert into the tables in here
*/

/*
function testfoo($username, $password){ // testing inserting stuff LOL?
	try {

		require 'config.php'; // to get the sensitive stuff
		$db = new PDO ("mysql:host=$servername;dbname=FourTestingP", $dbusername, $dbpassword);
		
		
		$testInsert = $db->prepare("INSERT INTO `testingcon` (username, password) values (:username,:password)");
		$varis = array (":username"=>$username, ":password"=>$password);
		$testInsert->execute($varis);
	}catch(Exception $e){
		echo "Gosh you couldn't connect :( " .$e->getMessage ();
        // probably make sure to catch stuff
		}	
	
} // testfoo end
*/

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
function addReview ($userID, $movieID, $ratingReview, $reviewText){

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
function addSaved ($userID, $movieID){

	try {

		require 'config.php'; // to get the sensitive stuff
		$db = new PDO ("mysql:host=$servername;dbname=FourTestingP", $dbusername, $dbpassword); // get into db

		$rInsert = $db->prepare("INSERT INTO `userInv` (userID, movie_id) values (:userID, :movieID");
		$varis = array (":userID"=>$userID, ":movie_id"=>$movieID);
		$rInsert->execute($varis);

		//echo "insert into reivew complete".PHP_EOL;
		
	} catch (Exception $e) {
		echo "you wre not able to put the movie into the userINV ". $e->getMessage(). PHP_EOL;	
	}

}

// adding movies to the uersINv
function addMovie ($userID, $movieID){

	try {
		require 'config.php'; // to get the sensitive stuff
		$db = new PDO ("mysql:host=$servername;dbname=FourTestingP", $dbusername, $dbpassword); // get into db
		
		$rInsert = $db->prepare("INSERT INTO `userInv` (userID, movie_id) values (:userID, :movieID)");
		$varis = array (":userID"=>$userID, ":movie_id"=>$movieID);
		$rInsert->execute($varis);
		
		echo "insert into reivew complete".PHP_EOL;
		
	} catch (Exception $e) {
		echo "you wre not able to put the movie into the movies ". $e->getMessage(). PHP_EOL;	
	}

}

// adding stuff to the table for preferences (ie formTable)
function addForm ($userID, $genre, $duration, $year, $language){
	try {
		require 'config.php'; // to get the sensitive stuff
		$db = new PDO ("mysql:host=$servername;dbname=FourTestingP", $dbusername, $dbpassword); // get into db
		
		$fInsert = $db->prepare("INSERT INTO `formTable` (userID, genre, duration, `year`, language) values (:userID, :genre, :duration, `:year`,:language)");
		$varis = array (":userID"=>$userID, ":genre"=>$genre, ":duration"=>$duration, ":year"=>$year, ":language"=>$language);
		$fInsert->execute($varis);
		
	} catch (Exception $e) {
		echo "you were not able to save your prefs :( ". $e->getMessage(). PHP_EOL;	
	}


}


	// function for adding movies to the db from the api (adnrew will need this I think)
	// lol he didnt but good warm up
function setMovie ($imbd_id, $title, $desc, $image, $genre, $duration, $year, $language){
	try {
		require 'config.php'; // to get the sensitive stuff
		$db = new PDO ("mysql:host=$servername;dbname=FourTestingP", $dbusername, $dbpassword); // get into db
		
		$fInsert = $db->prepare("INSERT INTO `movies` (`imdb_id`, `title`, `description`, `image`, genre, duration, `year`, `language`)
		values ($imbd_id, $title, $desc, $image, $genre, $duration, $year, $language)");
		$x = array (":imdb_db"=>$imbd_id, ":title"=>$title, ":desc"=>$desc, ":image"=> $image, ":genre"=>$genre, ":duration"=>$duration, ":year"=>$year, ":language"=>$language);
		$fInsert->execute($x);
		
	} catch (Exception $e) {
		echo "you were not able to cache ur movies( ". $e->getMessage(). PHP_EOL;	
	} 




}



?>