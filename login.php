<?php

/* Your password */
$password = 'bioiscool';
$user = 'admin';

/* Redirects here after login */
$redirect_after_login = 'admin.php';

/* Will not ask password again for */
$remember_password = strtotime('+30 days'); // 30 days

if (isset($_POST['password']) && $_POST['password'] == $password) {
    if (isset($_POST['user']) && $_POST['user'] == $user) {

        setcookie("password", $password, $remember_password);
        header('Location: ' . $redirect_after_login);
        exit;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Password protected</title>
</head>

<body>
    <div style="text-align:center;margin-top:50px;">
        You must enter the password to view this content.
        <form method="POST">
            <label for="">User</label>
            <input type="text" name="user">
            <br>
            <br>
            <label for="">Password</label>
            <input type="text" name="password">
            <br>
            <br>
            <button type="submit" value="Login">Login</button>
        </form>
    </div>
</body>

</html>