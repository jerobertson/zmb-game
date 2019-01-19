<?php
  $stmt = $conn->prepare("SELECT x,y FROM zmb_rw_sessions WHERE id=?");
  $stmt->bind_param("s", $_POST['k']);
  $stmt->execute();
  $stmt->bind_result($x, $y);
  $stmt->fetch();
  $stmt->close();
  
  $stmt = $conn->prepare("UPDATE zmb_rw_positions SET altName=? WHERE session=? AND x=? AND y=?");
  $stmt->bind_param("ssii", str_replace("\"", "", $_POST['textInput']), $_POST['k'], $x, $y);
  $stmt->execute();
  $stmt->bind_result($x, $y);
  $stmt->fetch();
  $stmt->close();
?>