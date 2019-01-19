<?php
  $stmt = $conn->prepare("SELECT x,y FROM zmb_rw_sessions WHERE id=?");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->bind_result($x, $y);
  $stmt->fetch();
  $stmt->close();
  
  $items = array();
  $stmt = $conn->prepare("SELECT item,count FROM zmb_rw_items WHERE session=? AND x=? AND y=?");
  $stmt->bind_param("sii", $_POST['k'], $x, $y);
  $stmt->execute();
  $stmt->bind_result($item, $count);
  while ($stmt->fetch()) {
    $items[$item] = $count;
  }
  $stmt->close();
  
  if (array_key_exists("1", $items) && $items['1'] > 2) {
    $items['1'] -= 3;
    
    $stmt = $conn->prepare("UPDATE zmb_rw_items SET count=? WHERE session=? AND x=? AND y=? AND item=1");
    $stmt->bind_param("isiii", $items['1'], $_POST['k'], $x, $y);
    $stmt->execute();
    $stmt->close();
    
    $newStage = 41;
    include "../updateStage.php";
    include "../timePass.php";
    
    array_push($textArray, "You build a small firepit.");
  }
  else {
    array_push($textArray, "You don't have enough firewood.");
  }
?>