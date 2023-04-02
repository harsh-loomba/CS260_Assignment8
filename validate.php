<?php

session_start();

include_once('connection.php');

$con = getDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["email"], $_POST["password"])) {
        exit("PLease fill all the required fields.");
    } else {

        $query = "SELECT `email`, `first_name`,`last_name`, `password` FROM `users` WHERE `email` = ?";

        $stmt = $con->prepare($query);
        $stmt->bind_param('s', $_POST['email']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows() == 0) {
            echo "ERROR: User does not exist.";
        } else {
            $stmt->bind_result($email, $first_name, $last_name, $password);
            $stmt->fetch();

            if (md5($_POST["password"]) === $password) {
                session_regenerate_id();
                $_SESSION['loggedin'] = TRUE;
                $_SESSION['first_name'] = $first_name;
                $_SESSION['last_name'] = $last_name;
                $_SESSION['id'] =  $_POST['email'];
                header('Location: home.php');
            } else {
                // Incorrect password
                echo 'Incorrect username and/or password!';
            }
        }

        $stmt->close();
    }
}
