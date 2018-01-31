<?php
ini_set('display_errors',1);
ini_set('display_startup_errors' ,1);
error_reporting(E_ALL);
session_start();
include 'helper_php/rating.php';
include 'helper_php/checkFav.php';
include 'helper_php/recentView.php';
require_once('helper_php/recipe.php');
require_once('helper_php/hitsCounter.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/__db/RecipesRepository.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/__db/UsersRepository.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/helper_php/CommentRepository.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/helper_php/Comment.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/login/loginCheck.php');
$recipe = new Recipe(-1);
if(isset($_GET['id'])){
  $id = $_GET['id'];
  $recipe = new Recipe($id);
}

$recipe->loadRecipe();

if($recipe->id != -1){
  HitsCounter($recipe->id);
    if(LoginCheck::isLoggedIn()){
        recentView($recipe->id);
    }
}

$comments = CommentRepository::getCommentsForRecipe($recipe->id);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>FIT MANIA</title>
    <!-- Bootstrap Core CSS -->
    <link href="__lib/bootstrap.css" rel="stylesheet">
    <!-- custom css -->
    <link type="text/css" href="__lib/recipe.css" rel="stylesheet">
    <link href="__lib/main.css" rel="stylesheet">
    <!-- Font Awesome Css -->
    <link rel="stylesheet" href="__fontAwesome/font-awesome/css/font-awesome.min.css">
    <link href="__lib/toastr.min.css" rel="stylesheet" type="text/css" />
</head>
<body class="body">

  <!-- Navigation Bar -->
  <?php include('includes/navigation.php'); ?>

  <!-- Page Body Start -->

<div class="wrapper">
<div class = "container conrainer-fluid page">
  <div class="row">
    <h2 class="main_title"><?php echo $recipe->name; ?></h2>
  </div>
  <div class = "row">
    <div class = "col-md-7 col-lg-6">
      <table class="table" style="background-color:#fff;">
        <tr style="background-color:#fff;">
          <th>Serving</th>
          <th>Calories / Serving</th>
          <th>Calculated Calories / Serving</th>
          <th>Meal</th>
        </tr>
        <tr>
          <td><?php echo $recipe->serving; ?></td>
          <td><?php echo $recipe->calories; ?></td>
          <td><?php echo number_format((float)$recipe->calculatedCalories/$recipe->serving, 2, '.', ''); ?>  Kcal</td>
          <td><?php echo $recipe->meal; ?></td>
        </tr>
      </table>
      <?php
        $tagsArray =explode(",",$recipe->tag);

        foreach($tagsArray as $tag){
              echo "<span class=\"btn btn-light btn-sm m-2\">" . $tag . "</span>";
        }
        echo "</br>";
        echo "<h4 class=\"m-2\">Ingredients</h4>";
        echo "<ul>";;
        foreach($recipe->ingredients as $ing){
          if($ing->amount == 0){
          echo "<li>" . $ing->name . " as per taste" . "</li>" ;
          }else{
            echo "<li>" . $ing->name . " " . $ing->amount . " " . $ing->unit .   " , Energy:"  . number_format((float)$ing->energy, 2, '.', '') . "</li>";
          }
        }
        echo "</ul>";
       ?>

    </div>
    <div class="col-md-5 col-lg-6">
      <img src="<?php echo $recipe->image_url ?>" alt="<?php echo $recipe->name?>" style="width:100%">
      <!--Like/Dislike button start -->
      <div class="container container-fluid">
        <div class="row">
<!--          <div class="col-md-7 col-sm-7"></div>-->
      <div class="col-md-5 col-sm-5 rating">
        <?php
        $rating = loadRating($id);
        $cho =loadChoice($id);
        //dictionary
        //cho[0] = 1 => you are logged in
        //cho[0] = 0 => you are not logged in
        //cho[1] = 0 => you chose dislike
        //cho[1] = 1 => you chose like
        //cho[1] = 2 => you  didn't choice
        if($cho[0] == 1){
          if($cho[1] == 1){
            $addLike = " active";
            $addDislike = "";
          }
          elseif($cho[1] == 0){
            $addLike = "";
            $addDislike = " active";
          }elseif($cho[1] == 2){
            $addLike = "";
            $addDislike = "";
          }
        }else{
          $addLike = "";
          $addDislike = "";
        }
        if($rating[0]==0&&$rating[1]==0){
          $likeRate = 50;
          $dislikeRate = 50;
        }
        elseif ($rating[0]==0) {
          $likeRate = 0;
          $dislikeRate = 100;
        }
        else{
          $likeRate = $rating[0]/($rating[0]+$rating[1])*100;
          $dislikeRate = 100-$likeRate;
        }
        $info = "fa fa-heart-o";
        $info = checkFav($id);
        ?>
        <table style="white-space:nowrap;word-break:keep-all;"   nowrap="nowrap" width="160px;"  data-placement="auto" data-delay="{ "show": 0, "hide": 1000 }" title="click again can cancel rating">
          <tr>
            <td>
              <form method="post" action="helper_php/fav.php">
                <input type="hidden" name="recipeId" value="<?php echo $id?>">
                <button class="btn btn-danger">
                  <span class="<?php echo $info?>" aria-hidden="true" style="color:white;"></span><br>
                  <!-- <span class="badge" >999999</span> -->
                </button>
              </form>
            </td>
            <td>
              <form method="post" action="helper_php/like.php">
                <!-- <input type="submit" id="like" style="width: 80px; height: 40px; background:url('img/like.jpg'); background-size: 100% 100%;" onclick="javascript:window.location.href='helper_php/like.php?sub=yes&recipeId=<?php //echo $id?>'"/> -->
                <input type="hidden" name="recipeId" value="<?php echo $id?>">
                <!-- <input type="submit" name="like"  style="width: 80px; height: 40px; background:url('img/like.jpg'); background-size: 100% 100%; " value="" class="btn btn btn-primary <?php //echo $addLike;?>"> -->
                <button class="btn btn-info <?php echo $addLike;?>" style="width: 80px;">
                  <span class="fa fa-thumbs-up" aria-hidden="true"></span> <span class="badge"><?php echo $rating[0];?></span>
                </button>
              </form>
            </td>
            <td><!-- <input type="button" id="dislike" style="width: 80px; height: 40px; background:url('img/dislike.jpg'); background-size: 100% 100%;" onclick="javascript:window.location.href='helper_php/dislike.php?sub=yes&recipeId=<?php //echo $id?>'"/> -->
              <form method="post" action="helper_php/dislike.php">
                <input type="hidden" name="recipeId" value="<?php echo $id?>">
                <!-- <input type="submit" name="dislike" style="width: 80px; height: 40px; background:url('img/dislike.jpg'); background-size: 100% 100%; " value="" class="btn btn btn-primary <?php //echo $addDislike?>"> -->
                <button class="btn btn-info <?php echo $addDislike;?>" style="width: 80px;">
                  <span class="fa fa-thumbs-down" aria-hidden="true"></span> <span class="badge"><?php echo $rating[1];?></span>
                </button>
              </form>
            </td>

          </tr>
          <tr>
            <td></td>
            <!-- <td><?php //echo $rating[0];?></td> -->
            <td colspan="2"><center><img src="img/green.png" width="<?php echo $likeRate;?>%" height="10px"><img src="img/red.png" width="<?php echo $dislikeRate;?>%" height="10px"></center></td>
            <!-- <td><?php //echo $rating[1];?></td> -->
          </tr>
        </table>
      </div>
    </div>
  </div>
      <!--Like/Dislike button end -->
    </div>
  </div>
  <div class = "row">
    <h4>Description (Steps):</h4>
  </div>
  <div class = "row">
    <p><?php echo $recipe->description?></p>
  </div>
  <div class="row">
      <!-- Recommendation goes here -->
      <h4>Similar Recipes</h4>
  </div>
  <div class="row mt-2 mb-3">
      <?php
        $similarRecipes = RecipesRepository::getSimilarRecipes($recipe->tag, $recipe->id);
        foreach ($similarRecipes as $sr) {
            $srName = $sr->getName();
            $srId = $sr->getId();
            $srImage = $sr->getImageUrl();
            echo
            "<div class='col-md-3'>" .
            "    <div class='col-md-12 smr p-0'>" .
            "        <a class='smr' href='viewRecipe.php?id=$srId'>" .
            "            <img class='smr-img' src='$srImage'/>" .
            "            <div class='smr-overlay'></div>" .
            "            <div class='smr-title'>" .
            "                <span>$srName</span>" .
            "            </div>".
            "        </a>" .
            "    </div>" .
            "</div>";
        }
      ?>
  </div>
  <br /><hr />
  <h3>Comments</h3>
  <div id="comments-container" class="comments-container">
      <?php if (count($comments) > 0) : ?>
      <?php foreach($comments as $comment): ?>
      <div class="comment-container clearfix">
          <div class="author-info clearfix">
              <div class="author-image float-left"><img src="<?= $comment->getUserImage() ?>" /></div>
              <div class="float-left">
                  <div>
                    <span class="info-title">Written by : </span><span class="author-name"><?= $comment->getUserName() ?></span>
                  </div>
                  <div>
                      <span class="info-title">Date : </span><span class="author-date"><?= $comment->getDate() ?></span>
                  </div>
              </div>
          </div>
          <div class="comment-text">
              <?= $comment->getText() ?>
          </div>
      </div>
      <?php endforeach; ?>
      <?php endif; ?>
      <?php if (count($comments) == 0) : ?>
          <div id="no_comments" class="card">
              <div class="card-body text-center">
                  <h5 class="text-muted">There are no currently comments for this recipe</h5>
                  <p>Be the first to comment</p>
              </div>
          </div>
      <?php endif; ?>
  </div>
  <br />
  <h4>Write your comment</h4>
  <div class="comment-input-container form-group clearfix">
      <div id="character_count_wrapper" class="float-right"><span id="character_count">0</span>/<span>250</span></div>
      <textarea id="comment_text" class="form-control" rows="5" placeholder="your comment here" maxlength="250" oninput="onInput()"></textarea>
      <br />
      <button id="btn_submit_comment" class="btn btn-primary float-right">Submit</button>
  </div>
</div>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="__lib/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
<script src="__lib/toastr.min.js" type="text/javascript"></script>
<script type="text/javascript">
    var commentPlaceholder = $('#comment_text');
    var characterCountWrapper = $('#character_count_wrapper');
    var characterCount = $('#character_count');
    var hasExceededMaxLength = false;

    $(document).ready(function () {

        $('#btn_submit_comment').click(function () {
            var commentText = commentPlaceholder.val();
            if ($.trim(commentText).length == 0)
                alert("Your comment cannot be empty");
            else if (hasExceededMaxLength)
                alert("You have exceeded the maximum length of characters for the comments");
            else {
                var xhr = $.ajax({
                    url: 'helper_php/commentRestController.php?op=add',
                    type: 'POST',
                    data: JSON.stringify({
                        'text': commentText,
                        'recipe_id': getParameterByName('id')
                    })
                })
                .done(function (response) {
                    switch (xhr.status) {
                        case 200 :
                            var commentsContainer = $('#comments-container');
                            commentsContainer.prepend('<div class="comment-container clearfix">\n' +
                                '          <div class="author-info clearfix">\n' +
                                '              <div class="author-image float-left"><img src="<?= isset($_SESSION[Session_Image_Url]) ? $_SESSION[Session_Image_Url] : '' ?>" /></div>\n' +
                                '              <div class="float-left">\n' +
                                '                  <div>\n' +
                                '                    <span class="info-title">Written by : </span><span class="author-name"><?= isset($_SESSION[Session_Full_Name]) ? $_SESSION[Session_Full_Name] : '' ?></span>\n' +
                                '                  </div>\n' +
                                '                  <div>\n' +
                                '                      <span class="info-title">Date : </span><span class="author-date">' + new Date().acceptableFormat() + '</span>\n' +
                                '                  </div>\n' +
                                '              </div>\n' +
                                '          </div>\n' +
                                '          <div class="comment-text">\n' +
                                '              ' + commentText + '\n' +
                                '          </div>\n' +
                                '      </div>');
                            commentPlaceholder.val('');
                            $('#no_comments').remove();
                            window.scrollTo(0, commentsContainer.offset().top);
                            toastr.options.progressBar = true;
                            toastr.options.positionClass = "toast-top-center";
                            toastr.success("Your comment was successfully added");
                            break;
                        case 500:
                            console.log(xhr);
                            toastr.options.progressBar = true;
                            toastr.options.positionClass = "toast-top-center";
                            toastr.error("There was a problem in the server");
                            break;
                    }
                })
                .fail(function (xhr) {
                    console.log(xhr.status);
                    console.log(xhr);
                    switch (xhr.status) {
                        case 401:
                            var returnUrl = encodeURIComponent(window.location.href);
                            window.location.replace('/cheza/code/Login/login.php?location=' + returnUrl);
                            break;
                    }
                });
            }
        });
    });

    function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
            results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    }

    Date.prototype.acceptableFormat = function() {
        var yyyy = this.getFullYear().toString();
        var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
        var dd  = this.getDate().toString();
        return yyyy + "-" + (mm[1]?mm:"0"+mm[0]) + "-" + (dd[1]?dd:"0"+dd[0]); // padding
    };

    function onInput() {
        var maxlength = $(this).attr("maxlength");
        var currentLength = commentPlaceholder.val().length;
        characterCount.html(currentLength.toString());
        if ( currentLength >= maxlength ) {
            changeColor(characterCountWrapper, 'red');
            hasExceededMaxLength = true;
        }else {
            changeColor(characterCountWrapper, 'black');
            hasExceededMaxLength = false;
        }
    }

    function changeColor(el, color) {
        el.css('color', color);
    }


</script>

</body>
</html>
