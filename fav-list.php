<?php
require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/__lib/constants.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/helper_php/RecipeSearch.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/helper_php/rating.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/login/loginCheck.php');

if(!LoginCheck::isLoggedIn()){
    $url = urlencode("/cheza/code/fav-list.php");
    header('location: /cheza/code/login/login.php?location=' . $url);
    exit;
}

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$pageNumber = isset($_GET['page']) ? $_GET['page'] : 1;
$recipesResult = RecipeSearch::getFavoriteRecipes($pageNumber);
$totalResultsNumber = $recipesResult['num'];
$totalNumOfPages = ($totalResultsNumber / RecipeSearch::NUM_OF_RECORDS_IN_PAGE) + (($totalResultsNumber % RecipeSearch::NUM_OF_RECORDS_IN_PAGE) > 0 ? 1 : 0);
$recipes = $recipesResult['recipes'];
?>

<!Doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>favorite list</title>

    <!-- Bootstrap Core CSS -->
    <link href="__lib/bootstrap.css" rel="stylesheet">
    <!-- Bootstrap Core JS -->
    <script src="__lib/bootstrap.js"></script>
    <!-- Font Awesome Css -->
    <link rel="stylesheet" href="__fontAwesome/font-awesome/css/font-awesome.min.css">
    <!--JavaScript Core css-->
<script type="text/javascript" src="__lib/jquery.js"></script>
    <!-- Custom  CSS -->
    <link href="__lib/favorite-list.css" rel="stylesheet">
    <link href="__lib/main.css" rel="stylesheet">
</head>

<body class="body">
  <!-- Navigation Bar -->
  <?php include('includes/navigation.php'); ?>

  <!-- Page Body Start -->
    <!--begin of page container-->
<div class="wrapper">
  <div class="container container-fluid white-bg page">
      <div class="container pt-3">
<h1>All Favorite Recipes</h1>
          <div class="row main-page">

              <?php
              if($recipes != null){
                  foreach ($recipes as $recipe) {
                      ?>
                      <div class="col-4 -sm -4 fav-list">
                          <div class="onefavorite">
                              <a href="viewRecipe.php?id=<?php echo $recipe->id?>" name="recipe" class="s_r">
                                      <!-- recipe image -->
                                      <img class="r_image" src="<?php echo $recipe->image_url; ?>" ></br>
                                      <div class="r_description">
                                      <!-- recipe title -->
                                      <span class="r_n"><?php echo $recipe->name; ?></span></br>
                                      <!--recpe tag -->
                                      <?php
                                      $tagsArray = explode(",",$recipe->tag);
                                      foreach($tagsArray as $tag){
                                          echo "<span class=\"tag\">" . $tag . " </span>";
                                      }
                                      ?>
                                      </br>
                                      <!-- reipe calories -->
                                      <span class="r_c">
                                        Calories: <?php echo $recipe->calories ?>
                                      </span>

                                      <!-- recipe author -->
                                      <span class="r_a">
                                          <?php
                                          if($recipe->user_id != -1)
                                          {
                                              echo $recipe->user_name;
                                          }
                                          ?>
                                      </span>
                                      <?php
                                      $rating = loadRating($recipe->id);
                                      ?>
                                     <!--
                                      <div class="r_p">
                                        <span style="width:40px; height: 20px;">
                  <span class="fa fa-thumbs-up" aria-hidden="true"></span> <span class="badge"><?php echo $rating[0];?></span>
          </span>
                                        <span style="width:40px; height: 20px;">
                  <span class="fa fa-thumbs-down" aria-hidden="true"></span> <span class="badge"><?php echo $rating[1];?></span>
          </span>
                                      </div>
-->
                                  </div>
                              </a>
                          </div>
                      </div>
                  <?php  }
              }
              ?>
          </div>

          <div class="row justify-content-center">
              <?php
              // pages at the bottom
              for($i=1 ; $i <= $totalNumOfPages ; $i++){
                  echo "<a class=\"btn btn-light btn-sm m-2\" href='fav-list.php?page=$i'>" . $i . "</a>";
              }
              ?>
          </div>

          <!-- Show no results card if nothing returned from the search function -->
          <?php if ($totalResultsNumber == 0): ?>
              <div class="card">
                  <div class="card-body text-center">
                      <h1 class="text-muted">No results found</h1>
                      <p>There are no items in your favorite list</p>
                  </div>
              </div>
          <?php endif; ?>
      </div>
  </div>
</div>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</body>
</html>
