<?php
  $rareItems = array();
  $chances = array();
  $stmt = $conn->prepare("SELECT item,chance FROM zmb_ro_rareloottable WHERE location=?");
  $stmt->bind_param("i", $locationId);
  $stmt->execute();
  $stmt->bind_result($rareItem, $chance);
  while ($stmt->fetch()) {
    array_push($rareItems, $rareItem);
    array_push($chances, $chance);
  }
  $stmt->close();
  
  for ($i = 0; $i < count($rareItems); $i++) {
    if (rand(1,100) <= $chances[$i]) {
      $stmt = $conn->prepare("SELECT item FROM zmb_rw_rareitems WHERE session=? AND item=?");
      $stmt->bind_param("si", $_POST['k'], $rareItems[$i]);
      $stmt->execute();
      $stmt->bind_result($dummy);
      $stmt->store_result();
      $rowCount = $stmt->num_rows();
      $stmt->close();
      
      if ($rowCount == 0) {
        $stmt = $conn->prepare("INSERT INTO zmb_rw_rareitems (session,item) VALUES (?,?)");
        $stmt->bind_param("si",  $_POST['k'], $rareItems[$i]);
        $stmt->execute();
        $stmt->close();
        
        $stmt = $conn->prepare("SELECT name FROM zmb_ro_itemtext WHERE id=?");
        $stmt->bind_param("i", $rareItems[$i]);
        $stmt->execute();
        $stmt->bind_result($itemtext);
        $stmt->fetch();
        $stmt->close();
        
        array_push($textArray, "You found a " . $itemtext . ".");
        
        switch ($rareItems[$i]) {
          case 4:
            $stmt = $conn->prepare("SELECT waterCap FROM zmb_rw_sessions WHERE id=?");
            $stmt->bind_param("s", $_POST['k']);
            $stmt->execute();
            $stmt->bind_result($value);
            $stmt->fetch();
            $stmt->close();
            
            $value += 2;
            
            $stmt = $conn->prepare("UPDATE zmb_rw_sessions SET waterCap=? WHERE id=?");
            $stmt->bind_param("is", $value, $_POST['k']);
            $stmt->execute();
            $stmt->close();
            break;
          case 5:
            $stmt = $conn->prepare("SELECT waterCap FROM zmb_rw_sessions WHERE id=?");
            $stmt->bind_param("s", $_POST['k']);
            $stmt->execute();
            $stmt->bind_result($value);
            $stmt->fetch();
            $stmt->close();
            
            $value += 3;
            
            $stmt = $conn->prepare("UPDATE zmb_rw_sessions SET waterCap=? WHERE id=?");
            $stmt->bind_param("is", $value, $_POST['k']);
            $stmt->execute();
            $stmt->close();
            break;
          case 6:
            $stmt = $conn->prepare("SELECT inventory FROM zmb_rw_sessions WHERE id=?");
            $stmt->bind_param("s", $_POST['k']);
            $stmt->execute();
            $stmt->bind_result($value);
            $stmt->fetch();
            $stmt->close();
            
            $value += 2;
            
            $stmt = $conn->prepare("UPDATE zmb_rw_sessions SET inventory=? WHERE id=?");
            $stmt->bind_param("is", $value, $_POST['k']);
            $stmt->execute();
            $stmt->close();
            break;
          case 7:
            $stmt = $conn->prepare("SELECT inventory FROM zmb_rw_sessions WHERE id=?");
            $stmt->bind_param("s", $_POST['k']);
            $stmt->execute();
            $stmt->bind_result($value);
            $stmt->fetch();
            $stmt->close();
            
            $value += 3;
            
            $stmt = $conn->prepare("UPDATE zmb_rw_sessions SET inventory=? WHERE id=?");
            $stmt->bind_param("is", $value, $_POST['k']);
            $stmt->execute();
            $stmt->close();
            break;
          case 8:
            $stmt = $conn->prepare("SELECT inventory FROM zmb_rw_sessions WHERE id=?");
            $stmt->bind_param("s", $_POST['k']);
            $stmt->execute();
            $stmt->bind_result($value);
            $stmt->fetch();
            $stmt->close();
            
            $value += 5;
            
            $stmt = $conn->prepare("UPDATE zmb_rw_sessions SET inventory=? WHERE id=?");
            $stmt->bind_param("is", $value, $_POST['k']);
            $stmt->execute();
            $stmt->close();
            break;
          default:
            break;
        }
      }
    }
  }
?>