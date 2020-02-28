<?php

  include 'pdosetup.php';

  if (!isset($_COOKIE["userID"])) {
    header("Location: signin.php");
    exit();
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="main.css">
  <title>counter</title>
  <style>
    .container {
      height: 300px;
    }
    .counter {
      display: inline-block;
      height: 200px;
      margin: 0;

      font-size: 150px;
      text-align: center;
      vertical-align: middle;
      line-height: 200px;
    }
  </style>
</head>
<body>
  <div class="container border">
    <p id="counter" class="counter border">

      <?php
        $userID = $_COOKIE["userID"];
        $sql = "SELECT counter FROM users WHERE user_id = '$userID'";
        $stmt = $pdo->query($sql)->fetch();
        echo $stmt["counter"];
      ?>

    </p>
    <form action="signout.php" method="POST" class="border" id="counter-form">
      <fieldset id="actions">
        <input type="button" value="-1" id="decrement">
        <input type="button" value="0" id="reset">
        <input type="button" value="+1" id="increment">
      </fieldset>
      <input type="submit" value="sign out" name="signout">
    </form>
  </div>
</body>

<script>
  // https://plainjs.com/javascript/utilities/set-cookie-get-cookie-and-delete-cookie-5/
  function getCookie(name) {
    var v = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
    return v ? v[2] : null;
  }

  var actions = document.getElementById("actions");
  // console.log("actions:", actions);

  actions.addEventListener("click", function(e) {

    // console.log("evtarget: ", e.target);
    // console.log("evcurtarget: ", e.currentTarget);
    if (e.target !== e.currentTarget) {

      var action = e.target.id;
      console.log("action: ", action);

      var xhr = new XMLHttpRequest();
      console.log("xhr init");
      xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {

          console.log("xhr was completed, code: 200");
          res = JSON.parse(xhr.response);
          console.log("res: ", res);
          var result = res.result;
          if (result === "SUCCESS") {
            counter.textContent = res.num;
            console.log("-------------------------------------------------");

          }
        }
      }

      xhr.open("POST", "action.php");
      xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

      var userID = getCookie("userID");
      console.log("userID(cookie): ", userID);
      xhr.send("userID=" + userID + "&action=" + action);

    }

    e.stopPropagation();

  });

</script>

</html>
