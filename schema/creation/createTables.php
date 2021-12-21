<?php


/*
    JNCV
   Using this, you can create the tables. 
   I will be using the create if not exist, so that if the script runs, it will not make tables that are already in the server LOL
   also if you run it at first, it will create all the tables that are needed.
   
   
*/


// get the information from the config files


try {

    require("../creds.php"); // this will take care of which db is being used
    $db = new PDO("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);



    // test code
    /*

    $stmt = $db->prepare("CREATE TABLE IF NOT EXISTS `ProjectsTable` 
        (
        `id` int auto_increment not null,
        `username` varchar(30) not null unique,
        `pwrd` varchar (200) not null,
        PRIMARY KEY (`id`)
        )
         CHARACTER SET utf8 COLLATE utf8_general_ci"
    );

    //remember to execute what you prepare!
    $stmt->execute();
    */

    // creating table for userLogin
    $createUserTable = $db->prepare(
        "CREATE TABLE IF NOT EXISTS `userLogin`
        (
        `userID` int auto_increment not null,
        `username` varchar (50) not null unique,
        `password` varchar (255) not null,
        `email` varchar (200) not null,
        primary key (`userID`)
        )"

    );
    $createUserTable->execute(); // this will run the code above if it can
    // end of creation of userLogins 

    // start of create of form table
    $createFormTable = $db->prepare(
        "CREATE TABLE IF NOT EXISTS formTable(
            `userID` int not null,
            `genre` varchar (100),
            `duration` int not null,
            `year` varchar (20),
            `language` varchar (200),
            Primary Key (`userID`),
            Foreign Key (`userID`) references userLogin(`userID`)
        );"
    );

    $createFormTable->execute();
    // end of creation of form table

    // creation of userSession
    $createUserSes = $db->prepare(
        "CREATE TABLE IF NOT EXISTS userSession(
        `session_id` varchar (255),
        `user_id` int not null,
        `creation` int 
        );"

    );

    $createUserSes->execute(); // this will run the code above if it can
    // end of isnta


    //create of cachedMovie Table (for all movies :))
    $cm = $db->prepare("CREATE TABLE IF NOT EXISTS `movies`(
          `movie_id` int auto_increment,
          `imdb_id` varchar (12) not null,
          `title` varchar (200) not null,
          `description` text,
          `image` text,
          `genre` varchar(200),
          `duration` int (10),
          `year` int (10),
          `language` varchar (200),
          Primary Key (movie_id)
        );
    ");
    $cm->execute();    // tnd of create cachedMovie


    // start of userInventory
    $ui = $db->prepare("CREATE TABLE IF NOT EXISTS `userInv`(
            `movie_id` int not null,
            `userID`int not null,
            Foreign Key (`userID`) references userLogin(`userID`),
            Foreign Key (`movie_id`) references movies(`movie_id`)     
            );");

    $ui->execute(); // end of userInventory 


    // creating review table
    $rc = $db->prepare("CREATE TABLE IF NOT EXISTS `reviews`(
        `review_id` int auto_increment,
        `movie_id` int not null,
        `userID` int not null,
        `reviewRating` int not null, 
        `reviewText` text,    
        Primary Key (review_id),
        Foreign Key (`userID`) references userLogin(`userID`),
        Foreign Key (`movie_id`) references movies(`movie_id`)     
        );
    ");

    $rc->execute(); // end of userInventory 


    // -----------------------------------------------------

    // creation of the table for followers

    $createFollowTable = $db->prepare(
        "CREATE TABLE IF NOT EXISTS `followTable`(
            `fID` int auto_increment,
            `followerID` int,
	        `followedID` int,
            Primary Key (`fID`),
	        Foreign Key (`followerID`) references userLogin(`userID`),
            Foreign Key (`followedID`) references userLogin(`userID`)
            )"
    );

    $createFollowTable->execute();
} catch (Exception $e) {

    echo $e->getMessage();
    echo "it didnt work LMAO";
    exit('SOMETHING BROKE :(');
}
