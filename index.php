<!-- LOGIN -->
<?php
// session start
session_start();

// redirect if user has logged in
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    header("location: homepage.php");
    exit;
}

require_once "config.php";

# initial variable, set empty strings
$username = $password = "";
$usernameErr = $passwordErr = $loginErr = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    // set username and password from form
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // if error is empty
    if(empty($usernameErr) && empty($passwordErr)){
        // query to database
        $sql = "SELECT id, username, password FROM users WHERE username = ?";

        // mysqli_prepare will return false if error occured
        if($stmt = mysqli_prepare($dbConn, $sql)){

            // bind var to make param of prepared statement
            mysqli_stmt_bind_param($stmt, "s", $paramUsername);

            // set username parameter in the query to $username
            $paramUsername = $username;

            // execute the prepared statement
            if(mysqli_stmt_execute($stmt)){

                // store result in internal buffer
                mysqli_stmt_store_result($stmt);

                // check username exist in db
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // bind result variable
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashedPassword);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashedPassword)){
                            // session started after password is corret
                            session_start();

                            // store session variable data
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["loggedin"] = true;

                            // redirect user to homepage
                            header("location: homepage.php");
                        } else{
                            // error if username or password invalid
                            $loginErr = "Invalid username or password.";
                        }
                    }
                } else{
                    // error if username or password invalid
                    $loginErr = "Invalid username or password.";
                }
            } else{
                // unexpected error
                echo "Unexpected error occured. Please try again later.";
            }

            // statement close
            mysqli_stmt_close($stmt);
        }
    }

    // close connection
    mysqli_close($dbConn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Noty</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Welcome to Noty!</p>

        <?php
        if(!empty($loginErr)){
            echo '<div class="alert alert-danger">' . $loginErr . '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($usernameErr)) ? 'is-invalid' : ''; ?>" required>
                <span class="invalid-feedback"><?php echo $usernameErr; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($passwordErr)) ? 'is-invalid' : ''; ?>" required>
                <span class="invalid-feedback"><?php echo $passwordErr; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Do not have any account? <a href="register.php">Sign up now!</a>.</p>
        </form>
    </div>
</body>
</html>
