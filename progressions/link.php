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
      //Head towards camp.
      $newStage = $_POST['stage'];
      $campName = explode("\"", $_POST['linkText'])[1];
      
      include "../findCamp.php";
      include "../timePass.php";
      include "../explore.php";
      
      break;
    case 50:
    case 52:
    case 53:
      //Run.
      $newStage = 51;
      
      include "../updateStage.php";
      include "../damageRoll.php";
      include "../findCamp.php";
      include "../timePass.php";
      include "../explore.php";

      break;
    case 51:
      //Force fight.
      $newStage = 52;
      
      array_push($textArray, "You're forced to fight.");
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