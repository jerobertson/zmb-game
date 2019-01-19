<?php
  $stmt = $conn->prepare("SELECT x,y,health FROM zmb_rw_sessions WHERE id=?");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->bind_result($x, $y, $health);
  $stmt->fetch();
  $stmt->close();
  
  $stmt = $conn->prepare("SELECT item FROM zmb_rw_rareitems WHERE session=? AND item=10");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->bind_result($dummy);
  $stmt->store_result();
  $hatchet = $stmt->num_rows();
  $stmt->close();
  
  $stmt = $conn->prepare("SELECT altName FROM zmb_rw_positions WHERE session=? AND x=? AND y=?");
  $stmt->bind_param("sii", $_POST['k'], $x, $y);
  $stmt->execute();
  $stmt->bind_result($altName);
  $stmt->fetch();
  $stmt->close();

  $stmt = $conn->prepare("SELECT danger,minDanger,maxDanger FROM zmb_rw_positions WHERE session=? AND x=? AND y=?");
  $stmt->bind_param("sii", $_POST['k'], $x, $y);
  $stmt->execute();
  $stmt->bind_result($danger, $minDanger, $maxDanger);
  $stmt->fetch();
  $stmt->close();
  
  array_push($textArray, "You fight.");
  
  if (rand(0,1) == 1) {
    $health--;
    $stmt = $conn->prepare("UPDATE zmb_rw_sessions SET health=? WHERE id=?");
    $stmt->bind_param("is", $health, $_POST['k']);
    $stmt->execute();
    $stmt->close;
    
    $valueArray['health'] = $health;
    array_push($textArray, "You're injured.");
  }
  
  if ($hatchet == 1) {
    $hatchet = 15;
  }
  
  if (rand(1, 100) > (($danger + 40) / 2 - $hatchet)) {
    $danger = max(0, $danger - 10);
    $minDanger = max(0, $minDanger - 2);
    $maxDanger = max(10, $maxDanger - 2);
    
    if ($danger == 0) {
      array_push($textArray, "The last of them drop to the floor, dead.");
      if ($altName == null) {
        $newStage = 40;
      }
      else {
        $newStage = 30;
      }
    }
    else {
      $newStage = 52;
    }
  }
  else {
    $maxDanger = min(100, $maxDanger + 2);
    $danger = min($maxDanger, $danger + 5);
    
    $newStage = 53;
  }
  include "../updateStage.php";
  
  $stmt = $conn->prepare("UPDATE zmb_rw_positions SET danger=?,minDanger=?,maxDanger=? WHERE session=? AND x=? AND y=?");
  $stmt->bind_param("iiisii", $danger, $minDanger, $maxDanger, $_POST['k'], $x, $y);
  $stmt->execute();
  $stmt->close();
?>