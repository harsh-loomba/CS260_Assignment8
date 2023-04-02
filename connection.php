<?php

function getDB()
{
    define('USER', 'root');
    define('PASSWORD', '');
    define('HOST', 'localhost');
    define('DATABASE', 'dblab8');

    $conn = new mysqli(HOST, USER, PASSWORD, DATABASE);

    if ($conn->connect_error) {
        die("Connection failed: $conn -> connect_error \n");
    }

    return $conn;
}

function convert_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);

    return $data;
}
