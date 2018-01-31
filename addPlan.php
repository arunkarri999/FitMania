<?php
include('helper_php/createPlan.php');
session_start();


if(isset($_POST['save']) && isset($_POST['planId']) && isset($_SESSION['newPlanMeals'])){
  $result = savePlan($_SESSION['newPlanMeals'],$_POST['planId']);
  if($result){
    echo "Plan added successfully, you will be redirected in a sec";
    header('refresh:3 ./planResult.php?action=view&id=' . $_POST['planId'] . '&week=1');
    exit;
  }
}
echo "You are in the wrong place bro..!";
header('refresh:3 ./index.php');
exit;

 ?>
