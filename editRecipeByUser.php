<?php if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/__db/RecipesRepository.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/__db/UsersRepository.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/login/loginCheck.php');
include_once("helper_php/units.php");
include_once("helper_php/usda.php");
if(!LoginCheck::isLoggedIn()){
    $url = urlencode("/cheza/code/myCreatedList.php");
    header('location: /cheza/code/login/login.php?location=' . $url);
    exit;
}
$currentUser = UsersRepository::getUserByEmail($_SESSION["Email"]);
$userId = $currentUser->getId();
$recipe_id = NULL;
// // if(!empty($_SESSION["edit_recipe_id"])){
// // $recipe_id = $_SESSION["edit_recipe_id"];

// var_dump($recipe_id);
// }





if(isset($_GET['recipe_id'])){
  $recipe_id = $_GET['recipe_id'];

}

ini_set('display_errors',1);
ini_set('display_startup_errors' ,1);
error_reporting(E_ALL);

?>

<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!DOCTYPE html>
<html>
<head>
	<title>Edit the ingredients</title>
  <!-- Bootstrap Core CSS -->
  <link href="__lib/bootstrap.css" rel="stylesheet">
  <!-- Bootstrap Core JS -->
  <script src="__lib/bootstrap.js"></script>
  <!-- Custom CSS -->
  <link href="__lib/main.css" rel="stylesheet">
  <style type="text/css">
    #preview,
    .img,
    img {
      max-width:100%;
      max-height:100%;
    }

    #preview {
      border: 0px solid #000;
      max-width:100%;
      max-height:100%;
    }
    </style>

  <!-- Java Script  -->
  <!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

  <!-- autocomplete -->
  <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/themes/base/minified/jquery-ui.min.css" type="text/css" />
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
  <script type="text/javascript" src="http://code.jquery.com/ui/1.10.1/jquery-ui.min.js"></script>
  <!-- <script type="text/javascript" src='/JavaScriptSpellCheck/include.js' ></script> -->

  <!--Because I get the Jquery library online, so this page need Internet-->
  <!-- Commented out by Waleed, for autocomplete to work -->

  <script type="text/javascript">
  	function cloneRow(){

      var tableID = "ingredientsTable";
      var table = document.getElementById(tableID);
  		var rowCount = table.rows.length;
  		if(rowCount <= 20){
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
  		}else{
  			alert("As a recipe you must have more than 1 ingredient!");
  		}
  	}

  	function preview(file,$default_url) {
      var prevDiv = document.getElementById('preview');
      if (file.files && file.files[0]) {
        var reader = new FileReader();
        reader.onload = function(evt) {

          prevDiv.innerHTML = '<img src="' + evt.target.result + '" />';
        }
        reader.readAsDataURL(file.files[0]);
      } else {

        prevDiv.innerHTML = '<div class="img" src="" style="filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src=\'' + file.value + '\'" ></div>';
      }
    }

  </script>

  <!-- autocomplete script -->
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
  <!-- end autocomplete -->

</head>
<body class="body">

  <!-- Navigation Bar -->
  <?php include('includes/navigation.php'); ?>

  <!-- Page Body Start -->

<div class="wrapper">
  <div class="page container">
	<div class="container container-fluid">
    <!--<div class="row">



	<form method = "get" enctype="multipart/form-data">
		<label>Please input the recipe id to test:</label>
		<input type="number" name="recipe_id">
		<input type="submit" name="recipe_id_button" value="search">
	</form>
</div>-->


<?php

  $message =  array('text' => '', 'has' => false);
  //check data integrity
  $dataIntegrity = TRUE;
  if(isset($_POST['change_recipe'])){
    foreach ($_POST['ingredient_name'] as $key => $value) {
      $ndbno = FoodApi::getIngredientNdbno($_POST['ingredient_name'][$key]);
      if(!$ndbno){
        $dataIntegrity = FALSE;
        $message = array('text' => 'No match found for ingredient: ' . $_POST['ingredient_name'][$key] ,  'has' => true);
      }
    }
  }
  // echo $dataIntegrity;

  if (isset($_POST['change_recipe']) && $dataIntegrity == TRUE) {

    $recipe_id_glo = $recipe_id;
    // $recipe_id_glo = $_GET['recipe_id'];
    $FileName = $_FILES['image']['name'];
    $ImageFile = $_FILES['image']['tmp_name'];
    $FileType = $_FILES['image']['type'];


    $FileType = str_replace("image/"," ",$FileType);
    $FileName = str_replace($FileName,$recipe_id_glo.".".$FileType , $FileName);
    $deleteFile = @unlink ($FileName);


    move_uploaded_file($ImageFile, "Img/recipes/$FileName");

    if ($FileName == NULL) {
      $sql_Change_Recipe = "UPDATE `recipes`
                SET `name`='$_POST[recipe_name]',`serving`='$_POST[serving]',`description`='$_POST[description]',`calories`='$_POST[calories]',`tag`='$_POST[tag]',`meal`='$_POST[meal]'
                WHERE id = $recipe_id_glo;";

    }else {
      $sql_Change_Recipe = "UPDATE `recipes`
                SET `name`='$_POST[recipe_name]',`serving`='$_POST[serving]',`description`='$_POST[description]',`calories`='$_POST[calories]',`tag`='$_POST[tag]',`meal`='$_POST[meal]',`image_url`='/cheza/code/Img/recipes/$FileName'
                WHERE id = $recipe_id_glo;";
    }



    // $recipe_id_glo=$_GET['recipe_id'];

    $recipe_id_glo = $recipe_id;

    if ($conn->query($sql_Change_Recipe) == TRUE) {
      // $recipe_id_glo = $_GET['recipe_id'];
      $recipe_id_glo = $recipe_id;
      $sql_Change_Ingredients = "DELETE FROM `ingredients` WHERE recipe_id= $recipe_id_glo;";

      $conn -> query($sql_Change_Ingredients);


      foreach ($_POST['ingredient_name'] as $key => $value) {

        $ndbno = FoodApi::getIngredientNdbno($value);

        $temp_ingredients_number = $_POST['ingredient_number'][$key];
        $sql_insert = "INSERT INTO ingredients(name, amount, unit, recipe_id, ndbno, amount_gram)
                VALUES ('{$value}',".  $_POST['ingredient_number'][$key] .", " .  $_POST['ingredient_unit'][$key] ." ,$recipe_id_glo, $ndbno, ".  $_POST['amountInGram'][$key] .")";
        echo $conn->error;
        if ($conn->query($sql_insert) == TRUE) {

        } else {
          echo "OPPS,Something wrong happenned!". $conn->error;
          echo "</br>";
        }
      }

      echo "<div class=\"alert alert-success\" role=\"alert\"> Update recipes in database successfully</div>";

    } else {
      echo "OPPS,Something wrong happenned!". $conn->error;
      echo "</br>";
    }


    //$sql_Change_Ingredients = "DELETE FROM 'ingredients' WHERE  "<p><img src= \"".changeUrlFormat($row['image_url'])."\" alt=\" ".$row['name']." \"></p>"

  } else{
    if($message['has']){
      echo "<div class=\"alert alert-warning\" role=\"alert\">" . $message['text'] . "</div>";
    }
  }
?>

<form method = "post" enctype="multipart/form-data">
  <div class="row">
    <div class="col-8">

		<?php
			//$recipe_id_glo ;
		?>

<?php if ($recipe_id != NULL): ?>



		<?php


			// if (isset($_GET['recipe_id_button'])) {
				// $recipe_id_glo = $_GET['recipe_id'];
        $recipe_id_glo = $recipe_id;
				$sql_test = "SELECT * FROM recipes WHERE id  = $recipe_id_glo;";
				$result = $conn -> query($sql_test);
				$sql_test_2 = "SELECT *,1 AS status FROM ingredients WHERE recipe_id = $recipe_id_glo;";
				$result2 = $conn -> query($sql_test_2);
				// echo "The recipeid ID ". $recipe_id_glo;

				while($row = $result->fetch_assoc()){
					echo "
          <div class=\"form-group\">
    			<label for=\"recipe_name\">Recipe Name: </label>
			 	<input class=\"form-control\" type=\"text\" name=\"recipe_name\" value = \"". $row['name']. "\"
					  pattern=\".{1,50}\" required title=\"1-50 characters\">
          </div>
          <div class=\"form-group\">
				 <label for=\"serving\">Serving: </label>
				 <input class=\"form-control\" type=\"number\" name=\"serving\" value=\"". $row['serving']. "\"
					  pattern=\".{1,50}\" required title=\"1-50 people\">
          </div>
          <div class=\"form-group\">
				  <label for=\"calories\">Calories: </label>
				  <input class=\"form-control\" type=\"text\" name=\"calories\" value=\"". $row['calories']. "\"
				  pattern=\".{0,10000}\" required title=\"0-10000\">
          </div>
          <div class=\"form-group\">
				  <label for=\"tag\">Tag: </label>
				  <input class=\"form-control\" type=\"text\" name=\"tag\" value=\"". $row['tag']. "\"
				  pattern=\".{1,50}\" required title=\"1-50 characters\">
          </div>
          <div class=\"form-group\">
				  <label for=\"meal\">Meal: </label>
				  <input class=\"form-control\" type=\"text\" name=\"meal\" value=\"". $row['meal']. "\"
				  pattern=\".{1,50}\" required title=\"1-50 characters\">
          </div>
          <div class=\"form-group\">
				  <label for=\"Description\">Description:</label>
				  <textarea  rows=\"6\" class=\"form-control\" name=\"description\" >". $row['description']. "</textarea>
          </div>";
          echo "</div>"; //for col
          echo "<div class=\"col\">";

  					echo "<img src=\"".changeUrlFormat($row['image_url'])."\">";
            echo "<div><input class=\"form-control\" type=\"file\" name=\"image\" onchange=\"preview(this)\" value=\"".changeUrlFormat($row['image_url'])."\"/></div>";
            echo "<p>image preview</p>";
            echo "<div id=\"preview\"></div>";

          echo "</div>";
				}


					?>

        </div>
        <!-- closing tag for row -->
        <div class="row">
					<p>
						<input class="btn btn-primary btn-lg disabled" type = "button" value = "Add ingredients" onclick="cloneRow();">
					</p>
						<table class="table table-striped" id="ingredientsTable" class="form" border="1">
	<tbody>
    <col width="43%">
    <col width="20%">
    <col width="20%">
    <col width="17%">
    <tr>
      <th>Name</th>
      <th>Amount</th>
      <th>Unit</th>
      <th>Amount In Gram</th>
      <th>Action</th>
    </tr>
          <?php
				  for ($i=0;$row = $result2 -> fetch_assoc();$i++){

				  	$n =$i;
					?>
								<tr id = "ingredient_<?php echo $i;?>">
								<td>
									<!-- <label>Name: </label> -->
									<input class="form-control auto" type="text" name="ingredient_name[]" value="<?php echo $row['name'];?>" pattern=\".{1,50}\" required title=\"1-50 characters\">
			          </td>
						   <td>
            			<!-- <label for="number_0">Number: </label> -->
            			<input class="form-control" type="number" name="ingredient_number[]" value="<?php echo $row['amount'];?>" pattern=\".{0,}\" required title=\"the number should more than 0\">
      			   </td>
      			   <td>
            			<!-- <label for="unit_0">Unit: </label> -->
            			<select class="form-control" id="unit_0" name="ingredient_unit[]">
                        	<?php
                        		// $allUnits = getUnits();
                        		// print_r($allUnits);
                        	foreach (getUnits() as $key => $value) {
                        			if($key==$row['unit']){
                        			echo "<option selected=\"".checkUnits($key,$row['unit'])."\" value=\"" . $key ."\">" .  $value . "</option>";
                        		}else{
                        			echo "<option  value=\"" . $key ."\">" .  $value . "</option>";
                        		}

                        	 } ?>
                  		</select>
          			</td>
                <td>
             			<!-- <label for="number_0">Number: </label> -->
             			<input class="form-control" type="number" name="amountInGram[]" value="<?php echo $row['amount_gram'];?>" pattern=\".{0,}\" required title=\"the amount in gram should more than 0\">
       			   </td>
          			<td>
        				    <input class="btn btn-secondary" type="button" value="Delete ingredients" onclick="deleteRow('ingredientsTable',this);"/>
          			</td>


			<?php 	} ?>

          </tbody>
          </table>
        <input class="btn btn-primary" type="submit" name="change_recipe" value="Save Changes">

      <?php

			//}
		?>
    <?php else: ?>

        <div class="card">
          <div class="card-body text-center">
            <h1 class="text-muted">Error</h1>
            <p>You are in the wrong place</p>
          </div>
        </div>
      <?php endif; ?>
    </div>
</div>

  	</div>
  	</form>
  </div>
  </div>
</body>
</html>
