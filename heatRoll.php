<?php
  $stmt = $conn->prepare("SELECT temperature,time,thirst,health,x,y FROM zmb_rw_sessions WHERE id=?");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->bind_result($temperature, $time, $thirst, $health, $x, $y);
  $stmt->fetch();
  $stmt->close();

  $stmt = $conn->prepare("SELECT level FROM zmb_rw_fires WHERE session=? AND x=? AND y=?");
  $stmt->bind_param("sii", $_POST['k'], $x, $y);
  $stmt->execute();
  $stmt->bind_result($fireLevel);
  $stmt->fetch();
  $stmt->close();
  
  switch ($fireLevel) {
    case 1:
      $temperature += rand(0, 1);
      break;
    case 2:
      $temperature += 1;
      break;
    case 3:
      $temperature += 1;
      $temperature += rand(0, 1);
      break;
    case 4:
      $temperature += 2;
      break;
    default:
      break;
  }
  
  if ($fireLevel != 0) {
    $oldFireLevel = $fireLevel;
    $fireLevel -= rand(0, 1);
    
    if ($fireLevel != $oldFireLevel) {
      $stmt = $conn->prepare("UPDATE zmb_rw_fires SET level=? WHERE session=? AND x=? AND y=?");
      $stmt->bind_param("isii", $fireLevel, $_POST['k'], $x, $y);
      $stmt->execute();
      $stmt->close();
      
      $valueArray['fire'] = $fireLevel;
      array_push($textArray, "The fire dwindles.");
    }
  }
  
  $oldThirst = $thirst;
  $oldTemperature = $temperature;
  
  switch (true) {
    case ($time < 5):
      $temperature -= 2;
      break;
    case ($time < 7):
      $temperature -= 1;
      break;
    case ($time < 9):
      $temperature += rand(-1, 0);
      $thirst += rand(0, 1);
      break;
    case ($time < 12):
      $temperature += rand(0, 1);
      $thirst += rand(0, 1);
      break;
    case ($time < 15):
      $temperature += 1;
      $thirst += 1;
      break;
    case ($time < 18):
      $temperature += rand(0, 1);
      $thirst += rand(0, 1);
      break;
    case ($time < 20):
      $temperature += rand(-1, 0);
      $thirst += rand(0, 1);
      break;
    case ($time < 23):
      $temperature -= 1;
      break;
    default:
      break;
  }  

  if ($temperature == 6) {
    $thirst += 1;
  }
  else if ($temperature > 6) {
    $thirst += 3;
    
    if ($fireLevel > 0) {
      array_push($textArray, "You stamp the fire out.");
      if ($fireLevel > 2) {
        $health -= 1;
      }
      $fireLevel = 0;
      $stmt = $conn->prepare("UPDATE zmb_rw_fires SET level=? WHERE session=? AND x=? AND y=?");
      $stmt->bind_param("isii", $fireLevel, $_POST['k'], $x, $y);
      $stmt->execute();
      $stmt->close();
      $valueArray['fire'] = $fireLevel;
    }
  }
  else if ($temperature < 0) {
    $health += $temperature;
    array_push($textArray, "You desperately need warmth.");
  }
  
  if ($thirst > 0) {
    $stmt = $conn->prepare("SELECT count FROM zmb_rw_items WHERE session=? AND x=? AND y=? AND item=2");
    $stmt->bind_param("sii", $_POST['k'], $x, $y);
    $stmt->execute();
    $stmt->bind_result($waterCount);
    $stmt->fetch();
    $stmt->close();
    
    if ($waterCount != null) {
      while ($waterCount > 0 && $thirst > 0) {
        $waterCount -= 1;
        $thirst -= 1;
      }
      $stmt = $conn->prepare("UPDATE zmb_rw_items SET count=? WHERE session=? AND x=? AND y=? AND item=2");
      $stmt->bind_param("isii", $waterCount, $_POST['k'], $x, $y);
      $stmt->execute();
      $stmt->close();
    }
    
    if ($thirst > 6) {
      $health -= 1;
      array_push($textArray, "You desperately need water.");
    }
  }
  
  $valueArray['health'] = $health;
  $valueArray['temp'] = $temperature;
  $valueArray['thirst'] = $thirst; 
  
  $temperature = max(0, min(6, $temperature));
  
  $stmt = $conn->prepare("UPDATE zmb_rw_sessions SET temperature=?, thirst=?, health=? WHERE id=?");
  $stmt->bind_param("iiis", $temperature, $thirst, $health, $_POST['k']);
  $stmt->execute();
  $stmt->close();
?>