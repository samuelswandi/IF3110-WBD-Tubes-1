<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="./styles/styles.css">

  <!-- jQuery CDN Link -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <title>Transparent form</title>
</head>

<body>
  <div class="container">
    <div class="form">
      <div class="btn">
        <button class="signUpBtn">SIGN UP</button>
        <button class="loginBtn">LOG IN</button>
      </div>
      <form class="signUp" action="register.php" method="post">
        <div class="formGroup">
          <input type="name" placeholder="Username" name="username" required autocomplete="off">
        </div>
        <div class="formGroup">
          <input type="email" placeholder="Email" name="email" required autocomplete="off">
        </div>
        <div class="formGroup">
          <input type="password" id="signupPassword" name="password" placeholder="Password" required autocomplete="off">
        </div>
        <div class="formGroup">
          <input type="password" id="confirmPassword" placeholder="Confirm Password" required autocomplete="off">
        </div>
        <div class="formGroup">
          <input type="submit" class="btn2" value="REGISTER">
        </div>

      </form>

      <!------ Login Form -------- -->
      <form class="login" action="login.php" method="get">

        <div class="formGroup">
          <input type="email" placeholder="Email ID" name="email" required autocomplete="off">
        </div>
        <div class="formGroup">
          <input type="password" id="loginPassword" name="password" placeholder="Password" required autocomplete="off">
        </div>
        <div class="formGroup">
          <input type="submit" class="btn2" value="LOGIN">
        </div>

      </form>

    </div>
  </div>

  <script src="./styles/jQuery.js"></script>
</body>

</html>
