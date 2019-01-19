<?php
  include "connection.php";

  if (session_id() == "") {
    session_start();
  }
  $k = session_id();
  
  if ($_POST['k'] == "") {
    include "createSession.php";
  }
  else {
    $stmt = $conn->prepare("SELECT stage,text FROM zmb_rw_sessions JOIN zmb_ro_stages ON (zmb_rw_sessions.stage = zmb_ro_stages.id) WHERE zmb_rw_sessions.id=?");
    $stmt->bind_param("s", $_POST['k']);
    $stmt->execute();
    $stmt->bind_result($stage, $text);
    $stmt->fetch();
    $stmt->close();
    
    if ($stage == null) {
      $k = session_id();
      include "createSession.php";
    }
    else {
      $k = $_POST['k'];
    }
  }
  
  $conn->close();
  echo json_encode(array("k"=>$k,"stage"=>$stage,"text"=>$text,"rows"=>$rowCount));
?>