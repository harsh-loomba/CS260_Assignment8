<?php

session_start();

if (!isset($_SESSION['loggedin'])) {
    header('Location: index.html');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
</head>

<body>
    <nav>
        <a href="logout.php">Logout</a>
        <a href="home.php">Home</a>
    </nav>

    <div>
        <h2>Profile</h2>
        <p>First Name: <?= $_SESSION['first_name'] ?><br></p>
        <p>Last Name: <?= $_SESSION['last_name'] ?><br></p>
        <p>Email: <?= $_SESSION['id'] ?><br></p>

    </div>

    <div class="container">
        <input type="button" class="button_active" onclick="location.href='edit_profile.php';" value="Edit Profile">
        <br>
    </div>

</body>

</html>