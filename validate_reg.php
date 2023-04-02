<?php

include_once('connection.php');

$con = getDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["email"], $_POST["password"], $_POST["first_name"], $_POST["confirm_pass"])) {
        exit("PLease fill all the required fields.");
    } else {
        $email = convert_input($_POST["email"]);
        $first_name = convert_input($_POST["first_name"]);
        $last_name = convert_input($_POST["last_name"]);
        $password = md5(convert_input($_POST["password"]));
        $confirm_pass = md5(convert_input($_POST["confirm_pass"]));

        if ($password === $confirm_pass) {

            $dupli_query = "SELECT DISTINCT `email` FROM `users` WHERE `email` = '$email'";

            $dupli_chk = mysqli_query($con, $dupli_query);

            if (mysqli_num_rows($dupli_chk) > 0) {
                echo "ERROR: User already exists.";
            } else {
                $query = "INSERT INTO `users`
                (`id`, `first_name`, `last_name`, `email`, `password`)
                VALUES (NULL, '$first_name', '$last_name', '$email', '$password');";

                $result = mysqli_query($con, $query);

                if ($result) {
                    echo "Registered Successfully";
                    header('Location: index.php');
                } else {
                    exit("ERROR: User not registered.");
                }
            }
        } else {
            exit("ERROR: Password and Confirm Password field are not matching!!!");
        }
    }
}
