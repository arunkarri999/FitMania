<?php

require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/__lib/constants.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/helper_php/RecipeSearch.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/helper_php/rating.php');
require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/login/loginCheck.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

if (!LoginCheck::isAdminLoggedIn()) {
    header("location: /cheza/code/Login/adminLogin.php");
    exit();
}

$recipes = RecipeSearch::getUnapprovedRecipes();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Recipe Approval</title>
    <!-- Custom  CSS -->
    <link href="__lib/singleRecipe.css" rel="stylesheet">
    <link href="__lib/main.css" rel="stylesheet">
    <!-- Bootstrap Core CSS -->
    <link href="__lib/bootstrap.css" rel="stylesheet">
    <!-- Bootstrap Core JS -->
    <script src="__lib/bootstrap.js"></script>
    <!-- Font Awesome Css -->
    <link rel="stylesheet" href="__fontAwesome/font-awesome/css/font-awesome.min.css">
    

    <style>
        table td {
            vertical-align: middle !important;
        }
    </style>
</head>
<body class="body">
   <!-- Navigation Bar -->

  <?php include('includes/navigation.php'); ?>
    <div class="container container-fluid white-bg wrapper" style="background-color: #fffefa">
        <?php
        if (is_null($recipes) || empty($recipes) || count($recipes) == 0)
            echo "<div class=\"card\">
                  <div class=\"card-body text-center\">
                      <h1 class=\"text-muted\">No results found</h1>
                      <p>There are no items in your favorite list</p>
                  </div>
              </div>";
        else {
            echo
                '<table class="table table-striped">' .
                '   <tr><th>image</th><th>name</th><th>link</th><th>approve</th></tr>'
            ;
            foreach ($recipes as $recipe) {
                echo
                "<tr>" .
                "   <td><img src='$recipe->image_url' width='150' height='150'/></td>" .
                "   <td><span>$recipe->name</span></td>" .
                "   <td><a href='viewRecipe.php?id=$recipe->id' class='btn btn-outline-info'>Go to Recipe</a></td>" .
                "   <td><span data-id='$recipe->id' class='btn btn-success btn-approve'>Approve</a></td>" .
                "</tr>"
                ;
            }
        }
        ?>
    </div>
    <script src="__lib/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.btn-approve').each(function (index) {
                var _self = $(this);
                _self.click(function () {
                    var id = _self.attr('data-id');
                    var xhr = $.post('/cheza/code/helper_php/approveRecipeRestController.php?id=' + id, function () {
                        alert("Recipe Approved Successfully");
                        window.location.reload(true);
                    })
                    .fail(function () {
                        alert("Recipe Approval Failed");
                        console.log(xhr);
                    });
                });
            });
        });
    </script>
</body>
</html>
