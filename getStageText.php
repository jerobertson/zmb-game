<?php
  $stmt = $conn->prepare("SELECT text FROM zmb_rw_sessions JOIN zmb_ro_stages ON (zmb_rw_sessions.stage = zmb_ro_stages.id) WHERE zmb_rw_sessions.id=?");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->bind_result($stageText);
  $stmt->fetch();
  $stmt->close();
  
  array_push($textArray, $stageText);
  $valueArray['text'] = $textArray;
?>