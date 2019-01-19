<?php
  $server = "jerobertson.co.uk";
  $username = "jerobert_master";
  $password = "rRe!LX\M7Ljx}r4t+2g\p";
  $dbname = "jerobert_jerobertson_co_uk_zmb";
  $conn = new mysqli($server, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
?>