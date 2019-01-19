<?php
  $stmt = $conn->prepare("SELECT health,thirst,x,y FROM zmb_rw_sessions WHERE id=?");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->bind_result($health, $thirst, $x, $y);
  $stmt->fetch();
  $stmt->close();
  
  $stmt = $conn->prepare("SELECT count FROM zmb_rw_items WHERE session=? AND x=? AND y=? AND item=9");
  $stmt->bind_param("sii", $_POST['k'], $x, $y);
  $stmt->execute();
  $stmt->bind_result($count);
  $stmt->fetch();
  $stmt->close();
  
  if ($count != null) {
    $oldHealth = $health;
    for ($i = 0; $i < $count; $i++) {
      $health = min(10, $health + 1);
    }
    
    $stmt = $conn->prepare("UPDATE zmb_rw_sessions SET health=? WHERE id=?");
    $stmt->bind_param("is", $health, $_POST['k']);
    $stmt->execute();
    $stmt->close();
    
    $newCount = $count - ($health - $oldHealth);
    
    $stmt = $conn->prepare("UPDATE zmb_rw_items SET count=? WHERE session=? AND x=? AND y=? AND item=9");
    $stmt->bind_param("isii", $newCount, $_POST['k'], $x, $y);
    $stmt->execute();
    $stmt->close();
  }
  
  $valueArray['health'] = $health;
  $valueArray['thirst'] = $thirst;
  
  if ($health < 1 && $_POST['stage'] < 4000) {
    $newStage = 4000;  
    include "../updateStage.php";
    include "../overrideCampsList.php";
    
    array_push($textArray, "You feel weary.");
    array_push($textArray, "You collapse.");

    $_POST['stage'] = 99999;
  }
?>