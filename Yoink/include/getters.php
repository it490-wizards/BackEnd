<?php



/*
    JNCV: NOV 08
    --> tested and is working : 
        - getSaved
        - getRecommended
        - getReviews
        - getMovie
        - getAllReviews

*/
/*


---------------------------------------------
function seefoo()
{

    try {

        require 'config.php'; // to get the sensitive stuff :^)
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        // echo "YOU DID IT POGU you are connected to sql!\n" ; // DEBUG
        $testshow = $db->prepare("SELECT * from testingcon");
        $testshow->execute();
        $results = $testshow->fetchall(PDO::FETCH_ASSOC); // gets all the data from query       
        $readable = json_encode($results);
        echo $readable . PHP_EOL;
        return $readable;
    } catch (PDOException $e) {
        echo "Gosh you couldn't connect :( " . $e->getMessage();
    }
}
*/

// this will get the movies that the user saved aka userInv
// because this table stores all of the users, you will have to get the userID to filter out their sepcific saves

function getSaved($userID)
{
    // put this into a config.php and use a require once :)

    try {

        require "config.php";
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        $showINV = $db->prepare("SELECT userLogin.userID, userInv.movie_id, title, `description`             
        from userLogin, userInv, movies,reviews             
        where userInv.userID = userLogin.userID 
        AND movies.movie_id = reviews.movie_id 
        AND userLogin.userID = :usrid;");
        $v = array(":usrid" => $userID);
        $showINV->execute($v);

        $results = $showINV->fetchall(PDO::FETCH_ASSOC); // gets all the data from query

        // $readable = json_encode($results); // makes it able to send it over
        $readable = $results;
        // echo $readable . PHP_EOL;
        return $readable;
    } catch (PDOException $e) {
        echo "function get saved failed " . $e->getMessage();
    }
}

// this is to get the movies recomendation
// this will rely on the userLogin, formTable (holds the preferences), and movie tablesS
function getRecommended($userID)
{
    try {

        require "config.php";
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);

        $eRec = $db->prepare("SELECT userLogin.userID, movies.movie_id, title, description 
        from movies, userLogin, formTable
         where userLogin.userID = :usrid 
         AND formTable.userID = userLogin.userID 
         AND ((formTable.genre = movies.genre AND formTable.duration = movies.duration)
         OR(formTable.genre = movies.genre AND formTable.year = movies.year) 
         OR (formTable.duration = movies.duration AND formTable.year = movies.year))");
        $v = array(":usrid" => $userID);
        $eRec->execute($v); // lol get honry bonked

        $results = $eRec->fetchall(PDO::FETCH_ASSOC); // gets all the data from query

        // $readable = json_encode($results);
        $readable = $results;

        //  echo $readable . PHP_EOL;
        return $readable;
    } catch (PDOException $e) {
        echo "Gosh you couldn't connect :( " . $e->getMessage();
    }
} // end of get reccs

// to get all the reviews of a given user
// gr the movieID, title, secription, reviewrating of the movie, and the reviewtext
function getReviews($userID)
{

    try {

        require "config.php";
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);

        $testshow = $db->prepare("SELECT userLogin.userID, movies.movie_id, title, description, reviewRating, reviewText 
        from movies, userLogin, reviews 
        where userLogin.userID = :xxx AND userLogin.userID = reviews.userID
        AND movies.movie_id = reviews.movie_id;");

        $v = array(":xxx" => $userID);
        $testshow->execute($v);

        $results = $testshow->fetchall(PDO::FETCH_ASSOC); // gets all the data from query
        // $readable = json_encode($results);
        $readable = $results;

        // echo $readable.PHP_EOL;	//
        return $readable;
    } catch (PDOException $e) {
        echo "function getReivew failed. " . $e->getMessage();
    }    // end of try/catch

} // end of getReview

//get movie (gets the titles and descriptions of a movie)
function getMovie($movie_id)
{
    try {
        require "config.php";
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);

        $go = $db->prepare("SELECT title, `description` from `movies` where movie_id = :movie_id ");
        $v = array(":movie_id" => $movie_id);
        $go->execute($v);

        $results = $go->fetchall(PDO::FETCH_ASSOC); // gets all the data from query
        //  $readable = json_encode($results);
        $readable = $results;

        return $readable;
    } catch (Exception $e) {
        echo "You cannot get the movies at the momment" . $e->getMessage() . PHP_EOL;
    }
} // get movie end


// getALL reviews
// you are looking for all of the reviews and the review's information on a SINGLE movie

function getAllReviews($movie_id)
{

    try {
        require "config.php";

        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword); // connect to db

        $mmm = $db->prepare("SELECT userLogin.username, userLogin.userID, reviews.reviewRating, reviews.reviewText 
        from reviews, userLogin 
        where movie_id = :mid
        AND userLogin.userID = reviews.userID");
        $v = array(":mid" => $movie_id);
        $mmm->execute($v);

        $results = $mmm->fetchall(PDO::FETCH_ASSOC); // gets all the data from query
        //$readable = json_encode($results);
        $readable = $results;

        return $readable;
    } catch (Exception $e) {
        echo "fak u it not work";
    }
}

// i need to solve this out with andrew LOL
// i already generate the session token in the insrts
function login($username, $password)
{


    try {
        require "config.php";
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword); // connect to db

        $mm = $db->prepare("SELECT * from `userLogin` where username = :username, `password` = `:password`");
        $v = array(":username" => $username, ":password" => $password);
        $mm->execute($v);

        $results = $mm->fetchall(PDO::FETCH_ASSOC); // gets all the data from query
        //$readable = json_encode($results);
        $readable = $results;

        //return $readable;
        return "HELLO :)";
    } catch (Exception $e) {
        echo "LMAO U CANT LOGIN U FOOL";
    }
}
