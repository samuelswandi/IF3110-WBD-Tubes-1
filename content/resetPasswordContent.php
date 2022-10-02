<?php
// Initialize the session
// session_start();

// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: index.php");
    exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$oldPassword = $newPassword = $confirmPassword = "";
$oldPasswordErr = $newPasswordErr = $confirmPasswordErr = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // query to database
    $sql = "SELECT id, username, password FROM users WHERE username = ?";

    // mysqli_prepare will return false if error occured
    if($stmt = mysqli_prepare($dbConn, $sql)){

        // bind var to make param of prepared statement
        mysqli_stmt_bind_param($stmt, "s", $paramUsername);

        // set username parameter in the query to $username
        $paramUsername = $_SESSION['username'];

        // execute the prepared statement
        if(mysqli_stmt_execute($stmt)){

            // store result in internal buffer
            mysqli_stmt_store_result($stmt);

            // check username exist in db
            if(mysqli_stmt_num_rows($stmt) == 1){
                // bind result variable
                mysqli_stmt_bind_result($stmt, $id, $username, $hashedPassword);
                if(mysqli_stmt_fetch($stmt) && !password_verify(trim($_POST["oldPassword"]), $hashedPassword)){
                    $oldPasswordErr = "Invalid password.";
                }
            } else{
                // error if username or password invalid
                $oldPasswordErr = "Invalid password.";
            }
        } else{
            // unexpected error
            echo "Unexpected error occured. Please try again later.";
        }
    }


    // Validate new password
    if(empty(trim($_POST["newPassword"]))){
        $newPasswordErr = "Please enter the new password.";
    } else{
        $newPassword = trim($_POST["newPassword"]);
    }

    // Validate confirm password
    if(empty(trim($_POST["confirmPassword"]))){
        $confirmPasswordErr = "Please confirm the password.";
    } else{
        $confirmPassword = trim($_POST["confirmPassword"]);
        if(empty($newPasswordErr) && ($newPassword != $confirmPassword)){
            $confirmPasswordErr = "Password did not match.";
        }
    }

    // Check input errors before updating the database
    if(empty($newPasswordErr) && empty($confirmPasswordErr) && empty($oldPasswordErr)){
        // Prepare an update statement
        $sql = "UPDATE users SET password = ? WHERE id = ?";

        if($stmt = mysqli_prepare($dbConn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "si", $paramPassword, $paramId);

            // Set parameters
            $paramPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $paramId = $_SESSION["id"];

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    else{
        $newPassword = "";
    }
    }

    // Close connection
    mysqli_close($dbConn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <style>
        /* body{ font: 14px sans-serif; } */
        .wrapper{ width: 50%; padding: 30px; height: fit-content; background-color: white; border-radius: 10px;}
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Reset Password</h2>
        <p>Please fill out this form to reset your password.</p>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Old Password</label>
                <input type="password" name="oldPassword" class="form-control <?php echo (!empty($oldPasswordErr)) ? 'is-invalid' : ''; ?>" value="<?php echo $oldPassword; ?>">
                <span class="invalid-feedback"><?php echo $oldPasswordErr; ?></span>
            </div>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="newPassword" class="form-control <?php echo (!empty($newPasswordErr)) ? 'is-invalid' : ''; ?>" value="">
                <span class="invalid-feedback"><?php echo $newPasswordErr; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirmPassword" class="form-control <?php echo (!empty($confirmPasswordErr)) ? 'is-invalid' : ''; ?>">
                <span class="invalid-feedback"><?php echo $confirmPasswordErr; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link ml-2" href="homepage.php">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
