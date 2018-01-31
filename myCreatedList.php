<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/__db/RecipesRepository.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/__db/UsersRepository.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/login/loginCheck.php');
if(!LoginCheck::isLoggedIn()){
    $url = urlencode("/cheza/code/myCreatedList.php");
    header('location: /cheza/code/login/login.php?location=' . $url);
    exit;
}
$currentUser = UsersRepository::getUserByEmail($_SESSION["Email"]);
$userId = $currentUser->getId();
//var_dump($userId);

ini_set('display_errors',1);
ini_set('display_startup_errors' ,1);
error_reporting(E_ALL);
$SERVERNAME = 'localhost';
$DB ='fit_mania';
$USER = 'fit';
$PASSWORD = 'fit';
$conn = new mysqli($SERVERNAME, $USER, $PASSWORD, $DB);
if($conn->connect_error){
	die('ERROR in connecting to database');
}
?>

<!DOCTYPE html>
<html>
    <script src="https://cdn.bootcss.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <!-- Bootstrap Core CSS -->
    <link href="__lib/bootstrap.css" rel="stylesheet">
    <!-- Custom  CSS -->
    <link href="__lib/singleRecipe.css" rel="stylesheet">
    <link href="__lib/main.css" rel="stylesheet">
	<link href="__lib/favorite-list.css" rel="stylesheet">
    <!-- Bootstrap Core JS -->
    <script src="__lib/bootstrap.js"></script>
    <!-- Font Awesome Css -->
    <link rel="stylesheet" href="__fontAwesome/font-awesome/css/font-awesome.min.css">
<head>
	<title>My receipe list</title>
</head>
<body class="body">
  <!-- Navigation Bar -->
  <?php include('includes/navigation.php'); ?>

  <!-- Page Body Start -->
<div class="wrapper">
<div class="container conrainer-fluid white-bg page">
<h1>My Recipes</h1>
<form method="get" enctype="multipart/form-data">
	<div class="main-page1" style="margin-top: 30px; padding-top: 25px; border-top:1px solid #BABABA">
		<!-- <form method="get">
			Please input the User id to test:
			<input type="number" name="user_id">
			<input type="submit" name="user_id_button" value="search">
		</form> -->

	<div class="container">
	 <div class="row">

		<?php
		// if (isset($_GET['user_id_button'])) {
			// $user_id = $_GET['user_id'];
			$sql = "SELECT * FROM `recipes` WHERE user_id = $userId";
			$result = $conn ->query($sql);
			if (mysqli_num_rows($result)==0) {
				?>
        <div class="col-12">
				      <div class="card">
                  <div class="card-body text-center">
                      <h1 class="text-muted">No results found</h1>
                      <p>There are no items in your created list</p>
                  </div>
              </div>
          </div>
				<?php
			}else{
			while ($row = $result->fetch_assoc()) {
					?>
					<div class="col">
			        <div class="card bg-light" style="width: 25rem;">
					<div class="col-12 ">
					<div class="card-body">
						<a link href="editR <?php ?>">
						<a href="editRecipeByUser.php?recipe_id=<?php echo $row['id'];?>&recipe_id_button=search" class="s_r">
							<div>
							<h4 class="card-header font-weight-bold text-center text-primary"><?php echo $row['name'];?></h4>
							</div>
							<img src="<?php echo $row['image_url'];?>" height="325px" width="325px">
						</a>
					</div>
					</div>
					</div>
					</div>
					<?php
				}
			}
		?>
	</div>
</div>
</div>
	</form>
	</div>
</div>
</body>
</html>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
