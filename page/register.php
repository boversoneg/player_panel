<?php
    require_once "config.php";
    
    $username = $password = $confirm_password = "";
    $username_err = $password_err = $confirm_password_err = "";
    
    if($_SERVER["REQUEST_METHOD"] == "POST"){
    
        if(empty(trim($_POST["username"]))){
            $username_err = "Wpisz nazwę użytkownika.";
        } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
            $username_err = "Nazwa użytkownika może zawirać tylko litery, cyfry oraz podłogę.";
        } else{
            $sql = "SELECT id FROM users WHERE username = ?";
            
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "s", $param_username);

                $param_username = trim($_POST["username"]);

                if(mysqli_stmt_execute($stmt)){
                    mysqli_stmt_store_result($stmt);
                    
                    if(mysqli_stmt_num_rows($stmt) == 1){
                        $username_err = "Konto z taką nazwą już istnieje.";
                    } else{
                        $username = trim($_POST["username"]);
                    }
                } else{
                    echo "Coś poszło nie tak! Spróbuj ponownie później lub skontaktuj się z developerem.";
                }

                mysqli_stmt_close($stmt);
            }
        }

        if(empty(trim($_POST["password"]))){
            $password_err = "Wpisz hasło.";     
        } elseif(strlen(trim($_POST["password"])) < 6){
            $password_err = "Hasło musi zawierać conajmniej 6 znaków";
        } else{
            $password = trim($_POST["password"]);
        }

        if(empty(trim($_POST["confirm_password"]))){
            $confirm_password_err = "Potwierdź hasło.";     
        } else{
            $confirm_password = trim($_POST["confirm_password"]);
            if(empty($password_err) && ($password != $confirm_password)){
                $confirm_password_err = "Hasła się nie zgadzają.";
            }
        }

        if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){

            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            
            if($stmt = mysqli_prepare($link, $sql)){
                mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

                $param_username = $username;
                $param_password = password_hash($password, PASSWORD_DEFAULT);

                if(mysqli_stmt_execute($stmt)){
                    header("location: login.php");
                } else{
                    echo "Coś poszło nie tak! Spróbuj ponownie później lub skontaktuj się z developerem.";
                }

                mysqli_stmt_close($stmt);
            }
        }

        mysqli_close($link);
    }
?>
 
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Panel rejestracji</title>
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
        <h2>Panel rejestracji</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group" >
                <label>Nazwa użytkownika</label><br>
                <input type="text" style='width: 360px; vertical-align: middle; display:table-cell;' name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Hasło</label><br>
                <input type="password" style='width: 360px; vertical-align: middle; display:table-cell;' name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Potwierdź hasło</label><br>
                <input type="password" style='width: 360px; vertical-align: middle; display:table-cell;' name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Zarejestruj">
            </div>
            <p>Masz konto? <a href="login.php">zaloguj się</a>.</p>
        </form>   
    </body>
    <script>
        (function() {
            let onpageLoad = localStorage.getItem("theme") || "";
            let element = document.body;
            element.classList.add(onpageLoad);
            document.getElementById("theme").textContent = localStorage.getItem("theme") || "light";
        })();
    </script>
</html>