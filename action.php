<?php

include "pdosetup.php";

if (empty($_POST)) {
  header("Location: signin.php");
  exit();
}

$userID = $_POST["userID"];
$action = $_POST["action"];
$response = null;

if (!empty($userID) && !empty($action)) {

  $counter = "";
  switch ($action) {

    case "decrement":
      $counter .= "counter - 1";
      break;
    case "reset":
      $counter .= "0";
      break;
    case "increment":
      $counter .= "counter + 1";
      break;

  }

  // log section_1 start
  /*ob_start();
  $fname = "action_log.txt";
  $file = fopen($fname, "w");

  if ($file === false) {
    echo "Error in opening new file";
  }

  $now = new DateTimeImmutable();
  $time = $now->format('Y-m-d h:i:s');
  fwrite($file, "fw start: $time\n",);
  var_dump($counter);
  $output = ob_get_clean();
  fwrite($file, $output);*/
  // log section_2 end

  $sql = "UPDATE users SET counter = " . $counter . " WHERE user_id = :userID";
  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    "userID" => $userID,
  ]);

  $sql = "SELECT counter FROM users WHERE user_id = :userID";
  $stmt = $pdo->prepare($sql);
  $stmt->execute(["userID" => $userID]);

  // qres = query result
  $qres = $stmt->fetch();
  $num = $qres["counter"];
  $response = array(
    "result" => "SUCCESS",
    "num" => $num
  );

  // log section_2 start
  /*fwrite($file, $sql . "\n");
  var_dump($response);
  $output = ob_get_clean();
  fwrite($file, $output);
  fwrite($file, "fw end\n");
  fclose($file);*/
  // log section_2 end

} else {

  $response = array(
    "result" => "FAILURE"
  );

}

header("Content-Type: application/json");
echo json_encode($response);

?>