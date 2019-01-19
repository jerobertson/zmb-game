<?php
  if (rand(0,1) == 1) {
    $stmt = $conn->prepare("SELECT health FROM zmb_rw_sessions WHERE id=?");
    $stmt->bind_param("s", $_POST['k']);
    $stmt->execute();
    $stmt->bind_result($health);
    $stmt->fetch();
    $stmt->close();
    
    $health = max(0, $health - 1);
    
    $stmt = $conn->prepare("UPDATE zmb_rw_sessions SET health=? WHERE id=?");
    $stmt->bind_param("is", $health, $_POST['k']);
    $stmt->execute();
    $stmt->close;
    
    $valueArray['health'] = $health;
    array_push($textArray, "You're injured, attempting to escape.");
  }
?>