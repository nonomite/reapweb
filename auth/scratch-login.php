<?php
session_start();


?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="css/reg-and-log.css">
  <script defer src="js/validation.js"></script>

  <title>Reg</title>
</head>
<body>
  <div class="container">
      <div class="container-content">
        <h3>Register here</h3>
        <form id="regForm" action="session.php" method="post">
          <label for="name">Name:</label>
          <input type="text" id="name" name="name" ><br>
          <label for="username">Username:</label>
          <input type="text" id="username" name="username" ><br>
          <label for="password">Password:</label>
          <input type="password" id="password" name="password" ><br>         
          <button type="submit" onsubmit="registrationValidate()" id="submit" class="btn btn-outline-primary">Register</button>
        </form>
    </div>
  </div>
</body>
</html> 