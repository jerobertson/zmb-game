<?php
  if ($_POST['stage'] != 99999 && $_POST['stage'] < 4000 && ($_POST['stage'] < 50 || $_POST['stage'] > 59)) {
    $stmt = $conn->prepare("SELECT x,y FROM zmb_rw_sessions WHERE id=?");
    $stmt->bind_param("s", $_POST['k']);
    $stmt->execute();
    $stmt->bind_result($x, $y);
    $stmt->fetch();
    $stmt->close();
    
    //===== Update Danger Levels =====//
    $locations = array();
    $stmt = $conn->prepare("SELECT minDanger,maxDanger,danger,x,y FROM zmb_rw_positions WHERE session=?");
    $stmt->bind_param("s", $_POST['k']);
    $stmt->execute();
    $stmt->bind_result($locMinDanger, $locMaxDanger, $locDanger, $locX, $locY);
    while ($stmt->fetch()) {
      if (!array_key_exists($locX, $locations)) {
        $locations[$locX] = array();
      }
      if ($locX == $x && $locY == $y) {
        $locDanger += 3;
      }
      else {
        $locDanger -= 2;
      }
      $locations[$locX][$locY] = min($locMaxDanger, max($locMinDanger, $locDanger));
    }
    $stmt->close();
    foreach($locations as $key=>$value) {
      foreach($locations[$key] as $iKey=>$iValue) {
        $stmt = $conn->prepare("UPDATE zmb_rw_positions SET danger=? WHERE session=? AND x=? AND y=?");
        $stmt->bind_param("isii", $iValue, $_POST['k'], intval($key), intval($iKey));
        $stmt->execute();
        $stmt->close();
      }
    }
    //----- Update Danger Levels -----//
    
    //===== Danger Roll =====//
    $stmt = $conn->prepare("SELECT danger FROM zmb_rw_positions WHERE session=? AND x=? AND y=?");
    $stmt->bind_param("sii", $_POST['k'], $x, $y);
    $stmt->execute();
    $stmt->bind_result($danger);
    $stmt->fetch();
    $stmt->close();
    
    if (rand(1, 100) <= ($danger / 2)) {
      $newStage = 50;
      include "../updateStage.php";
      
      $_POST['stage'] = 99999;
    }
    //----- Danger Roll -----//
  }
?>