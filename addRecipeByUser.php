 <?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
ini_set('display_errors',1);
ini_set('display_startup_errors' ,1);
error_reporting(E_ALL);
include_once("helper_php/units.php");
include_once("helper_php/usda.php");


require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/__db/RecipesRepository.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/__db/UsersRepository.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/login/loginCheck.php');
if(!LoginCheck::isLoggedIn()){
    $url = urlencode("/cheza/code/addRecipeByUser.php");
		header('location: /cheza/code/login/login.php?location=' . $url);
		exit;
}
$currentUser = UsersRepository::getUserByEmail($_SESSION["Email"]);
$userId = $currentUser->getId();
// var_dump($_POST);
    //echo $userId;
//var_dump($_POST);
/*$SERVERNAME = 'localhost';
$DB ='fit_mania';
$USER = 'fit';
$PASSWORD = 'fit';
$conn = mysqli_connect($SERVERNAME, $USER, $PASSWORD, $DB);
if(!$conn){
	die('ERROR in connecting to database');
}else {
}*/
//$n = 0;
?>

<!-- <script type="text/javascript" src="http://libs.baidu.com/jquery/2.0.0/jquery.min.js"></script> -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<script type="text/javascript">
	/*var n = 1;
	function cloneRow(){
		var first = $("#ingredientsTable tr").eq(0);
		var tr = first.html().replace(/0/g, n );
	    //alert(n);

        tr = '<tr name="' + (first[0].id.slice(0, -1) + n) + '">' + tr + '</tr>'
        $('#ingredientsTable').append(tr);
        n++;
        document.getElementById("count").value=n;
	}*/
	function cloneRow() {
		var tableID = "ingredientsTable";
    var table = document.getElementById(tableID);
		var rowCount = table.rows.length;
		if(rowCount <= 20){
  		//alert(rowCount);                        // limit the user from creating fields more than your limits
  		var row = table.insertRow(rowCount);
  		var colCount = table.rows[0].cells.length;
  		for(var i=0; i <colCount; i++) {
  			var newcell = row.insertCell(i);
  			newcell.innerHTML = table.rows[rowCount-1].cells[i].innerHTML;
  		}

		}else{
		 alert("Maximum types of ingredients is 20");

		}

    bindAutoComplete();

	}

	function deleteRow(tableID,obj) {
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;
		if(rowCount>=3){
			$(obj).parent().parent().remove();
		} else{
			alert("As a recipe you must have more than 1 ingredient!");
		}
	}
</script>
<!DOCTYPE html>
<html>
<head>
	<title>Add recipe by user</title>
	<!-- Bootstrap Core CSS -->
    <link href="__lib/bootstrap.css" rel="stylesheet">
    <!-- Bootstrap Core JS -->
    <script src="__lib/bootstrap.js"></script>
	 <!-- <link href="__lib/dietary.css" rel="stylesheet"> -->
	 <link href="__lib/addRecipe.css" rel="stylesheet">
     <link href="__lib/main.css" rel="stylesheet">

   <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

   <!-- autocomplete -->
   <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" />
   <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
   <script type="text/javascript" src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
   <!-- <script type="text/javascript" src='/JavaScriptSpellCheck/include.js' ></script> -->


   <script type="text/javascript">
   $(document).ready(function() {
     bindAutoComplete();
   });

   function bindAutoComplete() {
     $(".auto").autocomplete({
         source: "helper_php/autocomplete.php",
         minLength: 1
     });
   }
   </script>


</head>
<body class="body">

  <!-- Navigation Bar -->
  <?php include('includes/navigation.php'); ?>

  <!-- Page Body Start -->
  <div class="wrapper">
  <div class="container conrainer-fluid white-bg page">


    <?php
    $message =  array('text' => '', 'has' => false);
    //check data integrity
    $dataIntegrity = TRUE;
    if(isset($_POST['add_recipe'])){
      foreach ($_POST['ingredient_name'] as $key => $value) {
        $ndbno = FoodApi::getIngredientNdbno($_POST['ingredient_name'][$key]);
        if(!$ndbno){
          $dataIntegrity = FALSE;
          $message = array('text' => 'No match found for ingredient: ' . $_POST['ingredient_name'][$key] ,  'has' => true);
        }
      }
    }
    // echo $dataIntegrity;

  		if(isset($_POST['add_recipe']) && $dataIntegrity == TRUE){

  			$count = $_POST['count'];
  			//var_dump($_FILES);
  			//Upload the pic
  			$FileName = $_FILES['image']['name'];
  			$ImageFile = $_FILES['image']['tmp_name'];
  			//echo $FileName;
  			move_uploaded_file($ImageFile, "Img/recipes/$FileName");

  			$sql = "INSERT INTO `recipes`(`name`, `serving`, `description`, `image_url`, `calories`, `tag`, `meal`,`user_id`,`is_approved`)
  				VALUES ('$_POST[recipe_name]', '$_POST[serving]', '$_POST[description]', '/cheza/code/Img/recipes/$FileName', '$_POST[calories]', '$_POST[tag]', '$_POST[meal]','$userId',0);";
  			//echo $sql;
  		if ($conn->query($sql) == TRUE) {
  			//echo "Insert the recipt to database successfully";
  			$sql2 ="SELECT LAST_INSERT_ID()";
  			$result = $conn->query("SELECT LAST_INSERT_ID()");
  			$row = $result->fetch_assoc();
  			$recipeId = $row["LAST_INSERT_ID()"];

  			//echo $recipeId;
  		} else {
  			echo "OPPS,Something wrong happenned in insert recipe!";
  		}

  			/*for($i=0;$i<$count;$i++){
  				$namekey = "name_" . $i;
  				$unitkey = "unit_" . $i;
  				$numberkey = "number_" . $i;

  				$sql3 = "INSERT INTO `ingredients`(`name`, `unit`, `amount`, `recipe_id`)
  						VALUES ('$_POST[$namekey]', '$_POST[$unitkey]', '$_POST[$numberkey]',$recipeId);";*/
              // var_dump($_POST);
  				foreach ($_POST['ingredient_name'] as $key => $value) {
  					# code...
  					//echo $_POST['ingredient_number'][$key]."</br>";
            $ndbno = FoodApi::getIngredientNdbno($_POST['ingredient_name'][$key]);
            //echo $ndbno . "</br>";
            //echo $_POST['ingredient_name'][$key] . "</br>";
  					$temp_ingredients_number = $_POST['ingredient_number'][$key];
  					$sql3 = "INSERT INTO ingredients(name, amount, unit, recipe_id, ndbno,amount_gram)
  									VALUES ('{$value}',".  $_POST['ingredient_number'][$key] .", " .  $_POST['ingredient_unit'][$key] ." ,$recipeId ,$ndbno,".$_POST['amountInGram'][$key]." );";
  				if ($conn->query($sql3) == TRUE) {
  					//echo "Insert the ingredients" . $i . " to database successfully</br>";
  					} else {
  					echo "OPPS,Something wrong happenned!". $conn->error;
  					echo "</br>". $sql3;
  					}
            // echo $conn->error;
  			}
  		$conn->close();
      $url = "myCreatedList.php";
      echo "<script type='text/javascript'>";
      echo "window.location.href='$url'";
      echo "</script>";
      exit;
    } else{
      if($message['has']){
        echo "<div class=\"alert alert-warning\" role=\"alert\">" . $message['text'] . "</div>";
      }
    }

  	?>



	<form method="post" enctype="multipart/form-data" style='background-color:#fffefa'>
		<h1> Add Recipe</h1>
		<div class="form-group">
		   <label for="exampleInputName"> Name </label>
		   <input name="recipe_name" type="text" class="form-control" id="exampleInputName" aria-describedby="nameHelp" placeholder="Enter name" pattern=".{1,50}" required title="1-50 characters" value="<?php if($message['has']) {echo $_POST['recipe_name'];} ?>">
		</div>
		<div class="form-group">
		   <label for="exampleInputServing"> Serving </label>
		   <input name="serving" type="number" class="form-control" id="exampleInputServing" aria-describedby="servingHelp" placeholder="Serving number" pattern=".{1,50}" required title="1-50 people" value="<?php if($message['has']) {echo $_POST['serving'];} ?>">
		</div>
		<div class="form-group">
		   <label for="exampleInputCalories"> Calories </label>
		   <input name="calories" type="text" class="form-control" id="exampleInputCalories" aria-describedby="caloriesHelp" placeholder="Enter Calories" pattern=".{1,}" required title="every recipe has calories" value="<?php if($message['has']) {echo $_POST['calories'];} ?>">
		</div>
		<div class="form-group">
		   <label for="exampleInputTag"> Tag </label>
		   <input name="tag" type="text" class="form-control" id="exampleInputTag" aria-describedby="tagHelp" placeholder="Enter tag" pattern=".{1,100}" required title="1-100 characters" value="<?php if($message['has']) {echo $_POST['tag'];} ?>">
		   <small id="emailHelp" class="form-text text-muted">The tags should be comma separeated. e.g: beef,lamp,pizza,etc </small>
		</div>
		<div class="form-group">
		   <label for="exampleInputMeal"> Meal </label>
		   <input name="meal" type="text" class="form-control" id="exampleInputMeal" aria-describedby="mealHelp" placeholder="Enter meal type" pattern=".{1,50}" required title="1-50 characters" value="<?php if($message['has']) {echo $_POST['meal'];} ?>">
			<small id="emailHelp" class="form-text text-muted">e.g: breakfast, lunch, dinner</small>
		</div>
		<div class="form-group">
		   <label for="exampleInputMeal"> Picture </label>
		   <input name="image" type="file" class="form-control-file" id="exampleInputPicture">
		</div>
		<!---Add thee address to store the pic later-->
		<div class="form-group">
		   <label  for="exampleInputDescription"> Description </label>
		   <textarea name="description" class="form-control" id="description" rows="3" ><?php if($message['has']) {echo $_POST['description'];} ?></textarea>
		</div>
        <div class="form-group">
		   <label for="exampleInputIngredients"> Ingredients </label>

		</div>


	<table class="table" id="ingredientsTable">
        <thead>
        <tr class="form-group">
           <th scope="col">Name </th>
           <th scope="col">Number</th>
           <th scope="col">Unit </th>
           <th scope="col">Amount In Gram</th>
           <th scope="col">Delete</th>
        </tr>
        </thead>
        <tbody>
		<tr>
            <th scope="row"><input type="text" class="form-control auto" name="ingredient_name[]" id="exampleInputName" aria-describedby="nameHelp" placeholder="Enter name" pattern=".{1,150}" required title="1-150 characters"></th>
             <td><input type="number" class="form-control" name="ingredient_number[]" id="exampleInputNumber" aria-describedby="numberHelp" placeholder="Enter number" pattern=".{0,10000}" required title="0-10000"></td>
             <td><!--I would add the option base on the database later-->
						<select class="form-control" id="unit_0" name="ingredient_unit[]">
							<?php
							/*
								$sql4 = "SELECT * FROM units";
								$result = $conn -> query($sql4);
								while ($row = $result -> fetch_assoc()) {
									echo "<option value = ". $row['name'].">" . $row['name']. "</option>";
								}	*/
								foreach (getUnits() as $key => $value) {
                			echo "<option  value=\"" . $key ."\">" .  $value . "</option>";
                }
							?>
						</select>
			</td>
             <td>
              <input type="number" class="form-control" name="amountInGram[]" id="exampleInputAmountInGram" aria-describedby="numberHelp" placeholder="Enter number" pattern=".{0,10000}" required title="0-10000">
             </td>
             <td><button type="button" class="btn btn-outline-primary" onclick="deleteRow('ingredientsTable',this);">delete</button></td>
         </tr>
    </tbody>
	</table>
  <div>
    <h1><button type="button" class="btn btn-outline-primary" onclick="cloneRow();">+ Add ingredients</button></h1>
    <input type="hidden" id="count" name="count" value="1">
  </div>
	<input name="add_recipe" value="Add Recipe" type="submit" class="btn btn-primary btn-lg btn-block">


	</form>





	</div>
	</div>
  <!-- autocomplete script -->
  <!--<script type="text/javascript">
  $(function () {

      //autocomplete
      $(".auto").autocomplete({
          source: "helper_php/autocomplete.php",
          minLength: 1
      });
      // $(".auto").spellAsYouType();

  });
</script>-->

  <!-- end autocomplete -->
</body>
</html>
