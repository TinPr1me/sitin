<?php

@include 'config.php';

session_start();

if(!isset($_SESSION['user_name'])){
   header('location:login_form.php');
}

$id = $_SESSION['id'];
$select = " SELECT * FROM student WHERE id = '$id' ";
$result = mysqli_query($conn, $select);

$row = mysqli_fetch_array($result);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Settings</title>
    <link rel="stylesheet" href="css/dashboards.css">
</head>
<body>
    <header>
    <nav class="navbar">
            <a href="user_page.php" class="img">SeatFlow <span>.</span></a>         
        </nav>
    </header>

    <div class="container">
        <div class="content">
            <p class="welcome-text">User Settings<span></p>     
        </div>
    </div>

</body>
</html>