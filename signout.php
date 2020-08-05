<?php

include 'pdosetup.php';

if (!isset($_POST["signout"])) {
  header("Location: signin.php");
  exit();
}

if (isset($_POST["signout"])) {
  // set status=0(offline) and delete cookie
  $userID = $_COOKIE["userID"];
  $status = 0;
  $sql = "UPDATE users SET status = :status WHERE user_id = :userID";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    "status" => $status,
    "userID" => $userID
    ]);

  setcookie("userID", false, time() - 3600);
  unset($_COOKIE);

  header("Location: signin.php");
}

?>