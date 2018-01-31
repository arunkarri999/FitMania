<?php
session_start();
if(isset($_POST['changeDietary_x'])){
  $_SESSION['dietary'] = $_POST['newDietary'];
  header('Location: ./setGoal.php');
}
else{
  header('Location: ./selectDietary.php');
}
// require_once("login/loginCheck.php");
// require_once("__db/UsersRepository.php");

// $SERVER = "localhost";
// $USERNAME = "fit";
// $PASSWORD = "fit";
// $DB = "fit_mania";
// $conn = mysqli_connect($SERVER, $USERNAME, $PASSWORD, $DB);

// var_dump($_POST);

// if(isset($_POST['changeDietary_x'])){
//     $dietary = $_POST['newDietary'];
//     $userId = -1;

//     if(LoginCheck::isLoggedIn()){
//       $user = UsersRepository::getUserByEmail($_SESSION["Email"]);
//       $userId = $user->getId();

//       $sql = "UPDATE users set dietary = '$dietary' WHERE id = $userId";
//       $result = $conn->query($sql);
//       if($result){
//         header('Location: ./setGoal.php');
//       }else{
//         echo "something went wrong " . $conn->error;
//       }
//     $conn->close();
//     } else {
//      header('Location: login.php?location=' .urlencode($_SERVER['REQUEST_URI']));
//      exit;
//     }
// }else{
//    header('Location: index.php' );
//    exit;
// }


?>
