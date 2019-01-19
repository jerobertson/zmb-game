<?php
  $server = "db_url";
  $username = "db_user";
  $password = "db_pass";
  $dbname = "db_name";
  $conn = new mysqli($server, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
?>