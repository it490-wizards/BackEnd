<?php


/*
   Using this, you can create the tables. 
   I will be using the create if not exist, so that if the script runs, it will not make tables that are already in the server LOL
   also if you run it at first, it will creat all the tables that are needed.
   
   
*/


// get the information from the config files


try {

    require ("../config.php"); // this will take care of which db is being used
    $db = new PDO ("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);



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


    
    

    // creating table for userLogins
    $createUserTable = $db ->prepare ("CREATE TABLE IF NOT EXISTS `userLogin`
        (
        `userID` int auto_increment not null,
        `username` varchar (50) not null unique,
        `password` varchar (255) not null,
        `email` varchar (200) not null,
        primary key (`userID`)
        )"

	);
	$createUserTable -> execute(); // this will run the code above if it can
    // end of creation of userLogins 

    // start of create of form table
    $createFormTable= $db ->prepare (
        "CREATE TABLE IF NOT EXISTS formTable(
            userID int not null,
            genre varchar (20),
            duration int not null,
            'year' varchar (20),
            language varchar (200),
            Primary Key (`userID`),
            Foreign Key (`userID`) references userLogin(`userID`)
        );"
	);

	$createFormTable -> execute(); 
    // end of creation of form table

    // creation of userSession
    $createUserSes = $db ->prepare ("CREATE TABLE IF NOT EXISTS userSession(
        session_id varchar (255),
        user_id int not null,
        creation datetime
        );"

    );



    $createUserSes  -> execute(); // this will run the code above if it can
    // end of userSessions
    

    //create of cachedMovie Table
    $cm= $db ->prepare ("CREATE TABLE IF NOT EXISTS `movies`(
          movie_id int auto_increment,
          imbd_id varchar (12) not null,
          title varchar (200) not null,
          description text,
          image text,
          genre varchar(200),
          duration int (10),
          year int (10),
          language varchar (200),
          Primary Key (movie_id)
        );
    ");
    $cm -> execute();    // tnd of create cachedMovie


    // start of userInventory
    $ui= $db ->prepare ("CREATE TABLE IF NOT EXISTS `userInv`(
            movie_id int not null,
            userID int not null,
            Foreign Key (`userID`) references userLogin(`userID`),
            Foreign Key (`movie_id`) references movies(`movie_id`)     
            );
  ");

    $ui -> execute(); // end of userInventory 


    // creating review table
    $rb = $db ->prepare ("CREATE TABLE IF NOT EXISTS `reviews`(
        movie_id int not null,
        userID int not null,
        review_id int auto_increment,
        Primary Key (review_id),
        Foreign Key (`userID`) references userLogin(`userID`),
        Foreign Key (`movie_id`) references movies(`movie_id`)     
        );
    ");

    $rb -> execute(); // end of userInventory 
    



    








 


}  catch (Exception $e){

    echo $e->getMessage();
    echo "it didnt work LMAO";
    exit ('SOMETHING BROKE :(');

}












?>