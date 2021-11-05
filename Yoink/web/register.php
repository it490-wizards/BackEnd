<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $salt = random_bytes(16);
    $password_hash = hash("sha256", $salt . $password, true);

    $query = $mysql->prepare(
        "INSERT INTO user(
            username,
            password_hash,
            salt
        ) VALUES (
            ?, ?, ?
        )"
    );
    $query->bind_param("sss", $username, $password_hash, $salt);
    if ($query->execute())
        echo "Success";
    else
        echo "Failure";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Register</title>
</head>

<body>
    <h1>Register</h1>
    <form action="register.php" method="post">
        <label for="input-username">
            Username
            <input type="text" name="username" id="input-username">
        </label>
        <label for="input-password">
            Password
            <input type="password" name="password" id="input-password">
        </label>
        <input type="submit" value="Login">
    </form>
</body>

</html>
