<?php
  $items = array();
  $counts = array();
  $foundItems = array();
  
  $stmt = $conn->prepare("SELECT x,y FROM zmb_rw_sessions WHERE id=?");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->bind_result($x, $y);
  $stmt->fetch();
  $stmt->close();
  
  $stmt = $conn->prepare("SELECT item FROM zmb_rw_rareitems WHERE session=? AND item=10");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->bind_result($dummy);
  $stmt->store_result();
  $hatchet = $stmt->num_rows();
  $stmt->close();
  
  $stmt = $conn->prepare("SELECT location FROM zmb_rw_positions WHERE session=? AND x=? AND y=?");
  $stmt->bind_param("sii", $_POST['k'], $x, $y);
  $stmt->execute();
  $stmt->bind_result($locationId);
  $stmt->fetch();
  $stmt->close();
  
  $stmt = $conn->prepare("SELECT item,count FROM zmb_ro_loottable WHERE location=?");
  $stmt->bind_param("i", $locationId);
  $stmt->execute();
  $stmt->bind_result($item, $count);
  while ($stmt->fetch()) {
    array_push($items, $item);
    if ($hatchet == 1 && $item == 1) {
      array_push($counts, $count * 2);
    }
    else {
      array_push($counts, $count);
    }
  }
  $stmt->close();
  
  if (count($items) > 0) {
    $findChance = 20;
    while (rand(1, 100) > $findChance) {
      $findChance += ($findChance / 2);
      $ind = rand(0, count($items) - 1);
      if (array_key_exists($items[$ind], $foundItems)) {
        $foundItems[$items[$ind]] += $counts[$ind];  
      }
      else {
          $foundItems[$items[$ind]] = $counts[$ind];
      }
    }
    
    if (count($foundItems) == 0) {
      array_push($textArray, "You couldn't find anything.");
    }

    foreach($foundItems as $key=>$value) {
      $itemId = intval($key);
      $foundCount = $value;
      $oldCount = null;
      $itemtext = null;
      
      $stmt = $conn->prepare("SELECT count FROM zmb_rw_items WHERE session=? AND x=? AND y=? AND item=?");
      $stmt->bind_param("siii", $_POST['k'], $x, $y, $itemId);
      $stmt->execute();
      $stmt->bind_result($oldCount);
      $stmt->fetch();
      $stmt->close();
      
      $newCount = $oldCount + $foundCount;
      
      $stmt = $conn->prepare("SELECT name FROM zmb_ro_itemtext WHERE id=?");
      $stmt->bind_param("i", $itemId);
      $stmt->execute();
      $stmt->bind_result($itemtext);
      $stmt->fetch();
      $stmt->close();
      
      array_push($textArray, "You found " . $foundCount . " " . $itemtext . ".");
      
      $stmt = $conn->prepare("DELETE FROM zmb_rw_items WHERE session=? AND x=? AND y=? AND item=?");
      $stmt->bind_param("siii", $_POST['k'], $x, $y, $itemId);
      $stmt->execute();
      $stmt->close();
      
      $stmt = $conn->prepare("INSERT INTO zmb_rw_items(session,x,y,item,count) VALUES(?,?,?,?,?)");
      $stmt->bind_param("siiii", $_POST['k'], $x, $y, $itemId, $newCount);
      $stmt->execute();
      $stmt->close;
    }
  }
  else {
    array_push($textArray, "There is nothing to find here.");
  }
  
  include "../rareLootRoll.php";
?>