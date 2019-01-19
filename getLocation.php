<?php
  $stmt = $conn->prepare("SELECT x,y FROM zmb_rw_sessions WHERE id=?");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->bind_result($x, $y);
  $stmt->fetch();
  $stmt->close();
  
  $stmt = $conn->prepare("SELECT location,altName FROM zmb_rw_positions WHERE session=? AND x=? AND y=?");
  $stmt->bind_param("sii", $_POST['k'], $x, $y);
  $stmt->execute();
  $stmt->bind_result($locationId, $altName);
  $stmt->fetch();
  $stmt->close();
  
  $stmt = $conn->prepare("SELECT text FROM zmb_ro_locations WHERE id=?");
  $stmt->bind_param("i", $locationId);
  $stmt->execute();
  $stmt->bind_result($locationText);
  $stmt->fetch();
  $stmt->close();
  
  if ($altName != null) {
    if (strtolower(trim($altName, " .")) != strtolower(trim($locationText, " ."))) {
      array_push($textArray, "\"" . $altName. "\"");
      array_push($textArray, "(" . $locationText . ")");
    }
    else {
      array_push($textArray, $locationText);
    }
  }
  else {
    array_push($textArray, $locationText);
  }
?>