<?php
$ini = parse_ini_file(__DIR__ . "/../config.ini", true);

if ($ini)
    [
        "hostname" => $servername,
        "username" => $dbusername,
        "password" => $dbpassword,
        "database" => $dbname
    ] = $ini["mysql"];
else
    die("Failed to parse config.ini");
