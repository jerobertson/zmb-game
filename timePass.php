<?php
  $stmt = $conn->prepare("SELECT time,health FROM zmb_rw_sessions WHERE id=?");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->bind_result($time, $health);
  $stmt->fetch();
  $stmt->close();
  
  $time++;
  if ($time == 24) {
    $time = 0;
  }
  
  if (rand(1,5) == 5) {
    $health = min(10, $health + 1);
  }
  
  $stmt = $conn->prepare("UPDATE zmb_rw_sessions SET time=?,health=? WHERE id=?");
  $stmt->bind_param("iis", $time, $health, $_POST['k']);
  $stmt->execute();
  $stmt->close();
  
  $stmt = $conn->prepare("SELECT text FROM zmb_ro_time WHERE hour=?");
  $stmt->bind_param("i", $time);
  $stmt->execute();
  $stmt->bind_result($timeText);
  $stmt->fetch();
  $stmt->close();
  
  $valueArray['time'] = $time;
  $valueArray['health'] = $health;
  array_push($textArray, "Time passes.");
  if ($timeText != null) {
    array_push($textArray, $timeText);
  }
  
  include "../heatRoll.php";
?>