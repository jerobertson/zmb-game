<?php
  include "connection.php";
  
  $stmt = $conn->prepare("DELETE FROM zmb_rw_fires WHERE session=?");
  $stmt->bind_param("s", $_GET['k']);
  $stmt->execute();
  $stmt->close();
  
  $stmt = $conn->prepare("DELETE FROM zmb_rw_items WHERE session=?");
  $stmt->bind_param("s", $_GET['k']);
  $stmt->execute();
  $stmt->close();

  $stmt = $conn->prepare("DELETE FROM zmb_rw_positions WHERE session=?");
  $stmt->bind_param("s", $_GET['k']);
  $stmt->execute();
  $stmt->close();
  
  $stmt = $conn->prepare("DELETE FROM zmb_rw_rareitems WHERE session=?");
  $stmt->bind_param("s", $_GET['k']);
  $stmt->execute();
  $stmt->close();

  $stmt = $conn->prepare("DELETE FROM zmb_rw_sessions WHERE id=?");
  $stmt->bind_param("s", $_GET['k']);
  $stmt->execute();
  $stmt->close();
  
  $stmt = $conn->prepare("UPDATE zmb_rw_sessionscreationtime SET removed=1 WHERE session=?");
  $stmt->bind_param("s", $_GET['k']);
  $stmt->execute();
  $stmt->close();
  
  $conn->close();
  
  echo "Session " . $_GET['k'] . " deleted from database.";
?>