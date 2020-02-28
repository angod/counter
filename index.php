<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Index</title>
</head>
<body>
  <?php
    echo "list of files:<br>";
    if ($handle = opendir('.')) {
      while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
          echo "&nbsp;&nbsp;<a href='$entry'>$entry</a><br>";
        }
      }
      closedir($handle);
    }
  ?>
</body>
</html>