<?php
session_start();
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Witaj</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; text-align: center; }
    </style>
</head>
<body>
    <h1 class="my-5"><b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Witam na stronie</h1>
    <p>
        <a href="reset.php" class="btn btn-warning">Zresetuj hasło</a>
        <a href="logout.php" class="btn btn-danger ml-3">Wyloguj się</a>
    </p>
</body>
</html>