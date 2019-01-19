<?php
  $stmt = $conn->prepare("UPDATE zmb_rw_sessions SET stage=? WHERE id=?");
  $stmt->bind_param("is", $newStage, $_POST['k']);
  $stmt->execute();
  $stmt->close();
?>