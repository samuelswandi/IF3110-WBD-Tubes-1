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
// $username = $password = $confirmPassword = "";
$usernameErr = $passwordErr = $confirmPasswordErr = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    // username validation
    if(empty(trim($_POST["username"]))){
        $usernameErr = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $usernameErr = "Username can only contain letters, numbers, and underscores.";
    } else{
        // query to database
        $sql = "SELECT id FROM users WHERE username = ?";

        // mysqli_prepare will return false if error occured
        if($stmt = mysqli_prepare($dbConn, $sql)){

            // bind var to make param of prepared statement
            mysqli_stmt_bind_param($stmt, "s", $paramUsername);

            // set username parameter in the query to username from form
            $paramUsername = trim($_POST["username"]);

            // execute the prepared statement
            if(mysqli_stmt_execute($stmt)){

                // store result in internal buffer
                mysqli_stmt_store_result($stmt);

                // check username exist in db
                if(mysqli_stmt_num_rows($stmt) == 1){
                    // if exist return error
                    $usernameErr = "This username is already taken.";
                } else{
                    // if not exist set username variable
                    $username = trim($_POST["username"]);
                }
            } else{
                // unexpected error
                echo "Unexpected error occured. Please try again later.";
            }

            // statement close
            mysqli_stmt_close($stmt);
        }
    }

    // password validation
    if(empty(trim($_POST["password"]))){
        $passwordErr = "Please enter a password.";
    } else{
        $password = trim($_POST["password"]);
    }

    // password confirmation validation
    if(empty(trim($_POST["confirmPassword"]))){
        $confirmPasswordErr = "Please reenter your password.";
    } else{
        $confirmPassword = trim($_POST["confirmPassword"]);
        if(empty($passwordErr) && ($password != $confirmPassword)){
            $confirmPasswordErr = "Your password and confirmation password must match";
        }
    }

    // verify no erors occured
    if(empty($usernameErr) && empty($passwordErr) && empty($confirmPasswordErr)){

        // query insert to database
        $sql = "INSERT INTO users (username, password) VALUES (?, ?)";

        // mysqli_prepare will return false if error occured
        if($stmt = mysqli_prepare($dbConn, $sql)){

            // bind var to make param of prepared statement
            mysqli_stmt_bind_param($stmt, "ss", $paramUsername, $paramPassword);

            // set username and password parameter
            $paramUsername = $username;
            // store password in hashed
            $paramPassword = password_hash($password, PASSWORD_DEFAULT);

            // execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // redirect to login page
                header("location: index.php");
            } else{
                // unexpected error
                echo "Unexpected error occured. Please try again later.";
            }

            // close statement
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
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; }
        .wrapper{ width: 360px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Welcome to Noty!</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($usernameErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>" required>
                <span class="invalid-feedback"><?php echo $usernameErr; ?></span>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($passwordErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>" required>
                <span class="invalid-feedback"><?php echo $passwordErr; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirmPassword" class="form-control <?php echo (!empty($confirmPasswordErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirmPassword; ?>" required>
                <span class="invalid-feedback"><?php echo $confirmPasswordErr; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
            </div>
            <p>Already have an account? <a href="index.php">Login here</a>.</p>
        </form>
    </div>
</body>
</html>
