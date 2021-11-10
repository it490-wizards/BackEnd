<?php

require("../include/inserts.php");
require("../include/toAPICall.php");

function setCache()
{
    try {

        require("../include/config.php");
        $db = new PDO("mysql:host=$servername;dbname=FourTestingP", $dbusername, $dbpassword);

        $client = new DatabaseRpcClient();
        $top10 = $client->call("get_top10");

        foreach ($top10 as $movie) {
            setMovie($movie->id, $movie->title, $movie->plot, $movie->image, $movie->genres, intval($movie->runtimeMins), $movie->year, $movie->languages);
        }
    } catch (Exception $e) {
        echo "lmao you didnt make into the server from the api fuck u" . PHP_EOL;
    }
}


setCache();
