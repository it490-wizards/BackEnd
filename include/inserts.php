<?php

/**
 * Register a new user.
 *
 * @param string $username
 * @param string $password
 * @param string $email
 * @return bool `true` on successful registration, `false` otherwise
 */
function register($username, $password, $email)
{
    try {

        require __DIR__ . "/mysql.php";
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);

        $salt = random_bytes(16);
        $password_hash = hash("sha256", $salt . $password, false);

        $insert = $db->prepare(
            "INSERT INTO `userLogin` (username, password_hash, salt, email)
            VALUES (:username, :password_hash, :salt, :email)"
        );
        $insert->execute([
            ":username" => $username,
            ":password_hash" => $password_hash,
            ":salt" => $salt,
            ":email" => $email
        ]);

        return true;
    } catch (Exception $e) {
        $e->getMessage();
        return false;
    }
}

//insert into the reivews table
// comfrimed to work now :)
function addReview($movieID, $userID, $ratingReview, $reviewText)
{

    try {
        require __DIR__ . "/mysql.php";
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword); // get into db
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

        require __DIR__ . "/mysql.php";
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword); // get into db

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
        require __DIR__ . "/mysql.php";
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword); // get into db

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
        require __DIR__ . "/mysql.php";
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword); // get into db

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
        require __DIR__ . "/mysql.php";
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword); // get into db

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


// to add stuff to session table
function setSession($userID, $session)
{
    try {
        require __DIR__ . "/mysql.php";
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);

        $cTime = time();

        $abc = $db->prepare("INSERT INTO `userSession` (`session_id`,`user_id`,`creation`)
        values (:sesid, :usrid, :djtime)");
        $v = array(":sesid" => $session, ":usrid" => $userID, ":djtime" => $cTime);
        $abc->execute($v);

        // echo "session is set >>> " . $userID . " <<< this is the uid in session func";
    } catch (Exception $e) {
        echo "this is quite unfortunate. You cant add users at the momment. :(" . $e->getMessage();
    }
}


function logout($sessionToken)
{
    try {
        require __DIR__ . "/mysql.php";
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword); // connect to db

        $lm = $db->prepare("DELETE FROM `user_id` where `session_id` = :std");
        $vi = array(":std" => $sessionToken);
        $lm->execute($vi);

        // get the stuff
        // this is how you get the number from the qs

        return true;
    } catch (Exception $e) {
        echo "it persist :///// " . ' ' . $e->getMessage();
    }
}
