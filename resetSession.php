<?php
  $stmt = $conn->prepare("DELETE FROM zmb_rw_fires WHERE session=?");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->close();
  
  $stmt = $conn->prepare("DELETE FROM zmb_rw_items WHERE session=?");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->close();

  $stmt = $conn->prepare("DELETE FROM zmb_rw_positions WHERE session=?");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->close();
  
  //Keep rare items.

  $stmt = $conn->prepare("DELETE FROM zmb_rw_sessions WHERE id=?");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->close();
  
  $stmt = $conn->prepare("INSERT INTO zmb_rw_sessions (id) VALUES (?)");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  
  $rareItems = array();
  $stmt = $conn->prepare("SELECT item FROM zmb_rw_rareitems WHERE session=?");
  $stmt->bind_param("s", $_POST['k']);
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
  
  $valueArray['time'] = 23;
  $valueArray['fire'] = 0;
  $valueArray['temp'] = 3;
  $valueArray['thirst'] = 0;
  $valueArray['health'] = 10;
?>