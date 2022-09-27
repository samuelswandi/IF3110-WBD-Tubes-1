<?php
    # connect to local mysql
    $mysqli = new mysqli("127.0.0.1","root","password","php_mysql", 3306);
    if ($mysqli -> connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
    }

    # get user data from post form
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    # make query
    $query = "INSERT INTO users (username, email, password, is_admin) 
        VALUES ('$username', '$email','$password', 0)";

    if (mysqli_query($mysqli, $query)) {
        echo "New record created successfully";
      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      }
?>