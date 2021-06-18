<?php
    session_start();
    
    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
        header("location: welcome.php");
        exit;
    }
    
    require_once "config.php";

    $username = $password = "";
    $username_err = $password_err = $login_err = "";

    if($_SERVER["REQUEST_METHOD"] == "POST"){

        if(empty(trim($_POST["username"]))){
            $username_err = "Wpisz nazwę użytkownika.";
        } else{
            $username = trim($_POST["username"]);
        }
        
        if(empty(trim($_POST["password"]))){
            $password_err = "Wpisz hasło.";
        } else{
            $password = trim($_POST["password"]);
        }

        if(empty($username_err) && empty($password_err)){
            $sql = "SELECT id, username, password FROM users WHERE username = ?";
            
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_username);

                $param_username = $username;

                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);

                    if(mysqli_stmt_num_rows($stmt) == 1){                    
                        mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                        if(mysqli_stmt_fetch($stmt)){
                            if(password_verify($password, $hashed_password)){
                                session_start();
                                
                                $_SESSION["loggedin"] = true;
                                $_SESSION["id"] = $id;
                                $_SESSION["username"] = $username;                            
                                
                                header("location: welcome.php");
                            } else{
                                $login_err = "Zła nazwa użytkownika lub hasło.";
                            }
                        }
                    } else{
                        $login_err = "Zła nazwa użytkownika lub hasło.";
                    }
                } else{
                    echo "Coś poszło nie tak! Spróbuj ponownie później lub skontaktuj się z developerem.";
                }

                mysqli_stmt_close($stmt);
            }
        }

        mysqli_close($link);
    }
?>

<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Panel logowania</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <style>
            body{ font: 14px sans-serif; text-align: center; }
            .wrapper{ width: 360px; padding: 20px; }
            .dark-mode {
                background-color: #1a1919;
                color: white;
            }
        </style>
    </head>
    <body>
        <h2>Panel logowania</h2>

        <?php 
            if(!empty($login_err)){
                echo '<div class="alert alert-danger">' . $login_err . '</div>';
            }        
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Nazwa użykownika</label><br>
                <input type="text" style='width: 360px; vertical-align: middle; display:table-cell;' name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Hasło</label><br>
                <input type="password" style='width: 360px; vertical-align: middle; display:table-cell;' name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Zaloguj">
            </div>
            <p>Nie masz konta? <a href="register.php">zarejestruj się</a>.</p>
        </form>
    </body>
    <script type="text/javascript" id="cookieinfo" src="//cookieinfoscript.com/js/cookieinfo.min.js" data-message="Używamy plików cookie, aby poprawić Twoje wrażenia. Kontynuując odwiedzanie tej strony, zgadzasz się na używanie przez nas plików cookie." data-linkmsg="Więcej informacji."></script>
    <script>
        (function() {
            let onpageLoad = localStorage.getItem("theme") || "";
            let element = document.body;
            element.classList.add(onpageLoad);
            document.getElementById("theme").textContent = localStorage.getItem("theme") || "light";
        })();
    </script>
</html>