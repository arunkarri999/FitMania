<?php
ini_set('display_errors',1);
ini_set('display_startup_errors' ,1);
error_reporting(E_ALL);
require_once('helper_php/getRecipesByPage.php');
require_once('helper_php/RecipeSearch.php');
include 'helper_php/rating.php';
include 'helper_php/tags.php';
$isSearch = FALSE;
$searchTags = null;
$pageNumber = 1;
$recipes = null;
$keyword = null;
$caloriesFrom = null;
$caloriesTo = null;
$result = null;
if(isset($_GET['pageNumber']) && $_GET['pageNumber'] != null )
{
  $pageNumber = $_GET['pageNumber'];
}

if(isset($_GET['keyword'])){
  $isSearch = TRUE;
  if($_GET['keyword'] != null && $_GET['keyword'] != "") {
    $keyword = $_GET['keyword'];
  }else{
    $keyword = null;
  }
  if(isset($_GET['tags']) && count($_GET['tags']) >= 1)
  {
    $searchTags = implode(', ', $_GET['tags']);
  }
  else
  {
    $searchTags = null;
  }
  if(isset($_GET['calories_from']) && $_GET['calories_from'] != null )
  {
    $caloriesFrom = $_GET['calories_from'];
  }
  else
  {
    $caloriesFrom = null;
  }
  if(isset($_GET['calories_to']) && $_GET['calories_to'] != null )
  {
    $caloriesTo = $_GET['calories_to'];
  }
  else
  {
    $caloriesTo = null;
  }

  $result = RecipeSearch::search($keyword, $pageNumber ,$searchTags,$caloriesFrom, $caloriesTo);


} elseif(isset($_GET['t']) && $_GET['t'] == 'popular'){
  $result = getMostVisited($pageNumber);
} else{
  $result  = getCurrent($pageNumber);
}

$totalResultsNumber = $result['num'];
$recipes = $result['recipes'];
$totalNumOfPages = ceil($totalResultsNumber / 6);
 ?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <!-- Bootstrap Core CSS -->
    <link href="__lib/bootstrap.css" rel="stylesheet">
    <!-- Custom  CSS -->
    <link href="__lib/singleRecipe.css" rel="stylesheet">
    <link href="__lib/main.css" rel="stylesheet">
    <!-- Bootstrap Core JS -->
    <script src="__lib/bootstrap.js"></script>
    <!-- Font Awesome Css -->
    <link rel="stylesheet" href="__fontAwesome/font-awesome/css/font-awesome.min.css">
</head>
<body class="body">
  <!-- Navigation Bar -->

  <?php include('includes/navigation.php'); ?>

  <!--The framework of index page-->
<div class="wrapper">
 <div class="container conrainer-fluid white-bg page">
 <form method="get" enctype="multipart/form-data">

  <div class="container">
    <div class="row justify-content-center"><p> </p></div>
    <div class="row justify-content-center">
      <div class="col-10">
        <input name="keyword" type="text" class="form-control" id="exampleInputKeyword" aria-describedby="nameHelp" placeholder="Enter keywords" value="<?php echo $keyword; ?>">
      </div>
    	<div class="col-2">
          <input style="height:40px" type="image" name="search_button" value="search" src="Img/search_pan.png">
      </div>
    </div>
      <button type="button" class="btn btn-secondary btn-sm" data-toggle="collapse" data-target="#checklist" aria-controls="checklist">Advanced Search</button>
      <span class="font-italic">
      <?php
        if($isSearch){
          $message = "Search results for: $keyword";
          if($caloriesFrom != null){
            $message .= ", Calories: $caloriesFrom - ";
            if($caloriesTo != null){
              $message .= $caloriesTo;
            }
          }elseif($caloriesTo != null){
            $message .= ", Calories: 0 - {$caloriesTo}";
          }
            if($searchTags != null){
              $message .= ", Tags: {$searchTags}";
            }
            echo $message;
        }
       ?>
       <span>
 </div>


 <div class="collapse" id="checklist">
  <dl class="row">
    <dt class="col-sm-1"></dt>
     <dt class="col-sm-3">Calories: </dt>
        <dd class="col-sm-8">
          <label for="calories_from">From</label>
          <input type="number" name="calories_from" class="form-control col-3" value="<?php echo $caloriesFrom; ?>">

          <label for="calories_to">To</label>
          <input type="number" name="calories_to" class="form-control col-3" value="<?php echo $caloriesTo; ?>">

	      </dd>
 		<dt class="col-sm-1"></dt>
    <dt class="col-sm-3">Tags:</dt>
        <dd class="col-sm-8">
      		<div class="form-check">
            <?php $topTags = getTopTags(); ?>
            <?php if (count($topTags) > 0):?>
              <?php foreach ($topTags as $oneTag): ?>
                <label class="form-check-label">
            			<input class="form-check-input" type="checkbox" type="checkbox" name="tags[]" value="<?php echo $oneTag; ?>"><?php echo $oneTag; ?></label>
              <?php endforeach; ?>
            <?php endif;?>

        		<!-- <label class="form-check-label">
        			<input class="form-check-input" type="checkbox" type="checkbox" name="tags[]" value="Beef">Beef</label>
        		<label class="form-check-label">
              <input class="form-check-input" type="checkbox" name="tags[]" value="Chicken">Chicken</label>
        		<label class="form-check-label">
              <input class="form-check-input" type="checkbox" name="tags[]" value="Egg">Egg</label>
        		<label class="form-check-label">
              <input class="form-check-input" type="checkbox" name="tags[]" value="Vegetable">Vegetable<label> -->
          </div>
	      </dd>

      <dt class="col-sm-1"></dt>
      <!-- <dt class="col-sm-3"> Meal:</dt>
      <dd class="col-sm-8">
    		<div class="form-check">
            <label class="form-check-label">
  		        <input class="form-check-input" type="checkbox" name="meal_breakfast">Breakfast</label>
    		    <label class="form-check-label">
              <input class="form-check-input" type="checkbox" name="meal_lunch">Lunch</label>
    		    <label class="form-check-label">
              <input class="form-check-label" type="checkbox" name="meal_dinner">Dinner</label>
        </div>
       </dd>
	     <dt class="col-sm-1"></dt>
        <dt class="col-sm-3">Search by Others:</dt>
      		<dd class="col-sm-8">
    		    <div class="form-check">
  		        <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="tag">According to tag<lable>
            </div>
  	     </dd> -->
     </dl>
   </div>

 <p></p>

 <div class="contianer m-3">
   <div class="row">
     <div class="col">
	   <a class="btn btn-secondary btn-sm" href="index.php?t=recent">Recently Added<a>

	   <a class="btn btn-secondary btn-sm"href="index.php?t=popular">Most Viewed (Popular)<a>

      <?php if (isset($_GET['t']) && $isSearch === FALSE): ?>
        <span class="font-italic">  <?php echo "Showing: ". $_GET['t']; ?></span>
      <?php endif; ?>
     </div>

   </div>
 </div>

<div class="container">
<div class="row">

<?php if($recipes != null) :  ?>
<?php foreach ($recipes as $recipe) : ?>
  <div class="col-12 col-md-6 col-lg-4 mb-4">
    <a href="viewRecipe.php?id=<?php echo $recipe->id?>" name="recipe" class="s_r singlerecipe">
          <div class="r_t">
        <!-- recipe image -->
              <img class="r_image" src="<?php echo $recipe->image_url; ?>" ><br />
              <!-- recipe title -->
              <span class="r_n"><?php echo $recipe->name; ?></span><br />
              <!--recpe tag -->
              <?php
              $tagsArray =explode(",",$recipe->tag);

              foreach($tagsArray as $tag){
                    echo "<span class=\"tag\">" . $tag . " </span>";
              }
               ?>
        <br />
          <!-- reipe calories -->
        <span class="r_c">
              Calories: <?php echo $recipe->calories ?>
        </span>
        <br />
         <!-- recipe author -->
          <span class="r_a">
          <?php
          if (!is_null($recipe->user_name))
          {
            echo "Author : $recipe->user_name";
          }
          else echo "Author : Unknown";
          ?>
          </span>
        <?php
          $rating = loadRating($recipe->id);
        ?>
        <div class="r_p">
          <span style="width:40px; height: 20px;">
                  <span class="fa fa-thumbs-up" aria-hidden="true"></span> <span class="badge"><?php echo $rating[0];?></span>
          </span>
            <span style="width:40px; height: 20px;">
                  <span class="fa fa-thumbs-down" aria-hidden="true"></span> <span class="badge"><?php echo $rating[1];?></span>
          </span>
        </div>
      </div>
    </a>
  </div>
<?php endforeach; ?>
<?php endif; ?>



      </div>

    	<div class="row justify-content-center">
    	<?php
    	 // pages at the bottom
    	  for($i=1 ; $i <= $totalNumOfPages ; $i++){
    		echo "<button name=\"pageNumber\" value=\"$i\" class=\"btn btn-light btn-sm m-2\">" . $i . "</button>";
    	  }
    	  ?>
    	</div>

      <!-- Show no results card if nothing returned from the search function -->
      <?php if ($keyword != null && $totalResultsNumber == 0): ?>
        <div class="card">
          <div class="card-body text-center">
            <h1 class="text-muted">No results found</h1>
            <p>Try other keyword or clear the advanced search to get more results</p>
          </div>
        </div>
      <?php endif; ?>

      <!-- Type -->
      <?php if (isset($_GET['t']) && $_GET['t'] == 'popular'): ?>
          <input type="hidden" name="t" value="popular">
      <?php else: ?>
          <input type="hidden" name="t" value="recent">
      <?php endif; ?>

    </div>
  </form>
</div>
</div>
</body>
</html>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
