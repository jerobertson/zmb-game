<?php
  include "../connection.php";
  
  $newStage = 0;
  $textArray = array();
  $valueArray = array("k"=>$_POST['k']);
  include "../getStage.php";
  include "../healthCheck.php";
  include "../dangerRoll.php";
  
  switch ($_POST['stage']) {
    case 30:
    case 40:
      //Explore.
      $newStage = $_POST['stage'];
      
      include "../timePass.php";
      include "../explore.php";
      
      break;
    default:
      break;
  }
  
  $valueArray['stage'] = $newStage;
  include "../generateCampList.php";
  include "../generateMap.php";
  include "../getStageText.php";
  echo json_encode($valueArray);
  $conn->close();
?>