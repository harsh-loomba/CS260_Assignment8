<?php

//Start session
session_start();

//include connection.php
include_once('connection.php');

//Connect to database
$con = getDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //If all parameters present in post
    if (!isset($_POST["email"], $_POST["password"], $_POST["first_name"], $_POST["confirm_pass"])) {
        $_SESSION['log_msg'] = "Please fill all the required fields.";
    } else {
        $email = convert_input($_POST["email"]);
        $first_name = convert_input($_POST["first_name"]);
        $last_name = convert_input($_POST["last_name"]);
        $password = md5(convert_input($_POST["password"]));
        $confirm_pass = md5(convert_input($_POST["confirm_pass"]));

        //reconfirming passed data
        if ($password === $confirm_pass) {

            $dupli_query = "SELECT DISTINCT `email` FROM `users` WHERE `email` = '$email'";

            $dupli_chk = mysqli_query($con, $dupli_query);

            //User exits?
            if (mysqli_num_rows($dupli_chk) > 0) {
                $_SESSION['log_msg'] = "User already exists!";
            } else {
                //Registering

                $query = "INSERT INTO `users`
                (`id`, `first_name`, `last_name`, `email`, `password`)
                VALUES (NULL, '$first_name', '$last_name', '$email', '$password');";

                $result = mysqli_query($con, $query);

                if ($result) {
                    $_SESSION['log_msg'] = "Registered Successfully!";
                    header('Location: index.php');
                    exit();
                } else {
                    $_SESSION['log_msg'] = "Server Error : User not registered.";
                }
            }
        } else {
            $_SESSION['log_msg'] = "Password and Confirm Password field are not matching!!!";
        }
    }
}
//Redirectiong to register page if user not regitered
header('Location: register.php');
