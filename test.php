<?php
  $server = "server.microlite2.com";
  $username = "db_admin";
  $password = "p:c75gi;8@#FcAfuPm*SY";
  $dbname = "zmb";
  $conn = new mysqli($server, $username, $password, $dbname);
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  
  $locations = array();
  $stmt = $conn->prepare("SELECT minDanger,maxDanger,danger,x,y FROM zmb_rw_positions WHERE session=?");
  $stmt->bind_param("s", $_GET['k']);
  $stmt->execute();
  $stmt->bind_result($locMinDanager, $locMaxDanger, $locDanger, $locX, $locY);
  while ($stmt->fetch()) {
    if (!array_key_exists($locX, $locations)) {
      $locations[$locX] = array();
    }
    $locations[$locX][$locY] = max($locMinDanger, $locDanger - 2);
  }
  $stmt->close();
  print json_encode($locations);
?>