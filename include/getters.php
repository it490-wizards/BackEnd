<?php

function getSaved($userID)
{
    // put this into a mysql.php and use a require once :)

    try {

        require __DIR__ . "/mysql.php";
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
        /*
        $showINV = $db->prepare("SELECT userLogin.userID, userInv.movie_id, title, `description`
        from userLogin, userInv, movies,reviews
        where userInv.userID = userLogin.userID
        AND movies.movie_id = reviews.movie_id
        AND userLogin.userID = :usrid;");
        */
        $showINV = $db->prepare("SELECT userLogin.userID, userInv.movie_id, title, `description`
        from userLogin, userInv, movies
        where userInv.userID = userLogin.userID
        AND movies.movie_id = userInv.movie_id
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

        require __DIR__ . "/mysql.php";
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

        require __DIR__ . "/mysql.php";
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);

        $testshow = $db->prepare("SELECT userLogin.userID, movies.movie_id, title, description, reviewRating, reviewText
        from movies, userLogin, reviews
        where userLogin.userID = :xxx AND userLogin.userID = reviews.userID
        AND movies.movie_id = reviews.movie_id;");

        $v = array(":xxx" => $userID);
        $testshow->execute($v);

        $results = $testshow->fetchall(PDO::FETCH_ASSOC); // gets all the data from query
        $readable = $results;

        return $readable;
    } catch (PDOException $e) {
        echo "function getReivew failed. " . $e->getMessage();
    }    // end of try/catch

} // end of getReview

//get movie (gets the titles and descriptions of a movie)
function getMovie($movie_id)
{
    try {
        require __DIR__ . "/mysql.php";
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
        require __DIR__ . "/mysql.php";

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

/**
 * Process attempted login.
 *
 * @param string $username
 * @param string $password
 * @return string|null a session token on successful login, `null` otherwise
 */
function login($username, $password)
{
    try {
        require __DIR__ . "/mysql.php";
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);

        $mm = $db->prepare(
            "SELECT * FROM `userLogin` WHERE username = :username"
        );
        $mm->execute([":username" => $username]);
        $result = $mm->fetch(PDO::FETCH_ASSOC);

        if (
            $result === false ||
            $result["password_hash"] !== hash("sha256", $result["salt"] . $password, false)
        ) {
            return null;
        } else {
            $tyu = generateRandomString();
            setSession(intval($result["userID"]), $tyu);

            return $tyu;
        }
    } catch (Exception $e) {
        return null;
    }
}


function session_to_userid($st)
{
    try {
        require __DIR__ . "/mysql.php";
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword); // connect to db

        $lm = $db->prepare("SELECT `user_id` from `userSession` where `session_id` = :std");
        $vi = array(":std" => $st);
        $lm->execute($vi);

        // get the stuff
        // this is how you get the number
        $rst = $lm->fetch(PDO::FETCH_ASSOC);
        $xxx = $rst["user_id"];
        $r = intval($xxx);

        return $r;
    } catch (Exception $e) {
        echo "LMAO SES MORE LIKE SEG";
    }
}
