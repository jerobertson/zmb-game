<?php
  $currentTime = time();
  $oldSessions = array();
  
  $stmt = $conn->prepare("SELECT session,timestamp from zmb_rw_sessionscreationtime WHERE removed=0");
  $stmt->execute();
  $stmt->bind_result($session, $timestamp);
  while ($stmt->fetch()) {
    if ($currentTime - $timestamp > 60) {
      array_push($oldSessions, $session);
    }
  }
  $stmt->close();
  
  foreach($oldSessions as $session) {
    $stmt = $conn->prepare("SELECT stage from zmb_rw_sessions where id=?");
    $stmt->bind_param("s", $session);
    $stmt->execute();
    $stmt->bind_result($stage);
    $stmt->fetch();
    $stmt->close();
    
    if ($stage == 1) {
      $stmt = $conn->prepare("DELETE FROM zmb_rw_fires WHERE session=?");
      $stmt->bind_param("s", $session);
      $stmt->execute();
      $stmt->close();
      
      $stmt = $conn->prepare("DELETE FROM zmb_rw_items WHERE session=?");
      $stmt->bind_param("s", $session);
      $stmt->execute();
      $stmt->close();

      $stmt = $conn->prepare("DELETE FROM zmb_rw_positions WHERE session=?");
      $stmt->bind_param("s", $session);
      $stmt->execute();
      $stmt->close();
      
      $stmt = $conn->prepare("DELETE FROM zmb_rw_rareitems WHERE session=?");
      $stmt->bind_param("s", $session);
      $stmt->execute();
      $stmt->close();

      $stmt = $conn->prepare("DELETE FROM zmb_rw_sessions WHERE id=?");
      $stmt->bind_param("s", $session);
      $stmt->execute();
      $stmt->close();
      
      $stmt = $conn->prepare("UPDATE zmb_rw_sessionscreationtime SET removed=1 WHERE session=?");
      $stmt->bind_param("s", $session);
      $stmt->execute();
      $stmt->close();
    }
  }
?>