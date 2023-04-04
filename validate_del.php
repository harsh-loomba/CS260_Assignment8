<?php

//start session
session_start();

//include connection.php
include_once('connection.php');

//connect to database
$con = getDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //error for insufficient details
    if (!isset($_POST["password"])) {
        $_SESSION['log_msg'] = "Please fill all the required fields.";
    } else {

        //Finding user in database
        $query = "SELECT `email`, `password` FROM `users` WHERE `email` = ?";

        $stmt = $con->prepare($query);
        $stmt->bind_param('s', $_SESSION['id']);
        $stmt->execute();
        $stmt->store_result();

        //If no rows returned
        if ($stmt->num_rows() == 0) {
            $_SESSION['log_msg'] = "User does not exist!";
            header('Location: index.php');
        } else {
            //User exits
            $stmt->bind_result($email, $password);
            $stmt->fetch();

            //Check password
            if (md5($_POST["password"]) === $password) {

                //Delete Account

                $query = "DELETE FROM `users`
                WHERE `email` = '$email';";

                $result = mysqli_query($con, $query);

                if ($result) {
                    //Logout
                    session_destroy();

                    // Redirect to the login page
                    //Restart session
                    session_start();
                    //Set log message
                    $_SESSION['log_msg'] = "Account has been deleted.";
                    header('Location: index.php');
                    exit();
                } else {
                    $_SESSION['log_msg'] = "Server Error : User not deleted.";
                }
            } else {
                // Incorrect password
                $_SESSION['log_msg'] = 'Incorrect username and/or password!';
            }
        }

        $stmt->close();
    }
}

//Head back to index if account not deleted

header('Location: delete.php');
