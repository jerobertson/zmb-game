<?php
  include "../connection.php";
  
  $newStage = 0;
  $textArray = array();
  $valueArray = array("k"=>$_POST['k']);
  include "../getStage.php";
  include "../healthCheck.php";
  include "../dangerRoll.php";
  
  switch ($_POST['stage']) {
    case 21:
      //Progress k to stage 30. Stoke the fire.
      $newStage = 30;
      
      include "../updateStage.php";
      include "../stokeFire.php";
      
      break;
    case 30:
      //Stoke the fire.
      $newStage = $_POST['stage'];
      
      include "../stokeFire.php";
      
      break;
    case 40:
      //Create camp.
      $newStage = $_POST['stage'];
      
      include "../createCamp.php";
      
      break;
    case 50:
    case 52:
    case 53:
      //Fight.
      $newStage = 52;
      
      include "../fight.php";

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