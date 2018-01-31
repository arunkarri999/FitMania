<?php
if (session_status() == PHP_SESSION_NONE) {
   session_start();
}
include('helper_php/createPlan.php');
include('helper_php/goal.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/__db/UsersRepository.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/login/loginCheck.php');
if(!LoginCheck::isLoggedIn()){
    $url = urlencode("/cheza/code/goalList.php");
		header('location: /cheza/code/login/login.php?location=' . $url);
		exit;
}
$currentUser = UsersRepository::getUserByEmail($_SESSION["Email"]);
$userId = $currentUser->getId();

//Variables initialization
$action = "";
$todayDate = new DateTime("now");
$plan = [];
$planId = 0;
$planName = "";
$goal = null;
$week = 1;
$numberOfWeeks = 0;
$previousDisabled = "";
$nextDisabled = "";

if(isset($_GET['action'])){
  switch($_GET['action']){
    //case new plan or refresh
    case 'new';
    $action = 'new';
    if(isset($_SESSION['beginDate']) && isset($_SESSION['endDate']) && isset($_SESSION['goalCal'])){
      $trackDate = date_create($_SESSION['beginDate']); // To be used with the loop below
      $beginDate = date_create($_SESSION['beginDate']);
      $endDate = date_create($_SESSION['endDate']);
      $period = date_diff($beginDate, $endDate);
      $planId = $_SESSION['newPlanId'];
      //Creating the plan
      $plan = createPlan($_SESSION['goalCal'],$period->days+1);
      $_SESSION['newPlanMeals'] = $plan;

      //Get the number of weeks and the active index (tab)
      $weeksAndIndex = getWeeksAndIndex($beginDate,$endDate);
      $numberOfWeeks = $weeksAndIndex['numberOfWeeks'];
      $activeTabIndex = $weeksAndIndex['activeTabIndex'];
      $planName = "New";
    }
      break;
    case 'view':
    $action = 'view';
    if(isset($_GET['id'])){
      $plan = getPlanById($_GET['id']);
      $goal = getGoalById($_GET['id'],$userId);
      $trackDate = date_create($goal['begin']); // To be used with the loop below
      $beginDate = date_create($goal['begin']);
      $endDate = date_create($goal['end']);
      $period = date_diff($beginDate, $endDate);

      //Get the number of weeks and the active index (tab)
      $weeksAndIndex = getWeeksAndIndex($beginDate,$endDate);
      $numberOfWeeks = $weeksAndIndex['numberOfWeeks'];
      $activeTabIndex = $weeksAndIndex['activeTabIndex'];
      $planName = $_GET['id'];
      $planId = $_GET['id'];
      if(empty($plan)){
        // do something
      }
    }
      break;
    default:
    echo "You are in the wrong place again .. bro!!!!";
      break;
  }
}

if(isset($_GET['week'])){
  $week += intval($_GET['week']);
  if(intval($_GET['week']) == 1){
    $previousDisabled = 'disabled';
  }
  if(intval($_GET['week']) ==  $numberOfWeeks){
    $nextDisabled = 'disabled';
  }
}
// var_dump($plan);
 ?>
 <!doctype html>
	<html lang="en">
		<head>
  			<meta charset="utf-8">
  			<meta name="viewport" content="width=device-width, initial-scale=1">
  			<title>planResult</title>
      		<!-- Bootstrap Core CSS -->
    		<link href="__lib/bootstrap.css" rel="stylesheet">
    		<!-- Bootstrap Core JS -->
    		<script src="__lib/bootstrap.js"></script>
    		<link href="__lib/main.css" rel="stylesheet">
    		<!-- Font Awesome Css -->
    		<link rel="stylesheet" href="__fontAwesome/font-awesome/css/font-awesome.min.css">
      		<link rel="stylesheet" href="/resources/demos/style.css">
  			<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  			<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  			<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  			<script>
        var theIndex = <?php echo $activeTabIndex; ?>;
          $( function() {
    				$( "#accordion" ).accordion({
      				collapsible: true,
              active: theIndex
    				});
  				});
  			</script>
		</head>
		<body class="body">
      <!-- Navigation Bar -->
      <?php include('includes/navigation.php'); ?>

<!--
   <window.onresize = function(){
    var widthOfMobile = $('#warpBackground'). outerHeight();
    var withoutMargin = $('#mobileWarp'). outerHeight();
    $('#mobileBg').css('height',widthOfMobile);
    $('#bg').css('height',withoutMargin);
}
-->
      <!-- Page Body Start -->

    		<!--begin of the planresult page-->
    	<div class="wrapper">
    		<div class="container container-fluid white-bg page">
   				<h1>Plan <?php echo $planName; ?></h1>

          <?php if (empty($action) || empty($plan)): ?>
            <div class="card">
              <div class="card-body text-center">
                <h1 class="text-muted">404</h1>
                <p>Something wrong with your URL</p>
              </div>
            </div>
          <?php endif; ?>

          <?php if (!empty($plan)): ?>

   				<p style="color:#868e96">You need to ingest: <strong><?php if(isset($_SESSION['goalCal'])){echo $_SESSION['goalCal'];}else{echo $goal['calories'];}?> Calories/day </strong>, to reach your goal weight
            <strong><?php if(!empty($_SESSION['goalWeight'])) {echo $_SESSION['goalWeight'];} else {echo $goal['goal_weight'];} ?> Kg </strong>, by <strong>
              <?php if(!empty($_SESSION['endDate'])){ echo $_SESSION['endDate']; }  else {echo $goal['end'];}?>
            </strong> </p>
  		  		<div class="p-r-button">
            <?php if ($action == 'view'): ?>
                <a class="link1 <?php echo $previousDisabled; ?>" href="planResult.php?action=view&id=<?php echo $planId?>&week=<?php echo $week-2;?>" >Previous Week</a>
                <a class="link2 <?php echo $nextDisabled; ?>" href="planResult.php?action=view&id=<?php echo $planId?>&week=<?php echo $week;?>">Next Week</a>
            <?php endif; ?>

              <form style="display:block" action="addPlan.php" method="post">
                <?php if (isset($_SESSION["newPlanId"]) && $action == 'new'): ?>
                  <a class="link1 <?php echo $previousDisabled; ?>" href="planResult.php?action=new&week=<?php echo $week-2;?>">Previous Week</a>
                  <a class="link2 <?php echo $nextDisabled; ?>" href="planResult.php?action=new&week=<?php echo $week;?>">Next week</a>
                  <input type="hidden" name="planId" value="<?php echo $_SESSION['newPlanId'] ?>">
                  <input type="hidden" name="save" value="save">
                  <input type="image" class="link3" name="savePlan" value="Save Plan">
                  <a class="link4" href="planResult.php?action=new&week=1">Refresh</a>
                <?php endif; ?>
              </form>
  		  		</div>
    			<div class="container">
    				<div id="accordion">
              <?php $weekTrack = intval(1);?>
              <?php $targetWeek = intval($week - 1); ?>
        			<?php foreach ($plan as $key => $day): ?>
              <?php

                  if($weekTrack > $targetWeek){
                    break;
                  }elseif($weekTrack < $targetWeek){
                    if(intval($trackDate->format('w')) == 0){
                      $weekTrack += 1;
                    }
                    $trackDate->add(new DateInterval('P1D'));
                    continue;
                  }else{

                  $passedClass = "";

                  //Give passed days diffrent backcolor
                  if($trackDate->format('Y-m-d') <  $todayDate->format('Y-m-d')){
                    $passedClass = "bg-secondary";
                  }
                  ?>

          			<h3 class="<?php echo $passedClass; ?>" style="color: #F2F2F2; background-color: #539092;border-color:#8BCFCC">Day <?php echo $key+1; ?>
                  <?php echo $trackDate->format('l'); ?>
                  <?php echo $trackDate->format('Y-m-d'); ?>
                  -  <?php echo $plan[$key][0]->calories + $plan[$key][1]->calories + $plan[$key][2]->calories; ?> Kcal
                </h3>

          			<div class="oneday">
        	  			<table class=" p-r-table">
            				<thead>
             					<tr>
              						<th class="p-r-head1" style="background-color:#8BCFCC">Breakfast</th>
              						<th class="p-r-head2" style="background-color:#8BCFCC">Lunch</th>
              						<th class="p-r-head3" style="background-color:#8BCFCC">Dinner</th>
              					</tr>
            				</thead>
             				<tbody>
             					<tr>
             						<?php foreach ($day as $timeId => $recipe): ?>
                					<td>
                					<div class="singlerecipe">
              	 						<a href="viewRecipe.php?id=<?php echo $recipe->id; ?>" name="recipe" class="s_r">
              	  						<div class="r_t">
                          					<!-- recipe image -->
                  			  				<img class="r_image" src="<?php echo $recipe->image_url; ?>"><br>
                  			  				<!-- recipe title -->
                  			  				<span class="r_n"><?php echo $recipe->name; ?></span><br>
                  			  				<!--recpe tag -->
                  			  				<span class="tag">Steamed </span>
                     	  					<span class="tag">Street food</span><br>
                      	  					<!-- reipe calories -->
                          					<span class="r_c">Calories: <?php echo $recipe->calories; ?></span>
                          				</div>
              	  						</a>
									</div>
                					</td>
              						<?php endforeach; ?>
              					</tr>
            				</tbody>
          				</table>
    			      </div>
                <?php     }
                  // echo "I reached a point";
                  if(intval($trackDate->format('w')) == 0){
                    // echo "I am in";
                    $weekTrack += 1;
                  }
                   $trackDate->add(new DateInterval('P1D'));
                  ?>
        				<?php endforeach; ?>
    				<?php endif; ?>
    				</div>
  				</div>
			</div>
		</div>
<style>
h1{
	color: black;
	font-family: "architects-daughter";
	padding-top: 20px;
}
.container{
	background-color: #fff;
	max-width: 960px;
}
.p-r-button{
	height: 90px;
	max-width: 960px;
	background-color:aliceblue;
	border-bottom: solid 1px #ECE7E7;
	margin-bottom: 30px;
}
.oneday{
	height: auto;
	width: 827px;
	background-color:#fff;
}
.link1{
	margin-top: 40px;
	display:inline-block;
	vertical-align:top;
	display:inline;
	zoom:1;
	padding:4px 10px;
	margin-left:10px;
	border-radius:4px;
	background:#73D9D8;
	color:#fff;
	float: left;
	text-decoration: none;
}
.link1:hover{
	background-color:#73D9D8;
	text-decoration: none;
	color: white;
}
.link2{
	margin-top: 40px;
	display:inline-block;
	vertical-align:top;
	display:inline;
	zoom:1;
	padding:4px 10px;
	margin-left:10px;
	border-radius:4px;
	background:#73D9D8;
	float: left;
	color:#fff;
	text-decoration: none;
}
.link2:hover{
	background-color:#73D9D8;
	text-decoration: none;
	color: white;
}
.link3{
	margin-top: 40px;
	display:inline-block;
	vertical-align:top;
	display:inline;
	zoom:1;
	padding:4px 10px;
	margin-left:10px;
	border-radius:4px;
	background:#73D9D8;
	float: left;
	color:#fff;
	text-decoration: none;
}
.link3:hover{
	background-color:#73D9D8;
	text-decoration: none;
	color: white;
}
	.link4{
	margin-top: 40px;
	display:inline-block;
	vertical-align:top;
	display:inline;
	zoom:1;
	float: right;
	padding:4px 10px;
	margin-right:10px;
	border-radius:4px;
	background:#ff3232;
	color:#fff;
	text-decoration: none;
}
.link4:hover{
	background-color:#ff3232;
	text-decoration: none;
	color: white;
}
.panel-default>.panel-heading{
  	color: #333;
  	background-color: #fff;
  	border-color: #e4e5e7;
  	padding: 0;
  	-webkit-user-select: none;
  	-moz-user-select: none;
  	-ms-user-select: none;
  	user-select: none;
}
.panel-default>.panel-heading a{
  	display: block;
  	padding: 10px 15px;
}
.panel-default>.panel-heading a:after{
  	content: "";
  	position: relative;
  	top: 1px;
  	display: inline-block;
  	font-family: 'Glyphicons Halflings';
  	font-style: normal;
  	font-weight: 400;
  	line-height: 1;
  	-webkit-font-smoothing: antialiased;
  	-moz-osx-font-smoothing: grayscale;
  	float: right;
  	transition: transform .25s linear;
  	-webkit-transition: -webkit-transform .25s linear;
}
.panel-default>.panel-heading a[aria-expanded="true"]{
  	background-color: #eee;
}
.panel-default>.panel-heading a[aria-expanded="true"]:after{
  	content: "\2212";
  	-webkit-transform: rotate(180deg);
  	transform: rotate(180deg);
}
.panel-default>.panel-heading a[aria-expanded="false"]:after{
  	content: "\002b";
  	-webkit-transform: rotate(90deg);
  	transform: rotate(90deg);
}
.accordion-option{
  	width: 100%;
  	float: left;
  	clear: both;
  	margin: 15px 0;
}
.accordion-option .title{
  	font-size: 20px;
  	font-weight: bold;
  	float: left;
  	padding: 0;
  	margin: 0;
}
.accordion-option .toggle-accordion{
  	float: right;
  	font-size: 16px;
  	color: #6a6c6f;
}
.accordion-option .toggle-accordion:before{
  	content: "Expand All";
}
.accordion-option .toggle-accordion.active:before{
  	content: "Collapse All";
}
.singlerecipe{
	margin-top: 3px;
	width: 250px;
	height: 314px;
	text-decoration: none;
	background-color:#FFEEF5;
	border: 1px solid white;
	text-align: center;
}
/*	#F5FAC8*/
.singlerecipe:hover{
	text-decoration: none;
	border: 1.5px solid #FF8C94;
	height: 314px;
	width: 250px;
}
	.p-r-head1 {
  display:table-column-1;
  vertical-align: middle;
		height: 35px;
		width: 250px;
		color: #F2F2F2;
  -webkit-transform: perspective(1px) translateZ(0);
  transform: perspective(1px) translateZ(0);
  box-shadow: 0 0 1px transparent;
  position: relative;
  overflow: hidden;
}
.p-r-head1:before {
  content: "";
  position: absolute;
  z-index: -1;
  left: 0;
  right: 0;
  bottom: 0;
  background: #525050;
  height: 4px;
  -webkit-transform: translateY(4px);
  transform: translateY(4px);
  -webkit-transition-property: transform;
  transition-property: transform;
  -webkit-transition-duration: 0.3s;
  transition-duration: 0.3s;
  -webkit-transition-timing-function: ease-out;
  transition-timing-function: ease-out;
}
.p-r-head1:hover:before, .p-r-head1:focus:before, .p-r-head1:active:before {
  -webkit-transform: translateY(0);
  transform: translateY(0);
}
	.p-r-head2 {
  display:table-column-2;
  vertical-align: middle;
		height: 35px;
		width: 250px;
		color: #F2F2F2;
  -webkit-transform: perspective(1px) translateZ(0);
  transform: perspective(1px) translateZ(0);
  box-shadow: 0 0 1px transparent;
  position: relative;
  overflow: hidden;
}
.p-r-head2:before {
  content: "";
  position: absolute;
  z-index: -1;
  left: 0;
  right: 0;
  bottom: 0;
  background: #525050;
  height: 4px;
  -webkit-transform: translateY(4px);
  transform: translateY(4px);
  -webkit-transition-property: transform;
  transition-property: transform;
  -webkit-transition-duration: 0.3s;
  transition-duration: 0.3s;
  -webkit-transition-timing-function: ease-out;
  transition-timing-function: ease-out;
}
.p-r-head2:hover:before, .p-r-head2:focus:before, .p-r-head2:active:before {
  -webkit-transform: translateY(0);
  transform: translateY(0);
}
	.p-r-head3{
  display:table-column-3;
  vertical-align: middle;
		height: 35px;
		width: 250px;
		color: #F2F2F2;
  -webkit-transform: perspective(1px) translateZ(0);
  transform: perspective(1px) translateZ(0);
  box-shadow: 0 0 1px transparent;
  overflow: hidden;
}
.p-r-head3:before {
  content: "";
  position: absolute;
  z-index: -1;
  left: 0;
  right: 0;
  bottom: 0;
  background: #525050;
  height: 4px;
  -webkit-transform: translateY(4px);
  transform: translateY(4px);
  -webkit-transition-property: transform;
  transition-property: transform;
  -webkit-transition-duration: 0.3s;
  transition-duration: 0.3s;
  -webkit-transition-timing-function: ease-out;
  transition-timing-function: ease-out;
}
.p-r-head3:hover:before, .p-r-head3:focus:before, .p-r-head3:active:before {
  -webkit-transform: translateY(0);
  transform: translateY(0);
}

.s_r{
	text-decoration: none;
}
.s_r:hover{
	text-decoration: none;
}
	.r_t{
	background-color:#FFEEF5;
}
.r_n{
	font-family: "architects-daughter";
	font-size: 1.2em;
	font-weight: bold;
	color:dimgray;
}
.r_c{
	font-family: "architects-daughter";
	font-size: 0.9em;
	color: gray;
}
.r_p{
	position:absolute;
	right: 70px;
	bottom: 0px;
}
.tag{
	font-family: "architects-daughter";
	font-size: 0.9em;
	color: gray;
}
.r_a{
	font-family: "architects-daughter";
	font-size: 0.9em;
	color: gray;
}
.r_image{
	padding-top: 3px;
	width: 220px;d
	height: 220px;
}
/*
.wrapper{
	min-height:800px;
	padding-bottom:20px;
}
*/
/*
.searchForm{
		background-color:#fffefa;
		padding-bottom:20px;
		padding-top:10px;
		margin-bottom: 10px;
		box-shadow: 0 0px 10px 0 rgba(10, 10, 10, 0.2), 0 10px 20px 0 rgba(10, 10, 10, 0.60);
}
*/
/*
.body{
	display: block;
	position: relative;
}
.body:after {
    content : "";
    display: block;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    down: 0;
    background:url('images/bg.jpg') no-repeat fixed center;
    width: 100%;
    height: 100%;
    opacity : 0.7;
    z-index: -1;
}
*/
.disabled {
   pointer-events: none;
   cursor: default;
   background-color: #bbb;
}
</style>
</body>
</html>


<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
