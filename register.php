<?php
    require_once "config.php";
    # get user data from post form
    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    # check if username existed or not
    $query = "SELECT * FROM users WHERE username='$username'";
    $res = mysqli_query($mysqli, $query);
    if (mysqli_num_rows($res) > 0) {
        exit('This username already exists');
    }

    # check if email existed or not
    $query = "SELECT * FROM users WHERE email='$email'";
    $res = mysqli_query($mysqli, $query);
    if (mysqli_num_rows($res) > 0) {
        exit('This email already exists');
    }

    # make query
    $query = "INSERT INTO users (username, email, password, is_admin) 
        VALUES ('$username', '$email','$password', 0)";

    if (mysqli_query($mysqli, $query)) {
        echo "New record created successfully";
      } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
      }
    
?>