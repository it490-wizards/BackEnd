<?php




function doLogin ($username, $password){
    require("../config.php");
    $db = new PDO ("mysql:host=$servername;dbname=$dbname", $dbusername, $dbpassword);

    $query = $db->prepare(
        "SELECT
            user_id,
            username,
            password_hash,
            salt
        FROM
            user
        WHERE
            username=?
        "
    );
    
    $query->bind_param("s", $username);
    $query->execute();
    $result = $query->get_result();

    if (
        $result
        && ($user = $result->fetch_object())
        && hash("sha256", $user->salt . $password, true) === $user->password_hash
    ) {
        echo "Welcome {$user->username}!";
    } else {
        echo "Incorrect username or password";
    }


}



?>