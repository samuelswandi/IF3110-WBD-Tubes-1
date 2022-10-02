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
if($_SERVER["REQUEST_METHOD"] == "GET"){
    // query to database
    $sql = "SELECT title, content, FROM notes WHERE user_id = ?";

    // mysqli_prepare will return false if error occured
    if($stmt = mysqli_prepare($dbConn, $sql)){

        // bind var to make param of prepared statement
        mysqli_stmt_bind_param($stmt, "s", $paramUserID);

        // set username parameter in the query to $username
        $paramUserID = $_SESSION['id'];

        // execute the prepared statement
        if(mysqli_stmt_execute($stmt)){

            // store result in internal buffer
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_store_result($stmt)>0){
                echo $stmt;
            }else{
                echo "You haven't take any notes!";
            }

        } else{
            // unexpected error
            echo "Unexpected error occured. Please try again later.";
        }
    }

    // Close connection
    mysqli_close($dbConn);
}
?>
<html>
    
</html>
