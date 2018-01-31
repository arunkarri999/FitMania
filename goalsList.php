<?php
ini_set('display_errors',1);
ini_set('display_startup_errors' ,1);
error_reporting(E_ALL);

include_once("login/loginCheck.php");
include_once("__db/UsersRepository.php");
include_once("helper_php/goal.php");
$userId = null;
if(!LoginCheck::isLoggedIn()){
  header('Location: login/login.php');
  exit;
}else{
  $user = UsersRepository::getUserByEmail($_SESSION["Email"]);
  $userId = $user->getId();
}

$USER = 'fit';
$PASSWORD = 'fit';
$SERVER = 'localhost';
$DB = 'fit_mania';

$conn = mysqli_connect($SERVER, $USER, $PASSWORD, $DB);


$goals = [];
$goals = getAllMyGoals($userId);



// foreach ($goals as $key => $goal) {
//   echo $key;
//   foreach ($goal as $key2 => $value) {
//     echo " ". ": " . $key2 . " ". $value;
//   }
//   echo "</br>";
// }

 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
    <title>My Goals</title>
    <!-- Bootstrap Core CSS -->
    <link href="__lib/bootstrap.css" rel="stylesheet">
    <!-- Custom  CSS -->
    <link href="__lib/singleRecipe.css" rel="stylesheet">
	 <!-- Custom  CSS -->
    <link href="__lib/recipe.css" rel="stylesheet">
    <link href="__lib/main.css" rel="stylesheet">
    <!-- Bootstrap Core JS -->
    <script src="__lib/bootstrap.js"></script>
    <!-- Font Awesome Css -->
    <link rel="stylesheet" href="__fontAwesome/font-awesome/css/font-awesome.min.css">
    <script type="text/javascript">
    	function delcfm(){
    		if (!confirm("Do you want delete you gaol and plan?")) {
            	return false;
        	}
    	}
    	// function viewPlan(planId){
    	// 	var url='planResult.php?action=view&id='+planId+'&week=1';
    	// 	//alert(url);
    	// 	window.event.returnValue=false;
    	// 	window.location.href='planResult.php?action=view&id='+planId+'&week=1';
    	// }
    </script>
</head>
<body class="body">
<!-- Navigation Bar -->
<?php include('includes/navigation.php'); ?>

<!-- Page Body Start -->
<div class="wrapper">
<div class="container conrainer-fluid page">

	<h1>My Goals</h1>
	<div class="container">
	<div class="row">
<?php
if($goals!=null){
	foreach ($goals as $goal){
		?>



			<form method="post" >

			<div class="col">
			<div class="card bg-light " style="width: 30rem;">
		    <h1></h1>
			<input type="hidden" name="planId" value="<?php echo $goal['id'];?>">
			<input type="hidden" name="goalCal" value="<?php echo $goal['calories'];?>">
			<input type="hidden" name="begin" value="<?php echo $goal['begin'];?>">
			<input type="hidden" name="end" value="<?php echo $goal['end'];?>">
			<input type="hidden" name="goalWeight" value="<?php echo $goal['goal_weight'];?>">
			<div><h4 class="card-header font-weight-bold text-center text-primary"><?php echo $goal['dietary'];?></h4></div>
			<div class="card-body">
			<div><p class="card-text ">
			To <?php echo $goal['goal_weight']?>KG</div>
			<div>Habit: <?php if($goal['active']==0){
				echo "Sedentary";
			}
			elseif($goal['active']==1){
				echo "Lightly";
			}
			elseif($goal['active']==2){
				echo "Moderate";
			}
			else{echo "Very Active";}
			?></div>
			<div>From <?php echo $goal['begin'];?></div>
			<div>To <?php echo $goal['end'];?></div></p>

			<?php if($goal['status']=="0"){
				echo "<div>You have No Plan!</div>";
				echo "<input class=\"btn btn-info\" type=\"submit\" name=\"submit\" value=\"Continue Planning!\" formaction=\"helper_php/setSession.php\">";

			}
			else{
				echo "<div>You have Plan for this goal!</div>";

				echo "<a class=\"btn btn-info\" href=\"planResult.php?action=view&id=".$goal['id']."&week=1\">My Plan</a>";

			}?>
			</form>

			<form style="display: inline;"method="POST" onsubmit="return delcfm()">
			<input type="hidden" name="planId" value="<?php echo $goal['id'];?>">
			<button class="btn btn-danger" type="submit" formaction="helper_php/deleteGoal.php" name="delete" ><span class="fa fa-trash" aria-hidden="true"></span></button>
			</div>
			</div>
			</div>
			</form>
<?php
	}
}
?>




<!-- </form> -->
</div>
</div>

<?php if ($goals == null): ?>
  <div class="card">
    <div class="card-body text-center">
      <h1 class="text-muted">It is Empty..! :(</h1>
      <p>You have no goals yet!... Why not start today...!!</p>
      <a class="badge badge-primary" href="selectDietary.php">Add New Goal!</a>
    </div>
  </div>
<?php endif; ?>

</div>
</div>
</body>
</html>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
