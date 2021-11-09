<?php

/*
    JNCV:
        put all the functions that insert into the tables in here
	// NOV 11/08 -> What I know is tested and works now:
		- register 
		- setMovie
		- addSaved
		- addReview
		- addFrom
		- setSession

	-> what doesnt work?

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
function register($username, $password, $email)
{
	try {

		require 'config.php'; // to get the sensitive stuff
		$db = new PDO("mysql:host=$servername;dbname=FourTestingP", $dbusername, $dbpassword);

		$insert = $db->prepare("INSERT INTO `userLogin` (username, password, email) values (:username, :password, :email)");
		$v = array(":username" => $username, ":password" => $password, ":email" => $email);
		$insert->execute($v);
	} catch (Exception $e) {
		echo "this is quite unfortunate. You cant add users at the momment. :(" . $e->getMessage();
	}
}

//insert into the reivews table
// comfrimed to work now :)
function addReview($movieID, $userID, $ratingReview, $reviewText)
{

	try {
		require 'config.php'; // to get the sensitive stuff
		$db = new PDO("mysql:host=$servername;dbname=FourTestingP", $dbusername, $dbpassword); // get into db
		$rInsert = $db->prepare("
		INSERT INTO reviews (`movie_id`, `userID`, `reviewRating`, `reviewText`) 
		values (:movieID, :userID, :rr, :rt)");
		$v = array(":movieID" => $movieID, ":userID" => $userID, ":rr" => $ratingReview, ":rt" => $reviewText);
		$rInsert->execute($v);
		// echo "insert into reivew complete" . PHP_EOL;
	} catch (Exception $e) {
		echo "you were not able to insert a reivew into the table" . $e->getMessage() . PHP_EOL;
	}
}


// add movies to the userInv
// comfirmed working as long as there are users and movies that exist :)
function addSaved($userID, $movieID)
{

	try {

		require 'config.php'; // to get the sensitive stuff
		$db = new PDO("mysql:host=$servername;dbname=FourTestingP", $dbusername, $dbpassword); // get into db

		$rInsert = $db->prepare("INSERT INTO `userInv` (`movie_id`, `userID`) 
		values (:md,:ud)");
		$varis = array(":md" => $movieID, ":ud" => $userID);
		$rInsert->execute($varis);

		//echo "insert into reivew complete".PHP_EOL;

	} catch (Exception $e) {
		echo "you wre not able to put the movie into the userINV " . $e->getMessage() . PHP_EOL;
	}
}

// adding movies to the uersINv
// lol is this justnoather addsaved? oops
function addMovie($userID, $movieID)
{

	try {
		require 'config.php'; // to get the sensitive stuff
		$db = new PDO("mysql:host=$servername;dbname=FourTestingP", $dbusername, $dbpassword); // get into db

		$rInsert = $db->prepare("INSERT INTO `userInv` (userID, movie_id) values (:userID, :movieID)");
		$varis = array(":userID" => $userID, ":movie_id" => $movieID);
		$rInsert->execute($varis);

		echo "insert into reivew complete" . PHP_EOL;
	} catch (Exception $e) {
		echo "you wre not able to put the movie into the movies " . $e->getMessage() . PHP_EOL;
	}
}

// adding stuff to the table for preferences (ie formTable)
function addForm($userID, $genre, $duration, $year, $language)
{
	try {
		require 'config.php'; // to get the sensitive stuff
		$db = new PDO("mysql:host=$servername;dbname=FourTestingP", $dbusername, $dbpassword); // get into db

		$fInsert = $db->prepare("INSERT INTO `formTable` (`userID`, `genre`, `duration`, `year`, `language`) 
		values (:userID, :genre, :duration, :era,:langu)");
		$varis = array(":userID" => $userID, ":genre" => $genre, ":duration" => $duration, ":era" => $year, ":langu" => $language);
		$fInsert->execute($varis);
	} catch (Exception $e) {
		echo "you were not able to save your prefs :( " . $e->getMessage() . PHP_EOL;
	}
}


// function for adding movies to the db from the api (adnrew will need this I think)
// lol he didnt but good warm up
// this is just to popular the table .. and i might end up using this too LOL
function setMovie($imbd_id, $title, $desc, $image, $genre, $duration, $year, $language)
{
	try {
		require 'config.php'; // to get the sensitive stuff
		$db = new PDO("mysql:host=$servername;dbname=FourTestingP", $dbusername, $dbpassword); // get into db

		$fInsert = $db->prepare("INSERT INTO `movies` (`imdb_id`, `title`, `description`, `image`, `genre`, `duration`, `year`, `language`)
		values (:imdb_db, :title, :descp, :img, :genre, :duration, :era, :langu);");
		$x = array(":imdb_db" => $imbd_id, ":title" => $title, ":descp" => $desc, ":img" => $image, ":genre" => $genre, ":duration" => $duration, ":era" => $year, ":langu" => $language);
		$fInsert->execute($x);
		return true;
	} catch (Exception $e) {
		echo "you were not able to cache ur movies( " . $e->getMessage() . PHP_EOL;
	}
}

//-------------------- SESSION STUFF IS BELOW HERE ----------------------------

// create a werid string for sessions
function generateRandomString()
{
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen($characters);
	$String = '';
	for ($i = 0; $i < 15; ++$i) {
		$String .= $characters[rand(0, $charactersLength - 1)];
	}
	return $String;
}

// to add stuff to session table
function setSession($userID)
{
	try {
		require 'config.php'; // to get the sensitive stuff
		$db = new PDO("mysql:host=$servername;dbname=FourTestingP", $dbusername, $dbpassword);
		$seed = generateRandomString();
		$cTime = time();
		$abc = $db->prepare("INSERT INTO `userSession` (`session_id`,`user_id`,`creation`) 
		values (:sesid, :usrid, :djtime)");
		$v = array(":sesid" => $seed, ":usrid" => $userID, ":djtime" => $cTime);
		$abc->execute($v);
	} catch (Exception $e) {
		echo "this is quite unfortunate. You cant add users at the momment. :(" . $e->getMessage();
	}
}
