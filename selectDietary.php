<?php
include_once("login/loginCheck.php");
include_once("__db/UsersRepository.php");
include_once("helper_php/dietaryGoal.php");
// require_once($_SERVER['DOCUMENT_ROOT'] . '/cheza/code/__db/RecipesRepository.php');
if(!LoginCheck::isLoggedIn()){
  header('Location: login/login.php');
  exit;
}

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
     <!-- Bootstrap Core JS -->
     <script src="__lib/bootstrap.js"></script>
     <!-- custom css -->
     <link href="__lib/dietary.css" rel="stylesheet">
     <link href="__lib/main.css" rel="stylesheet">
     <!-- Third Party CSS -->
     <link href="https://fonts.googleapis.com/css?family=Allan" rel="stylesheet">
 </head>
 <body class="body">

   <!-- Navigation Bar -->
   <?php include('includes/navigation.php'); ?>

   <!-- Page Body Start -->
<div class="wrapper">
   <div class = "container conrainer-fluid white-bg page">
     <h1 class="text-center">What is your Goal? . . .</h1>
     <hr>
     <div class = "row">
      <div class = "col-md-4 col-xs-12">
             <form  method="post" action="setDietary.php" class="text-right">
                    <input type="image" name="changeDietary"  src = "images/lose.png" class="selectimg"/>
                    <input type="hidden" name="newDietary" value="lose"/>
                    <!-- <input type="submit" name="assingTask" class="btn btn-link" value="Assign"/> -->
            </form>
      </div>

      <div class = "col-md-4 col-xs-12">
          <form  method="post" action="setDietary.php" class="text-right">
                 <input type="image" name="changeDietary" src = "images/keep.png" class="selectimg"/>
                 <input type="hidden" name="newDietary" value="keep"/>
                 <!-- <input type="submit" name="assingTask" class="btn btn-link" value="Assign"/> -->
         </form>
      </div>

      <div class = "col-md-4 col-xs-12">
          <form  method="post" action="setDietary.php" class="text-right">
              <input type="image" name="changeDietary"  src = "images/gain.png" class="selectimg"/>
              <input type="hidden" name="newDietary" value="gain"/>
              <!-- <input type="submit" name="assingTask" class="btn btn-link" value="Assign"/> -->
          </form>
      </div>
     </div>
     <h2 class="text-center">Weight!</h2>
   </div>
   </div>
 </body>
 </html>

 <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
 <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
