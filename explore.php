<?php
  $stmt = $conn->prepare("SELECT x,y,inventory,waterCap FROM zmb_rw_sessions WHERE id=?");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->bind_result($x, $y, $inventory, $waterCap);
  $stmt->fetch();
  $stmt->close();
  
  $stmt = $conn->prepare("SELECT danger FROM zmb_rw_positions WHERE session=? AND x=? AND y=?");
  $stmt->bind_param("sii", $_POST['k'], $x, $y);
  $stmt->execute();
  $stmt->bind_result($oldDanger);
  $stmt->fetch();
  $stmt->close();
  
  $stmt = $conn->prepare("SELECT location,minDanger,maxDanger FROM zmb_rw_positions WHERE session=? AND x=? AND y=?");
  $stmt->bind_param("sii", $_POST['k'], $x, $y);
  $stmt->execute();
  $stmt->bind_result($locationId, $minDanger, $maxDanger);
  $stmt->fetch();
  $stmt->close();
  
  if ($_POST['stage'] < 50 || $_POST['stage'] > 59) {
    $items = array();
    $stmt = $conn->prepare("SELECT item,count FROM zmb_rw_items WHERE session=? AND x=? AND y=?");
    $stmt->bind_param("sii", $_POST['k'], $x, $y);
    $stmt->execute();
    $stmt->bind_result($item, $count);
    while ($stmt->fetch()) {
      $items[$item] = $count;
    }
    $stmt->close();
  
    foreach ($items as $key=>$value) {
      $newCount = 0;
      
      if (intval($key) == 2) {
        $newCount = max($value - $inventory - $waterCap, 0);
      }
      else {
        $newCount = max($value - $inventory, 0);
      }
      
      $stmt = $conn->prepare("UPDATE zmb_rw_items SET count=? WHERE session=? AND x=? AND y=? AND item=?");
      $stmt->bind_param("isiii", $newCount, $_POST['k'], $x, $y, intval($key));
      $stmt->execute();
      $stmt->close();
    }
  }
  
  $oldX = $x;
  $oldY = $y;
  
  $north = array("x"=>$x+1,"y"=>$y);
  $south = array("x"=>$x-1,"y"=>$y);
  $east = array("x"=>$x,"y"=>$y+1);
  $west = array("x"=>$x,"y"=>$y-1);
  
  $directions = array($north, $south, $east, $west);
  
  if ($direction == null) {
    $direction = $directions[rand(0, count($directions) - 1)];
  }
  
  $stmt = $conn->prepare("SELECT location,altName FROM zmb_rw_positions WHERE session=? AND x=? AND y=?");
  $stmt->bind_param("sii", $_POST['k'], $direction['x'], $direction['y']);
  $stmt->execute();
  $stmt->bind_result($newLocationId, $altName);
  $stmt->fetch();
  $stmt->close();
  
  $discovered = false;
  
  if ($newLocationId == null || $newLocationId == 0) {
    $newlocations = array();
    
    $stmt = $conn->prepare("SELECT loc1, chance FROM zmb_ro_positionchances WHERE loc0=?");
    $stmt->bind_param("s", $locationId);
    $stmt->execute();
    $stmt->bind_result($loc1, $chance);
    while ($stmt->fetch()) {
      $newlocations[$loc1] = $chance;
    }
    $stmt->close();
    
    asort($newlocations);
    $random = rand(1, 100);
    $addedValue = 0;
    $newLocationId = $locationId;
    
    foreach ($newlocations as $key=>$value) {
      if ($random <= $value + $addedValue) {
        $discovered = true;
        $newLocationId = $key;
        $newX = $direction['x'];
        $newY = $direction['y'];
        
        $stmt = $conn->prepare("SELECT text,defMinDanger,defMaxDanger,discoverText FROM zmb_ro_locations WHERE id=?");
        $stmt->bind_param("i", $newLocationId);
        $stmt->execute();
        $stmt->bind_result($locationText, $defMinDanger, $defMaxDanger, $discoverText);
        $stmt->fetch();
        $stmt->close();
        
        $minDanger = ($defMinDanger * 2 + $minDanger) / 3;
        $maxDanger = ($defMaxDanger * 2 + $maxDanger) / 3;
        $danger = min($maxDanger, max($minDanger, ($oldDanger + rand($minDanger, $maxDanger)) / 2));
        
        $stmt = $conn->prepare("INSERT INTO zmb_rw_positions (session,x,y,location,minDanger,maxDanger,danger) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("siiiiii",  $_POST['k'], $newX, $newY, $newLocationId, $minDanger, $maxDanger, $danger);
        $stmt->execute();
        $stmt->close();
        
        if ($_POST['stage'] < 50 || $_POST['stage'] > 59) {
          foreach ($items as $key=>$value) {
            $stmt = $conn->prepare("INSERT INTO zmb_rw_items (session,x,y,item,count) VALUES (?,?,?,?,?)");
            $stmt->bind_param("siiii",  $_POST['k'], $newX, $newY, intval($key), min($value, $inventory));
            $stmt->execute();
            $stmt->close();
            
            if (intval($key) == 2) {
              $stmt = $conn->prepare("INSERT INTO zmb_rw_items (session,x,y,item,count) VALUES (?,?,?,?,?)");
              $stmt->bind_param("siiii",  $_POST['k'], $newX, $newY, intval($key), min($value, $waterCap));
              $stmt->execute();
              $stmt->close();
            }
          }
        }
        
        $stmt = $conn->prepare("INSERT INTO zmb_rw_fires(session,x,y) VALUES(?,?,?)");
        $stmt->bind_param("sii", $_POST['k'], $newX, $newY);
        $stmt->execute();
        $stmt->close;
        
        $stmt = $conn->prepare("UPDATE zmb_rw_sessions SET x=?, y=? WHERE id=?");
        $stmt->bind_param("iis", $newX, $newY, $_POST['k']);
        $stmt->execute();
        $stmt->close();
        
        array_push($textArray, $discoverText . " " . strtolower($locationText));
        
        break;
      }
      else {
        $addedValue += $value;
      }
    }
    
    if ($discovered == false) {
      if ($newStage != 51) {
        array_push($textArray, "You get lost and head back.");
      }
      
      foreach ($items as $key=>$value) {
        $stmt = $conn->prepare("UPDATE zmb_rw_items SET count=? WHERE session=? AND x=? AND y=? AND item=?");
        $stmt->bind_param("isiii", $value, $_POST['k'], $x, $y, intval($key));
        $stmt->execute();
        $stmt->close();
      }
    }
  }
  else {
    $discovered = true;
    
    $x = $direction['x'];
    $y = $direction['y'];
    
    $stmt = $conn->prepare("UPDATE zmb_rw_sessions SET x=?, y=? WHERE id=?");
    $stmt->bind_param("iis", $x, $y, $_POST['k']);
    $stmt->execute();
    $stmt->close();
    
    if ($_POST['stage'] < 50 || $_POST['stage'] > 59) {
      foreach ($items as $key=>$value) {
        $rowCount = 0;
        
        $stmt = $conn->prepare("SELECT count FROM zmb_rw_items WHERE session=? AND x=? AND y=? AND item=?");
        $stmt->bind_param("siii", $_POST['k'], $x, $y, intval($key));
        $stmt->execute();
        $stmt->store_result();
        $rowCount = $stmt->num_rows;
        $stmt->close();
        
        if ($rowCount == 0) {
          $stmt = $conn->prepare("INSERT INTO zmb_rw_items (session,x,y,item,count) VALUES (?,?,?,?,?)");
          $stmt->bind_param("siiii", $_POST['k'], $x, $y, intval($key), min($value, $inventory));
          $stmt->execute();
          $stmt->close();
          
          if (intval($key) == 2) {
            $stmt = $conn->prepare("INSERT INTO zmb_rw_items (session,x,y,item,count) VALUES (?,?,?,?,?)");
            $stmt->bind_param("siiii",  $_POST['k'], $x, $y, intval($key), min($value, $waterCap));
            $stmt->execute();
            $stmt->close();
          }
        }
        else {
          $stmt = $conn->prepare("SELECT count FROM zmb_rw_items WHERE session=? AND x=? AND y=? AND item=?");
          $stmt->bind_param("siii", $_POST['k'], $x, $y, intval($key));
          $stmt->execute();
          $stmt->bind_result($originalCount);
          $stmt->fetch();
          $stmt->close();
          
          $newCount = 0;
          
          if (intval($key) == 2) {
            $newCount = min($value, $inventory) + min($value, $waterCap) + $originalCount;
          }
          else {
            $newCount = min($value, $inventory) + $originalCount;
          }
          
          $stmt = $conn->prepare("UPDATE zmb_rw_items SET count=? WHERE session=? AND x=? AND y=? AND item=?");
          $stmt->bind_param("isiii", $newCount, $_POST['k'], $x, $y, intval($key));
          $stmt->execute();
          $stmt->close();
        }
      }
    }
    
    $stmt = $conn->prepare("SELECT text,arriveText FROM zmb_ro_locations WHERE id=?");
    $stmt->bind_param("s", $newLocationId);
    $stmt->execute();
    $stmt->bind_result($locationText, $arriveText);
    $stmt->fetch();
    $stmt->close();
    
    if ($altName != null) {
      array_push($textArray, "You arrive at " . strtolower(trim($altName, " .")) . ".");
      if (strtolower(trim($altName, " .")) != strtolower(trim($locationText, " ."))) {
        array_push($textArray, "(" . $locationText . ")");
      }
    }
    else {
      array_push($textArray, $arriveText . " " . strtolower($locationText));
    }
  }
  
  $stmt = $conn->prepare("SELECT level FROM zmb_rw_fires WHERE session=? AND x=? AND y=?");
  $stmt->bind_param("sii", $_POST['k'], $x, $y);
  $stmt->execute();
  $stmt->bind_result($fireLevel);
  $stmt->fetch();
  $stmt->close();
  
  $valueArray['fire'] = $fireLevel;
  
  $stmt = $conn->prepare("SELECT altName FROM zmb_rw_positions WHERE session=? AND x=? AND y=?");
  $stmt->bind_param("sii", $_POST['k'], $x, $y);
  $stmt->execute();
  $stmt->bind_result($altName);
  $stmt->fetch();
  $stmt->close();
  
  if ($discovered == true) {
    if ($_POST['stage'] == 50 || $_POST['stage'] == 52 || $_POST['stage'] == 53) {
      $newDanger = 100;
      $stmt = $conn->prepare("UPDATE zmb_rw_positions SET danger=?,maxDanger=? WHERE session=? AND x=? AND y=?");
      $stmt->bind_param("iisii", $newDanger, $newDanger, $_POST['k'], $oldX, $oldY);
      $stmt->execute();
      $stmt->close;
    }
    
    if ($altName == null) {
      $newStage = 40;
    }
    else {
      $newStage = 30;
    }
    include "../updateStage.php";
  }
?>