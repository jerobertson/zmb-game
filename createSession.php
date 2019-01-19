<?php
  $timestamp = time();
  
  $stmt = $conn->prepare("INSERT INTO zmb_rw_sessionscreationtime (session) VALUES (?)");
  $stmt->bind_param("s", $k);
  $stmt->execute();
  $stmt->close();
  
  $stmt = $conn->prepare("UPDATE zmb_rw_sessionscreationtime SET timestamp=?, removed=0 WHERE session=?");
  $stmt->bind_param("is", $timestamp, $k);
  $stmt->execute();
  $stmt->close();
  
  $stmt = $conn->prepare("SELECT stage FROM zmb_rw_sessions WHERE id=?");
  $stmt->bind_param("s", $k);
  $stmt->execute();
  $stmt->bind_result($dummy);
  $stmt->store_result();
  $rowCount = $stmt->num_rows();
  $stmt->close();
  
  if ($rowCount == 0) {
    $stmt = $conn->prepare("INSERT INTO zmb_rw_sessions (id) VALUES (?)");
    $stmt->bind_param("s", $k);
    $stmt->execute();
    $stmt->close();
    
    $rareItems = array();
    $stmt = $conn->prepare("SELECT item FROM zmb_rw_rareitems WHERE session=?");
    $stmt->bind_param("s", $k);
    $stmt->execute();
    $stmt->bind_result($item);
    while ($stmt->fetch()) {
      array_push($rareItems, $item);
    }
    $stmt->close();
    
    foreach ($rareItems as $item) {
      $stmt = $conn->prepare("SELECT inventory,waterCap FROM zmb_rw_sessions WHERE id=?");
      $stmt->bind_param("s", $_POST['k']);
      $stmt->execute();
      $stmt->bind_result($inventory, $waterCap);
      $stmt->fetch();
      $stmt->close();
      switch ($item) {
        case 4:        
          $waterCap += 2;
          break;
        case 5:
          $waterCap += 3;
          break;
        case 6:
          $inventory += 2;
          break;
        case 7:
          $inventory += 3;
          break;
        case 8:
          $inventory += 5;
          break;
        default:
          break;
      }
      $stmt = $conn->prepare("UPDATE zmb_rw_sessions SET inventory=?,waterCap=? WHERE id=?");
      $stmt->bind_param("iis", $inventory, $waterCap, $_POST['k']);
      $stmt->execute();
      $stmt->close();
    }
  }
  
  include "cleanup.php";
?>