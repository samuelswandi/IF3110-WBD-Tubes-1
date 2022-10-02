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
        $sql = "SELECT id, username, password, is_admin FROM users WHERE username = ?";

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
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashedPassword, $is_admin);
                    if(mysqli_stmt_fetch($stmt)){
                        if(password_verify($password, $hashedPassword)){
                            // session started after password is corret
                            session_start();

                            // store session variable data
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;
                            $_SESSION["loggedin"] = true;
                            $_SESSION["role"] =  $is_admin;

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
    <title>Notey. 📝</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
        .outer-wrapper{height: 100vh; display: flex; justify-content: center; align-items: center }
    </style>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
        <link rel="icon" type="image/png" href="../assets/img/favicon.png">
        <title>Notey. 📝</title>
        <!--     Fonts and icons     -->
        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
        <!-- Nucleo Icons -->
        <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
        <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
        <!-- rc="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
        <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
        <!-- CSS Files -->
        <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.6" rel="stylesheet" />
</head>
<body>
    <div class="outer-wrapper">
        <div class="wrapper">
            <h2>Login</h2>
            <p>Welcome to Notey! 📝</p>

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
                <p>Do not have any account? <a href="register.php">Sign up now!</a></p>
            </form>
        </div>
    </div>
</body>
</html>
