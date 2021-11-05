<?php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    $query = $mysql->prepare(
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
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>

<body>
    <h1>Login</h1>
    <form action="login.php" method="post">
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
