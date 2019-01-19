<?php
  include "connection.php";
  
  $valueArray = array();
  
  $stmt = $conn->prepare("SELECT time,x,y,temperature,thirst,health FROM zmb_rw_sessions WHERE id=?");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->bind_result($time, $x, $y, $temperature, $thirst, $health);
  $stmt->fetch();
  $stmt->close();
  $valueArray['time'] = $time;
  $valueArray['temperature'] = $temperature;
  $valueArray['thirst'] = $thirst;
  $valueArray['health'] = $health;

  $stmt = $conn->prepare("SELECT level FROM zmb_rw_fires WHERE session=? AND x=? AND y=?");
  $stmt->bind_param("sii", $_POST['k'], $x, $y);
  $stmt->execute();
  $stmt->bind_result($fireLevel);
  $stmt->fetch();
  $stmt->close();
  $valueArray['fire'] = $fireLevel;
  
  $stmt = $conn->prepare("SELECT danger FROM zmb_rw_positions WHERE session=? AND x=? AND y=?");
  $stmt->bind_param("sii", $_POST['k'], $x, $y);
  $stmt->execute();
  $stmt->bind_result($danger);
  $stmt->fetch();
  $stmt->close();
  $valueArray['danger'] = round(255 - ((255 * $danger) / 100));
  
  include "generateCampList.php";
  include "generateMap.php";
  
  $conn->close();
  echo json_encode($valueArray);
?>