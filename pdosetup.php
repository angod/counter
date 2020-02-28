<?php

// local web server, dbparams not sensitive
$dbhost = "127.0.0.1";
$db = "ttask4ksoft";
$dbuser = "root";
$dbpasswd = "mysqlpasswd";
$charset = "utf8mb4";

$dsn = "mysql:host=$dbhost;dbname=$db;charset=$charset";

$pdoOptions = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
  PDO::ATTR_EMULATE_PREPARES   => false
];

try {
  $pdo = new PDO($dsn, $dbuser, $dbpasswd, $pdoOptions);
} catch (\PDOException $e) {
  throw new \PDOException($e->getMessage(), (int)$e->getCode);
}

?>
