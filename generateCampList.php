<?php
  $camps = array();
  $campsD = array();
  $candidateCamps = array();
  
  $stmt = $conn->prepare("SELECT x,y FROM zmb_rw_sessions WHERE id=?");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->bind_result($x, $y);
  $stmt->fetch();
  $stmt->close();
  
  $stmt = $conn->prepare("SELECT session FROM zmb_rw_positions WHERE session=?");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->bind_result($dummy);
  $stmt->store_result();
  $valueArray['discoveredTilesCount'] = $stmt->num_rows();
  $stmt->close();
  
  $stmt = $conn->prepare("SELECT x,y,altName,danger FROM zmb_rw_positions WHERE session=?");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->bind_result($locX, $locY, $locAltName, $locDanger);
  while ($stmt->fetch()) {     
    if ($locAltName != null) {
      $distance = abs($x - $locX) + abs($y - $locY);
      if ($distance > 0 && $distance < 10) {
        array_push($candidateCamps, array("name"=>trim($locAltName, " ."),"danger"=>$locDanger,"distance"=>abs($x - $locX) + abs($y - $locY)));
      }
    }
    if ($locX == $x && $locY == $y) {
      $valueArray['danger'] = round(255 - ((255 * $locDanger) / 100));
    }
  }
  $stmt->close();
  
  usort($candidateCamps, function($a, $b) {
    return $a['distance'] - $b['distance'];
  });
  for ($i = 0; $i < count($candidateCamps); $i++) {
    $camps[$candidateCamps[$i]['name']] = $candidateCamps[$i]['distance'];
    $campsD[$candidateCamps[$i]['name']] = round(255 - ((255 * $candidateCamps[$i]['danger']) / 100));
  }
  
  if ($valueArray['camps'] == null) {
    $valueArray['camps'] = json_encode($camps);
    $valueArray['campsD'] = json_encode($campsD);
  }
?>