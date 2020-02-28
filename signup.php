<?php

  include 'pdosetup.php';

  // verify and signup if user send credentials
  if (!empty($_POST)) {
    $username = $_POST["username"];
    $passwd = $_POST["password"];
    $birth_date = $_POST["birth_date"];
    $error = "";

    if (empty($username)) {
      $error = "Please fill: username";
    }

    if (empty($passwd)) {
      if (!empty($error)) {
        $error .= ", password";
      } else {
        $error = "Please fill: password";
      }
    }

    if (empty($birth_date)) {
      if (!empty($error)) {
        $error .= ", birth date";
      } else {
        $error = "Please fill: birth date";
      }
    }

    if (empty($error)) {

      $sql = "SELECT * FROM users WHERE username = :username";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([
        "username" => $username
      ]);
      $isExist = $stmt->fetch();
      if (!$isExist) {
        // check age
        $dob = new DateTimeImmutable($birth_date); // dob = date of birth
        $now = new DateTimeImmutable();
        $min_age = $now->sub(new DateInterval("P5Y"));
        $max_age = $now->sub(new DateInterval("P150Y"));

        // echo "dob: " . $dob->format('Y-m-d');
        // echo "<br>now: " . $now->format('Y-m-d');
        // echo "<br>-5y: " . $min_age->format('Y-m-d');
        // echo "<br>-150y: " . $max_age->format('Y-m-d');

        $age = false;
        if ($dob > $now) {
          $error = "back to the future";
        } elseif ($dob > $min_age) {
          $error = "Too young";
        } elseif ($dob < $max_age) {
          $error = "Too old";
        } else {
          $age = true;
        }

        // if age is OK-> signup
        if ($age) {
          $passwd_hash = password_hash($passwd, PASSWORD_ARGON2ID);
          $status = true;

          $sql = "INSERT INTO users(username, password, birth_date, status)
            VALUES(:username, :password, :birth_date, :status)";
          $stmt = $pdo->prepare($sql);
          $stmt->execute([
            "username" => $username,
            "password" => $passwd_hash,
            "birth_date" => $birth_date,
            "status" => $status
            ]);

          $sql = "SELECT user_id FROM users WHERE username = :username";
          $stmt = $pdo->prepare($sql);
          $stmt->execute([
            "username" => $username
          ]);
          $user = $stmt->fetch();

          // set cookie
          $userID = $user["user_id"];
          setcookie("userID", $userID, time() + 86400);

          header("Location: counter.php");

        }
      } else {
        $error .= "username <b>'$username'</b> already in use";
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
  <style>
    input[type=submit] {
      width: 100px;
      margin-left: 99px;
    }
  </style>
  <title>sign up</title>
</head>
<body>
  <div class="container border">
    <p class="error border">
      <?php if (!empty($error)) { echo $error; } ?>
    </p>
    <form action="signup.php" method="POST" class="border">
      <label for="username">username</label>
      <input type="text" name="username"
        <?php if (!empty($username)) { echo "value='$username'"; } ?>
      >
      <br>
      <label for="password">password</label>
      <input type="password" name="password"
        <?php if (!empty($passwd)) { echo "value='$passwd'"; } ?>
      >
      <br>
      <label for="birth_date">birth date</label>
      <input type="date" name="birth_date"
        <?php if (!empty($birth_date)) { echo "value='$birth_date'"; } ?>
      >
      <br>
      <input type="submit" value="sign up" name="signup">
    </form>
  </div>
</body>
</html>

