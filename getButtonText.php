<?php
  include "connection.php";
  
  $stmt = $conn->prepare("SELECT a1.action 'btn1', a2.action 'btn2', a3.action 'btn3', a4.action 'btn4', a5.action 'btn5', a6.action 'btn6', aT.action 'btnText' FROM zmb_ro_stages s LEFT JOIN zmb_ro_actions a1 ON s.button1 = a1.id LEFT JOIN zmb_ro_actions a2 ON s.button2 = a2.id LEFT JOIN zmb_ro_actions a3 ON s.button3 = a3.id LEFT JOIN zmb_ro_actions a4 ON s.button4 = a4.id LEFT JOIN zmb_ro_actions a5 ON s.button5 = a5.id LEFT JOIN zmb_ro_actions a6 ON s.button6 = a6.id LEFT JOIN zmb_ro_actions aT ON s.buttonText = aT.id WHERE s.id = ?");
  $stmt->bind_param("i", $_POST['stage']);
  $stmt->execute();
  $stmt->bind_result($btn1, $btn2, $btn3, $btn4, $btn5, $btn6, $btnText);
  $stmt->fetch();
  $stmt->close();
  $conn->close();
  echo json_encode(array("btn1"=>$btn1,"btn2"=>$btn2,"btn3"=>$btn3,"btn4"=>$btn4,"btn5"=>$btn5,"btn6"=>$btn6,"btnText"=>$btnText));
?>