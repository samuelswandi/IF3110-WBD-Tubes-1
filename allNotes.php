<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$res ="tessss";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // query to database
    $sql = "SELECT title, content FROM notes WHERE user_id = ?";

    // mysqli_prepare will return false if error occured
    if($stmt = mysqli_prepare($dbConn, $sql)){

        // bind var to make param of prepared statement
        mysqli_stmt_bind_param($stmt, "s", $paramUserID);

        // set username parameter in the query to $username
        $paramUserID = $_SESSION["id"];

        /* bind result variables */
        mysqli_stmt_bind_result($stmt, $title, $content);

        // execute the prepared statement
        if(mysqli_stmt_execute($stmt)){

            // // store result in internal buffer
            // mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_store_result($stmt)>0){
                /* fetch values */
                while (mysqli_stmt_fetch($stmt)) {
                    $res = '"%s (%s)\n", $title, $content';
                }
            }else{
                $res = "You haven't take any notes!";
            }

        } else{
            // unexpected error
            $res = "Unexpected error occured. Please try again later.";
        }
    }

    // Close connection
    mysqli_close($dbConn);
}
?>
<html>
    <head>
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
    <style>
          .outer-wrapper{height: 60vh; display: flex; justify-content: right; align-items: center; padding: 24vh}
      </style>
    <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.6" rel="stylesheet" />`
    </head>
    <body><?php echo $res; ?></body>
</html>
