<!-- NEW NOTES -->
<?php
// session start
// session_start();

require_once "config.php";

$newNoteCreated = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){

    // set username and password from form
    $userId = $_SESSION["id"];
    $title = trim($_POST["title"]);
    $content = trim($_POST["content"]);

    // validate title
    if(strlen($title) < 1) {
        $title = "Untitled";
    }

    // query to database
    $sql = "INSERT INTO notes (user_id, title, content) VALUES (?, ?, ?)";

    // mysqli_prepare will return false if error occured
    if($stmt = mysqli_prepare($dbConn, $sql)){

        // bind var to make param of prepared statement
        mysqli_stmt_bind_param($stmt, "sss", $paramUid, $paramTitle, $paramContent);

        // set username parameter in the query to $username
        $paramUid = $userId;
        $paramTitle = $title;
        $paramContent = $content;

        // execute the prepared statement
        if(mysqli_stmt_execute($stmt)){

        } else{
            // unexpected error
            echo "Unexpected error occured. Please try again later.";
        }

        // statement close
        mysqli_stmt_close($stmt);
    }

    // close connection
    mysqli_close($dbConn);

    $newNoteCreated = "New note created!";
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
        /* body{ font: 14px sans-serif; } */
        .wrapper{ width: 50%; padding: 30px; height: fit-content; background-color: white; border-radius: 10px;}
    </style>
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
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- CSS Files -->
    <link id="pagestyle" href="../assets/css/soft-ui-dashboard.css?v=1.0.6" rel="stylesheet" />
</head>
<body>
    <div class="wrapper">
        <h2>Make new notes!</h2>

        <?php
        if(!empty($newNoteCreated)){
            echo '<div class="alert alert-success">' . $newNoteCreated . '</div>';
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Title</label>
                <input type="text" name="title" class="form-control" placeholder="Untitled">
            </div>
            <div class="form-group">
                <label>Content</label>
                <textarea type="text" name="content" class="form-control" rows="5" cols="80"></textarea>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Create!">
                <a class="btn btn-link ml-2" href="homepage.php">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>
