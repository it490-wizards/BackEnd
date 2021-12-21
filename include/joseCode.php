<?php

// to add followers to the followTable 
// note the this is dependent on the userLogin table being filled out and being dependent 
function addFollow($followerID, $followedID)
{

    try {
        require 'config.php';
        $db = new PDO("mysql:host=$servername;dbname=FourTestingP", $dbusername, $dbpassword); // get into db
        $rInsert = $db->prepare("INSERT INTO followTable (`followerID`, `followedID`) 
		values (:followerID, :followedID)");

        $v = array(":followerID" => $followerID, ":followedID" => $followedID);
        $rInsert->execute($v);
        // echo "insert into followTable complete" . PHP_EOL;
    } catch (Exception $e) {
        echo "you were not able to insert a follow into the table" . $e->getMessage() . PHP_EOL;
    }
}

// getter for jose?
// built to get the followers based on the userid
function getFollowing($userID)
{
    try {

        require "config.php";
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);

        $testshow = $db->prepare(" SELECT userLogin.username, userLogin.userID
            FROM userLogin
            WHERE userLogin.userID 
            IN (
            SELECT followTable.followedID
            FROM followTable, userLogin
            WHERE userLogin.userID = :xxx
            AND userLogin.userID = followTable.followerID 
            GROUP BY followTable.followedID) 
                GROUP BY userLogin.userID");

        $v = array(":xxx" => $userID);
        $testshow->execute($v);

        $results = $testshow->fetchall(PDO::FETCH_ASSOC); // gets all the data from query
        $readable = $results;

        return $readable;
    } catch (PDOException $e) {
        echo "function getFollowing failed. " . $e->getMessage();
    }    // end of try/catch

} // end of getFollowing


function getFollowers($userID)
{
    try {

        require "config.php";
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);

        $ts = $db->prepare("SELECT userLogin.username, userLogin.userID
            FROM userLogin
            WHERE userLogin.userID 
            IN (
                SELECT followTable.followerID
                FROM followTable, userLogin
                WHERE userLogin.userID = :xx
                AND userLogin.userID = followTable.followedID
                GROUP BY followTable.followerID ) 
            GROUP BY userLogin.userID");


        $v = array(":xx" => $userID);
        $ts->execute($v);

        $results = $ts->fetchall(PDO::FETCH_ASSOC); // gets all the data from query
        $readable = $results;

        return $readable;
    } catch (PDOException $e) {
        echo "function getFollowers failed. " . $e->getMessage();
    }    // end of try/catch

} // end of getFollowers

// honestly this looks schek but if it works I guess dont touch it?
function getUserReviews($userPageID) //different from getAllReviews
{
    try {

        require "config.php";
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);

        $testshow = $db->prepare("SELECT userLogin.username,COUNT(followTable.followerID) 
            as followers, 
            movies.movie_id, title, description,reviewText,reviewRating
            FROM followTable, userLogin, reviews,movies 
            WHERE userLogin.userID = :yyy
            AND followTable.followedID = userLogin.userID
            AND userLogin.userID=reviews.userID 
            AND movies.movie_id=reviews.movie_id
            GROUP BY userLogin.userID, reviews.review_id");


        $v = array(":yyy" => $userPageID);
        $testshow->execute($v);

        $results = $testshow->fetchall(PDO::FETCH_ASSOC); // gets all the data from query
        $readable = $results;

        return $readable;
    } catch (PDOException $e) {
        echo "function getAllUserReviews failed." . $e->getMessage();
    }    // end of try/catch

} // end of getFollowing

function getRankings()
{
    try {

        require "config.php";
        $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);

        $testshow = $db->prepare("SELECT userLogin.username, COUNT(followTable.followerID)
        as followers 
        FROM followTable, userLogin
        WHERE followTable.followedID = userLogin.userID
        GROUP BY userLogin.userID
        ORDER BY COUNT(followTable.followerID) DESC");


        $testshow->execute();

        $results = $testshow->fetchall(PDO::FETCH_ASSOC); // gets all the data from query

        return $results;
    } catch (PDOException $e) {
        echo "function getRankings failed." . $e->getMessage();
    }    // end of try/catch

} // end of getRankings