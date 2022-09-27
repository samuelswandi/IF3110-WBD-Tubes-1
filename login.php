<?php
    require_once "config.php";
    # get user data from post form
    $email = $_POST["email"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    # check if email existed or not
    $query = "SELECT * FROM users WHERE email='$email' AND password='password'";
    $res = mysqli_query($mysqli, $query);
    if (mysqli_num_rows($res) != 1) {
        exit('woe siapa lu ajg');
    } 

    $hashed_password = "";
    if(password_verify($password, $hashed_password)) {
        echo "anjay masuk";
    } 

?>