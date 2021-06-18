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

            .dark-mode {
                background-color: #1a1919;
                color: white;
            }
        </style>
    </head>
    <body onload="loadpage()">
        <h1 class="my-5"><b><?php echo htmlspecialchars($_SESSION["username"]); ?></b>. Witam na stronie</h1>
        <p>
            <a href="reset.php" class="btn btn-warning">Zmień hasło</a>
            <a href="logout.php" class="btn btn-danger ml-3">Wyloguj się</a>
            <button id='button' class="btn btn-dark ml-3" onclick="themeToggle()">Dark mode</button>
        </p>
    </body>

    <script>
        function loadpage() {
            let onpageLoad = localStorage.getItem("theme") || "";
            
            let element = document.body;
            element.classList.add(onpageLoad);

            let theme = localStorage.getItem("theme");
            if (theme === "dark-mode") {
                document.getElementById('button').innerHTML = 'Light mode';
                document.getElementById("button").className = "btn btn-light ml-3";
            } else {
                document.getElementById('button').innerHTML = 'Dark mode';
                document.getElementById("button").className = "btn btn-dark ml-3";
            }
        }

        function themeToggle() {
            let element = document.body;
            element.classList.toggle("dark-mode");

            let theme = localStorage.getItem("theme");
            if (theme && theme === "dark-mode") {
                localStorage.setItem("theme", "");
                document.getElementById('button').innerHTML = 'Dark mode';
                document.getElementById("button").className = "btn btn-dark ml-3";
            } else {
                localStorage.setItem("theme", "dark-mode");
                document.getElementById('button').innerHTML = 'Light mode';
                document.getElementById("button").className = "btn btn-light ml-3";
            }
        }
    </script>
</html>