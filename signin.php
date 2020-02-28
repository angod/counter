<?php

  if (isset($_POST["signup"])) {
    header("Location: signup.php");
    exit();
  }

  include "pdosetup.php";

  // verify and login if user send credentials
  if (!empty($_POST)) {
    $username = $_POST["username"];
    $passwd = $_POST["password"];

    $error = "";

    if (empty($username)) {
      $error = "Please enter: username";
    }

    if (empty($passwd)) {
      if (!empty($error)) {
        $error .= ", password";
      } else {
        $error = "Please enter: password";
      }
    }

    if (empty($error)) {
      $sql = "SELECT user_id, password FROM users WHERE username = :username";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        "username" => $username
      ]);

      /*ob_start();
      $fname = "log.txt";
      $file = fopen($fname, "w");
      if ($file == false) {
        echo "Error in opening new file";
      }
      $now = new DateTimeImmutable();
      $time = $now->format('Y-m-d h:i:s');
      fwrite($file, "fw start: $time\n",);
      fwrite($file, $sql . "\n");
      // $rows = $stmt->fetchAll();
      // var_dump($rows);
      $output = ob_get_clean();
      fwrite($file, $output);
      fwrite($file, "fw end\n");
      fclose($file);*/

      $user = $stmt->fetch();
      // if user exist in DB
      if ($user) {
        $passwd_hash = $user["password"];
        if (password_verify($passwd, $passwd_hash)) {
          // set cookie
          $userID = $user["user_id"];
          setcookie("userID", $userID, time() + 86400);

          // update status=1(online)
          $status = true;
          $sql = "UPDATE users SET status = :status WHERE user_id = :userID";
          $stmt = $pdo->prepare($sql);
          $stmt->execute([
            "status" => $status,
            "userID" => $userID
            ]);

          // redirect ----> counter.php
          header("Location: counter.php");
        } else {
          $error = "password incorrect";
        }
      } else {
        $error = "username <b>'$username'</b> doesn't exist";
      }
    }

    if (!empty($error)) {
      $error .= "!";
    }
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="main.css">
  <title>sign in</title>
</head>
<body>
  <div class="container border">
    <p class="error border">
      <?php if (!empty($error)) { echo $error; } ?>
    </p>
    <form action="signin.php" method="POST" class="border">
      <label for="username">username</label>
      <input type="text" name="username"
        <?php if (!empty($username)) { echo "value='$username'"; } ?>
      >
      <br>
      <label for="password">password</label>
      <input type="password" name="password">
      <br>
      <input type="submit" value="sign in" name="signin">
      <input type="submit" value="sign up" name="signup">
    </form>
  </div>
</body>
</html>