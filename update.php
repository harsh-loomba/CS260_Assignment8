<?php

session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}

include_once('connection.php');

$con = getDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST["first_name"], $_POST["last_name"], $_POST['password'])) {
        exit("Please fill all the required fields. Names cannot be empty strings");
    } else {

        $query = "SELECT `id`, `email`, `first_name`,`last_name`, `password` FROM `users` WHERE `email` = ?";

        $stmt = $con->prepare($query);
        $stmt->bind_param('s', $_SESSION['id']);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows() == 0) {
            echo "ERROR: User does not exist.";
        } else {

            $stmt->bind_result($ID, $email, $first_name, $last_name, $password);
            $stmt->fetch();

            if (md5($_POST["password"]) === $password) {

                $first_name = convert_input($_POST["first_name"]);
                $last_name = convert_input($_POST["last_name"]);
                $email = $_SESSION['id'];
                $updated_pass = $password;

                if (isset($_POST['new_pass'], $_POST['confirm_pass'])) {
                    if ($_POST['new_pass'] != "") {
                        if ($_POST['new_pass'] === $_POST['confirm_pass']) {
                            $updated_pass = md5($_POST['new_pass']);
                        } else {
                            exit("ERROR: Password and Confirm Password fields are not matching!!!");
                        }
                    }
                }

                $update_query = "UPDATE `users`
                SET `first_name` = '$first_name',
                `last_name` = '$last_name',
                `password` = '$updated_pass'
                WHERE `id` = '$ID'";

                $result = mysqli_query($con, $update_query);

                if ($result) {
                    echo "Details Updated Successfully";
                    $_SESSION['first_name'] = $first_name;
                    $_SESSION['last_name'] = $last_name;
                    header('Location: profile.php');
                } else {
                    exit("ERROR: Details not changed.");
                }
            } else {
                // Incorrect password
                echo 'Incorrect password!';
            }
        }

        $stmt->close();
    }
}
