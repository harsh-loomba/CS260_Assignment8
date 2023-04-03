<?php

//start session
session_start();

//include connection.php
include_once('connection.php');

//connect to database
$con = getDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //error for insufficient details
    if (!isset($_POST["email"], $_POST["password"])) {
        $_SESSION['log_msg'] = "Please fill all the required fields.";
    } else {

        //Finding user in database
        $query = "SELECT `email`, `first_name`,`last_name`, `password` FROM `users` WHERE `email` = ?";

        $stmt = $con->prepare($query);
        $stmt->bind_param('s', $_POST['email']);
        $stmt->execute();
        $stmt->store_result();

        //If no rows returned
        if ($stmt->num_rows() == 0) {
            $_SESSION['log_msg'] = "User does not exist!";
            header('Location: index.php');
        } else {
            //User exits
            $stmt->bind_result($email, $first_name, $last_name, $password);
            $stmt->fetch();

            //Check password
            if (md5($_POST["password"]) === $password) {

                //Login session

                session_regenerate_id();
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['first_name'] = $first_name;
                $_SESSION['last_name'] = $last_name;
                $_SESSION['id'] =  $_POST['email'];
                header('Location: home.php');
            } else {
                // Incorrect password
                $_SESSION['log_msg'] = 'Incorrect username and/or password!';
            }
        }

        $stmt->close();
    }
}

//Head back to index if not logged in

header('Location: index.php');
