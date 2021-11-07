<?php

    // JNCV
    // functions that get information belong in here. 
    //refer to the doc jose made for the names 

    
/*
    function seefoo (){
    
        try{
            
            require 'config.php'; // to get the sensitive stuff :^)
            $db = new PDO ("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
            // echo "YOU DID IT POGU you are connected to sql!\n" ; // DEBUG
            $testshow = $db->prepare("SELECT * from testingcon");
            $testshow->execute();
            $results = $testshow->fetchall (PDO::FETCH_ASSOC); // gets all the data from query       
            $readable = json_encode($results);        
            echo $readable.PHP_EOL;	
            return $readable;
            
    
        }catch(PDOException $e){
            echo "Gosh you couldn't connect :( " .$e->getMessage ();
            }	
    }
    */


    // this will get the movies that the user saved aka userInv
    // because this table stores all of the users, you will have to get the userID to filter out their sepcific saves

    function get_saved ($userID){
        // put this into a config.php and use a require once :)
    
        try{
            
            require 'config.php'; // to get the sensitive stuff :^)
            $db = new PDO ("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
            $showINV = $db->prepare("SELECT userLogin.userID, userInv.movie_id, title, `description` 
            from movies 
            where userID = :userID 
            AND userInv.userID = userLogin.userID
            AND movies.movie_id = reviews.movie_id
            group by reviews.movie_id");
            $v = array (":userID"=>$userID);
            $showINV->execute($v);
            
            $results = $showINV->fetchall (PDO::FETCH_ASSOC); // gets all the data from query
            
            //$readable = json_encode($results); // makes it able to send it over
                $readable = $results;
          //  echo $readable.PHP_EOL;	
            return $readable;

        }catch(PDOException $e){
            echo "function get saved failed " .$e->getMessage ();
            }	
    }

    // this is to get the movies recomendation 
    // this will rely on the userLogin, formTable (holds the preferences), and movie tablesS
    function getRecommended ($userID){
        try{
            
            require 'config.php'; // to get the sensitive stuff :^)
            $db = new PDO ("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);
            
            $eRec = $db->prepare("SELECT 
            userLogin.userID, movies.movie_id, title, description 
            FROM movies, userLogin, formTable
            where userLogin.userID = :userID and form.userID = userLogin.userID 
            and((formTable.genre=movies.genre and formTable.duration=movies.duration) 
            OR (formTable.duration=movies.duration AND ) ) ");
            $v = array (":userID"=>$userID);
            $eRec->execute($v); // lol get honry bonked
            
            $results = $showINV->fetchall (PDO::FETCH_ASSOC); // gets all the data from query
            
           // $readable = json_encode($results);
            $readable = $results;

            echo $readable.PHP_EOL;	
            return $readable;

        }catch(PDOException $e){
            echo "Gosh you couldn't connect :( " .$e->getMessage ();
            }

        
            
            


    }// end of get reccs

    // to get all the reviews of a given user
    // gr the movieID, title, secription, reviewrating of the movie, and the reviewtext
    function getReviews ($userID){

        try{
            
            require 'config.php'; // to get the sensitive stuff :^)
            $db = new PDO ("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);

            $testshow = $db->prepare("SELECT userLogin.userID, userInv.movie_id, title, `description`, reviewRating, reviewText 
            FROM movies, userLogin, userInv
            WHERE userID=:userID 
            AND userInv.userID = userLogin.userID
            AND movies.movie_id = userInv.movie_id
            group by userInv.movie_id");

            $v = array (":userID"=>$userID);
            $testshow->execute($v);

            $results = $testshow->fetchall (PDO::FETCH_ASSOC); // gets all the data from query       
           // $readable = json_encode($results);  
            $readable = $results;

           // echo $readable.PHP_EOL;	//
            return $readable;
            
    
        }catch(PDOException $e){
            echo "function getReivew failed. " .$e->getMessage ();
        }	// end of try/catch

    } // end of getReview

    //get movie (gets the titles and descriptions of a movie)
    function getMovie($movie_id){
        try {
            require 'config.php'; // to get the sensitive stuff :^)
            $db = new PDO ("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);

            $go = $db->prepare("SELECT title, `description` from `movies` where movie_id = :movie_id ");
            $v = array(":movie_id"=>$movie_id);
            $go->execute($v);

            $results = $go->fetchall (PDO::FETCH_ASSOC); // gets all the data from query       
          //  $readable = json_encode($results); 
          $readable = $results;
  
          return $readable; 



        } catch (Exception $e) {
            echo "You cannot get the movies at the momment" . $e->getMessage(). PHP_EOL;
        }
    }// get movie end 


    // getALL reviews
    // you are looking for all of the reviews and the review's information on a SINGLE movie
    
    function getAllReivews ($movie_id){  

        try {
            
            require 'config.php'; // to get the sensitive stuff :^)
            $db = new PDO ("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword); // connect to db

            $mmm = $db->prepare("SELECT * from `reviews` where movie_id = :movie_id");
            $v = array(":movie_id"=>$movie_id);
            $mmm->execute($v);

            $results = $mmm->fetchall (PDO::FETCH_ASSOC); // gets all the data from query       
            //$readable = json_encode($results);  
            $readable = $results;

            return $readable;



        } catch (Exception $e) {
        }

    }

    





    



?>