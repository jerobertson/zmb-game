<?php
  $stmt = $conn->prepare("SELECT x,y FROM zmb_rw_sessions WHERE id=?");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->bind_result($x, $y);
  $stmt->fetch();
  $stmt->close();
  
  $direction = array("x"=>$x,"y"=>$y);
  $destination = "";
  
  $stmt = $conn->prepare("SELECT x,y,altName FROM zmb_rw_positions WHERE session=?");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->bind_result($locX, $locY, $locAltName);
  while ($stmt->fetch()) {
    if ($campName == trim($locAltName, " .")) {
      $destination = strtolower(trim($locAltName, " ."));
      if ($x < $locX) {
        $direction = array("x"=>$x+1,"y"=>$y);
      }
      else if ($x > $locX) {
        $direction = array("x"=>$x-1,"y"=>$y);
      }
      if ($direction == array("x"=>$x,"y"=>$y) || rand(0, 1) == 0) {
        if ($y < $locY) {
          $direction = array("x"=>$x,"y"=>$y+1);
        }
        else if ($y > $locY) {
          $direction = array("x"=>$x,"y"=>$y-1);
        }
      }
    }
  }
  $stmt->close();
  
  if ($destination != "") {
    array_push($textArray, "You head towards " . $destination . ".");
  }
?>