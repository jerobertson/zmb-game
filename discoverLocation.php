<?php
  $firewoodId = 1;
  
  $stmt = $conn->prepare("SELECT defMinDanger,defMaxDanger FROM zmb_ro_locations WHERE id=?");
  $stmt->bind_param("i", $locationId);
  $stmt->execute();
  $stmt->bind_result($defMinDanger, $defMaxDanger);
  $stmt->fetch();
  $stmt->close();
  
  $danger = rand($defMinDanger, $defMaxDanger);
  
  $stmt = $conn->prepare("INSERT INTO zmb_rw_fires(session,x,y) VALUES(?,?,?)");
  $stmt->bind_param("sii", $_POST['k'], $x, $y);
  $stmt->execute();
  $stmt->close;
  
  $stmt = $conn->prepare("INSERT INTO zmb_rw_items(session,x,y,item,count) VALUES(?,?,?,?,?)");
  $stmt->bind_param("siiii", $_POST['k'], $x, $y, $firewoodId, $firewood);
  $stmt->execute();
  $stmt->close;
  
  $stmt = $conn->prepare("INSERT INTO zmb_rw_positions(session,x,y,location,altName,minDanger,maxDanger,danger) VALUES(?,?,?,?,?,?,?,?)");
  $stmt->bind_param("siiisiii", $_POST['k'], $x, $y, $locationId, $altName, $defMinDanger, $defMaxDanger, $danger);
  $stmt->execute();
  $stmt->close;
  
  $stmt = $conn->prepare("UPDATE zmb_rw_sessions SET x=?, y=? WHERE id=?");
  $stmt->bind_param("iis", $x, $y, $_POST['k']);
  $stmt->execute();
  $stmt->close;
  
  $stmt = $conn->prepare("SELECT text,discoverText FROM zmb_ro_locations WHERE id=?");
  $stmt->bind_param("i", $locationId);
  $stmt->execute();
  $stmt->bind_result($locationText, $discoverText);
  $stmt->fetch();
  $stmt->close();
  
  if ($discoverText != null) {
    array_push($textArray, $discoverText . " " . strtolower($locationText));
  }
  else {
    array_push($textArray, $locationText);
  }
?>