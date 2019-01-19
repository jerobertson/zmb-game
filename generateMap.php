<?php
  $mapId = 3;

  $stmt = $conn->prepare("SELECT item FROM zmb_rw_rareitems WHERE session=? AND item=?");
  $stmt->bind_param("si", $_POST['k'], $mapId);
  $stmt->execute();
  $stmt->bind_result($dummy);
  $stmt->store_result();
  $rowCount = $stmt->num_rows();
  $stmt->close();
  
  if ($rowCount == 1) {
    $stmt = $conn->prepare("SELECT x,y FROM zmb_rw_sessions WHERE id=?");
    $stmt->bind_param("s", $_POST['k']);
    $stmt->execute();
    $stmt->bind_result($x, $y);
    $stmt->fetch();
    $stmt->close();
    
    $positions = array();
    $stmt = $conn->prepare("SELECT location,x,y FROM zmb_rw_positions WHERE session=?");
    $stmt->bind_param("s", $_POST['k']);
    $stmt->execute();
    $stmt->bind_result($locId, $locX, $locY);
    while ($stmt->fetch()) {
      if (!array_key_exists($locX, $positions)) {
        $positions[$locX] = array();
      }
      $positions[$locX][$locY] = $locId;
    }
    $stmt->close();
    
    $htmlmap = "";
    for ($iy = -4; $iy < 5; $iy++) {
      $htmlmap .= '<div class="col-xs-4 mapgrid">';
      for ($ix = -4; $ix < -1; $ix++) {
        if (array_key_exists($x + $ix, $positions) && array_key_exists($y - $iy, $positions[(string) ($x + $ix)])) {
          $stmt = $conn->prepare("SELECT mapColour FROM zmb_ro_locations WHERE id=?");
          $stmt->bind_param("i", $positions[(string) ($x + $ix)][(string) ($y - $iy)]);
          $stmt->execute();
          $stmt->bind_result($mapColour);
          $stmt->fetch();
          $stmt->close();
          $htmlmap .= '<div class="col-xs-4 maptile" style="background:' . $mapColour . ';"></div>';
        }
        else {
          $htmlmap .= '<div class="col-xs-4 maptile" style="background:rgba(50,50,50,0.3);"></div>';
        }
      }
      $htmlmap .= '</div><div class="col-xs-4 mapgrid">';
      for ($ix = -1; $ix < 2; $ix++) {
        if (array_key_exists($x + $ix, $positions) && array_key_exists($y - $iy, $positions[(string) ($x + $ix)])) {
          $stmt = $conn->prepare("SELECT mapColour FROM zmb_ro_locations WHERE id=?");
          $stmt->bind_param("i", $positions[(string) ($x + $ix)][(string) ($y - $iy)]);
          $stmt->execute();
          $stmt->bind_result($mapColour);
          $stmt->fetch();
          $stmt->close();
          $htmlmap .= '<div class="col-xs-4 maptile" style="background:' . $mapColour . ';"></div>';
        }
        else {
          $htmlmap .= '<div class="col-xs-4 maptile" style="background:rgba(50,50,50,0.3);"></div>';
        }
      }
      $htmlmap .= '</div><div class="col-xs-4 mapgrid">';
      for ($ix = 2; $ix < 5; $ix++) {
        if (array_key_exists($x + $ix, $positions) && array_key_exists($y - $iy, $positions[(string) ($x + $ix)])) {
          $stmt = $conn->prepare("SELECT mapColour FROM zmb_ro_locations WHERE id=?");
          $stmt->bind_param("i", $positions[(string) ($x + $ix)][(string) ($y - $iy)]);
          $stmt->execute();
          $stmt->bind_result($mapColour);
          $stmt->fetch();
          $stmt->close();
          $htmlmap .= '<div class="col-xs-4 maptile" style="background:' . $mapColour . ';"></div>';
        }
        else {
          $htmlmap .= '<div class="col-xs-4 maptile" style="background:rgba(50,50,50,0.3);"></div>';
        }
      }
      $htmlmap .= '</div>';
    }
    $valueArray['map'] = $htmlmap;
  }
?>