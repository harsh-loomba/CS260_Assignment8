<?php

//Start session
session_start();

//User not logged in : Redirect to index.php
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}

//Including connection
include_once('connection.php');

//Connecting to database
$con = getDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //Checkign if all parameters passed
    if (!isset($_POST["first_name"], $_POST["last_name"], $_POST['password'])) {
        $_SESSION['log_msg'] = "Please fill all the required fields. Names cannot be empty strings";
    } else {

        //Querying user details
        $query = "SELECT `id`, `email`, `first_name`,`last_name`, `password` FROM `users` WHERE `email` = ?";

        $stmt = $con->prepare($query);
        $stmt->bind_param('s', $_SESSION['id']);
        $stmt->execute();
        $stmt->store_result();

        // If user exists
        if ($stmt->num_rows() == 0) {
            $_SESSION['log_msg'] =  "ERROR: User does not exist.";
        } else {

            $stmt->bind_result($ID, $email, $first_name, $last_name, $password);
            $stmt->fetch();

            if (md5($_POST["password"]) === $password) {

                $first_name = convert_input($_POST["first_name"]);
                $last_name = convert_input($_POST["last_name"]);
                $email = $_SESSION['id'];
                $updated_pass = $password;

                // If password changed
                if (isset($_POST['new_pass'], $_POST['confirm_pass'])) {
                    if ($_POST['new_pass'] != "") {
                        if ($_POST['new_pass'] === $_POST['confirm_pass']) {
                            $updated_pass = md5($_POST['new_pass']);
                        } else {
                            $_SESSION['log_msg'] = "Password and Confirm Password fields are not matching!!!";
                            header('Location: edit_profile.php');
                        }
                    }
                }

                // Update user in database

                $update_query = "UPDATE `users`
                SET `first_name` = '$first_name',
                `last_name` = '$last_name',
                `password` = '$updated_pass'
                WHERE `id` = '$ID'";

                $result = mysqli_query($con, $update_query);

                if ($result) {
                    // If update successful, also update session variables
                    echo "Details Updated Successfully";
                    $_SESSION['first_name'] = $first_name;
                    $_SESSION['last_name'] = $last_name;

                    //Redirect to profile page
                    header('Location: profile.php');
                } else {
                    $_SESSION['log_msg'] = "Unexpected error: Details not changed.";
                }
            } else {
                // Incorrect password
                $_SESSION['log_msg'] =  'Incorrect password!';
            }
        }

        $stmt->close();
    }
}

//Redirect to edit_profile.php if any error
header('Location: edit_profile.php');
