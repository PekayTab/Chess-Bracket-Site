<?php
/* Your password */
$password = 'bioiscool';
$user = 'admin';

if (empty($_COOKIE['password']) || $_COOKIE['password'] !== $password) {
    // Password not set or incorrect. Send to login.php.
    header('Location: login.php');
    exit;
}