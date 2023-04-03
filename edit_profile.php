<?php

session_start();

//User not logged in : Redirect to index.php
if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}

//Displaying log message
$log = '';

if (isset($_SESSION['log_msg'])) {
    $log = $_SESSION['log_msg'];
    unset($_SESSION['log_msg']);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Edit Profile</title>

    <script>
        // Javascript function to check frontend password matching
        var check = function() {
            if (document.getElementById('password').value ==
                document.getElementById('confirm_pass').value) {
                document.getElementById('message').style.color = 'green';
                document.getElementById('message').innerHTML = '';
            } else {
                document.getElementById('message').style.color = 'red';
                document.getElementById('message').innerHTML = 'Password and Confirm Password fields are not matching!!!';
            }
        }
    </script>

</head>

<body>
    <div class="session">

        <div class="left">
            <svg enable-background="new 0 0 300 302.5" version="1.1" viewBox="0 0 300 302.5" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                <style type="text/css">
                    .st01 {
                        fill: #fff;
                    }
                </style>
                <path class="st01" d="m126 302.2c-2.3 0.7-5.7 0.2-7.7-1.2l-105-71.6c-2-1.3-3.7-4.4-3.9-6.7l-9.4-126.7c-0.2-2.4 1.1-5.6 2.8-7.2l93.2-86.4c1.7-1.6 5.1-2.6 7.4-2.3l125.6 18.9c2.3 0.4 5.2 2.3 6.4 4.4l63.5 110.1c1.2 2 1.4 5.5 0.6 7.7l-46.4 118.3c-0.9 2.2-3.4 4.6-5.7 5.3l-121.4 37.4zm63.4-102.7c2.3-0.7 4.8-3.1 5.7-5.3l19.9-50.8c0.9-2.2 0.6-5.7-0.6-7.7l-27.3-47.3c-1.2-2-4.1-4-6.4-4.4l-53.9-8c-2.3-0.4-5.7 0.7-7.4 2.3l-40 37.1c-1.7 1.6-3 4.9-2.8 7.2l4.1 54.4c0.2 2.4 1.9 5.4 3.9 6.7l45.1 30.8c2 1.3 5.4 1.9 7.7 1.2l52-16.2z" />
            </svg>
        </div>

        <!-- Update form -->

        <form method="post" action="update.php" name="Update">

            <h4>Update <span>PROFILE</span></h4>
            <p>Leave New Password field empty if you don't want to change your password.</p>

            <!-- Printing log -->
            <span style="color:red;"><?= $log ?></span>

            <div class="floating label">
                <input placeholder="First Name" type="text" name="first_name" pattern="^[a-zA-Z][a-zA-Z\s]*$" title="Names cannot contain digits or special characters." value=<?= $_SESSION['first_name'] ?> required />
            </div>

            <div class="floating label">
                <input placeholder="Last Name" type="text" name="last_name" pattern="^[a-zA-Z][a-zA-Z\s]*$" title="Names cannot contain digits or special characters." value=<?= $_SESSION['last_name'] ?> />
            </div>

            <div class="floating label">
                <input placeholder="New Password" type="password" id="password" name="new_pass" pattern=".{8,100}" title="Passwords should be 8 - 100 characters." onkeyup='check();' />
            </div>

            <div class="floating label">
                <input placeholder="Confirm New Password" type="password" id="confirm_pass" name="confirm_pass" onkeyup='check();' />
            </div>

            <span id='message'></span>

            <div class="floating label">
                <input placeholder="Old Password" type="password" name="password" pattern=".{8,100}" title="Passwords should be 8 - 100 characters." required />
            </div>

            <button type="submit" value="Login">Save</button>

            <a href="profile.php" class="discrete">Back to Profile</a>

    </div>
</body>

</html>