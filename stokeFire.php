<?php
  $stokeText = "You stoke the fire.";
  $noWoodText = "You have no firewood.";
  
  $stmt = $conn->prepare("SELECT x,y FROM zmb_rw_sessions WHERE id=?");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->bind_result($x, $y);
  $stmt->fetch();
  $stmt->close();

  $stmt = $conn->prepare("SELECT level FROM zmb_rw_fires WHERE session=? AND x=? AND y=?");
  $stmt->bind_param("sii", $_POST['k'], $x, $y);
  $stmt->execute();
  $stmt->bind_result($fireLevel);
  $stmt->fetch();
  $stmt->close();
  
  $stmt = $conn->prepare("SELECT count FROM zmb_rw_items WHERE session=? AND x=? AND y=? AND item=1");
  $stmt->bind_param("sii", $_POST['k'], $x, $y);
  $stmt->execute();
  $stmt->bind_result($firewoodCount);
  $stmt->fetch();
  $stmt->close();
  
  $firewoodCount -= 1;
  if ($firewoodCount >= 0) {
    $fireLevel = min(4, $fireLevel + 1);
  
    $stmt = $conn->prepare("UPDATE zmb_rw_fires SET level=? WHERE session=? AND x=? AND y=?");
    $stmt->bind_param("isii", $fireLevel, $_POST['k'], $x, $y);
    $stmt->execute();
    $stmt->close();
    
    $stmt = $conn->prepare("UPDATE zmb_rw_items SET count=? WHERE session=? AND x=? AND y=? AND item=1");
    $stmt->bind_param("isii", $firewoodCount, $_POST['k'], $x, $y);
    $stmt->execute();
    $stmt->close();
    
    array_push($textArray, "You stoke the fire.");
  }
  else {
    array_push($textArray, "You have no firewood.");
  }
  $valueArray['fire'] = $fireLevel;
  
  $stmt = $conn->prepare("SELECT text FROM zmb_rw_fires JOIN zmb_ro_firetext ON (zmb_rw_fires.level = zmb_ro_firetext.value) WHERE session=? AND x=? AND y=?");
  $stmt->bind_param("sii", $_POST['k'], $x, $y);
  $stmt->execute();
  $stmt->bind_result($fireText);
  $stmt->fetch();
  $stmt->close();
  
  array_push($textArray, $fireText);
?>