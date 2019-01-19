<?php
  $stmt = $conn->prepare("SELECT stage FROM zmb_rw_sessions WHERE id=?");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->bind_result($stage);
  $stmt->fetch();
  $stmt->close();
  
  $_POST['stage'] = $stage;
?>