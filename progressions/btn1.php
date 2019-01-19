<?php
  include "../connection.php";
  
  $newStage = 0;
  $textArray = array();
  $valueArray = array("k"=>$_POST['k']);
  include "../getStage.php";
  include "../healthCheck.php";
  include "../dangerRoll.php";
  
  switch ($_POST['stage']) {
    case 1:
      //Progress k to stage 10.
      $newStage = 10;
      
      include "../updateStage.php";
      
      break;
    case 10:
      //Progress k to stage 20.
      $newStage = 20;
      
      include "../updateStage.php";

      break;
    case 20:
      //Progress k to stage 21. Discover "Your house." location.
      $newStage = 21;
      $locationId = 2;
      $altName = "Your house.";
      $x = 0;
      $y = 0;
      $firewood = 3;
      
      include "../updateStage.php";
      include "../discoverLocation.php";

      break;
    case 21:
    case 30:
    case 40:
      //Show location.
      $newStage = $_POST['stage'];
      
      include "../getLocation.php";

      break;
    case 50:
    case 52:
    case 53:
      //Run.
      $newStage = 51;
      
      include "../updateStage.php";
      include "../damageRoll.php";
      include "../timePass.php";
      include "../explore.php";

      break;
    case 51:
      //Fight.
      $newStage = 52;
      
      include "../fight.php";

      break;
    case 4000:
      //Update death.
      $newStage = 4001;
      
      include "../updateStage.php";
      include "../overrideCampsList.php";
      
      array_push($textArray, "You can't get up.");
      
      break;
    case 4001:
      //Update death 2.
      $newStage = 4002;
      
      include "../updateStage.php";
      include "../overrideCampsList.php";
      
      array_push($textArray, "You close your eyes.");
      
      break;
    case 4002:
      //Reset player.
      $newStage = 1;
      
      include "../updateStage.php";
      include "../resetSession.php";
      include "../overrideCampsList.php";
      
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