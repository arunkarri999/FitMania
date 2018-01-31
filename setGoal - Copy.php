<?php
include_once("helper_php/dietaryGoal.php");
require_once("login/loginCheck.php");
if(!LoginCheck::isLoggedIn()){
  header('Location: login/login.php');
  exit;
}
?>
<script type="text/javascript">
  function checkForm(){
    var weight = document.getElementById('weight').innerHTML;
    var age = document.getElementById('age').innerHTML;
    var height = document.getElementById('height').innerHTML;
    var gender = document.getElementById('gender').innerHTML;
    var goal = document.getElementById('goal').innerHTML;
    // alert(weight.substr(weight.length-1));
    // alert(age.substr(age.length-1));
    // alert(height.substr(height.length-1));
    // alert(gender.substr(gender.length-1));
    // alert(goal.substr(goal.length-1));
    if(goal.substr(goal.length-1) == ">"){
      alert("please set goal in dietary");
      return false;
    }
    if(weight.substr(weight.length-1) == ">"){
      alert("please set weight in profile");
      return false;
    }
    if(age.substr(age.length-1) == ">"){
      alert("please set age in profile");
      return false;
    }
    if(height.substr(height.length-1) == ">"){
      alert("please set height in profile");
      return false;
    }
    if(gender.substr(gender.length-1) == ">"){
      alert("please set gender in profile");
      return false;
    }

    var begin = document.getElementById("begin").value;
    var end = document.getElementById("end").value;
    var diet = document.getElementById("goal").innerHTML;
    diet = diet.substr(diet.length-4);
    if(diet == "keep"){
      if(begin.length == 0||end.length == 0){
        alert("error00");
        return false;
      }
      if(begin>end){
        alert("error01");
        return false;
      }
      var habitLen = document.getElementsByName("exerciseType").length;
      var count=0;
      for(i=0;i<habitLen;i++){
        if(document.getElementsByName("exerciseType")[i].checked){
          count++;
        }
      }
      if(!count){
        alert("error02");
        return false;
      }
      return true;
    }
    var goalWeight = document.getElementById("goalWeight").value;
    if(goalWeight.length == 0){
      alert("error04");
      return false;
    }
    if(begin.length == 0||end.length == 0){
      alert("error00");
      return false;
    }
    if(begin>end){
      alert("error01");
      return false;
    }
    var habitLen = document.getElementsByName("exerciseType").length;
    var count=0;
    for(i=0;i<habitLen;i++){
      if(document.getElementsByName("exerciseType")[i].checked){
        count++;
      }
    }
    if(!count){
      alert("error02");
      return false;
    }
    return true;
  }
  function suggestPeriod(){
    var goalWeight = document.getElementById("goalWeight").value;
    if(goalWeight> 0){

      goalWeight = parseFloat(goalWeight);
      <?php $showInfo = checkInfo();?>
      var weight = <?php  echo floatval($showInfo['weight']);?>;
      //alert(weight);
      var goal = Math.abs(weight-goalWeight);
      var day = goal*7000/1000;
      //alert(day);
      document.getElementById("suggestion").innerHTML="please choose a period at least "+day+" days to reach your goal and keep a health lifestle!";
    }
    else{
      alert("Please input goal weight");
    }
  }

  function day(){
    //alert("go");
    var date1=document.getElementById('begin').value;
    var date2=document.getElementById('end').value;
    if(date1.length == 0){
      document.getElementById("d").innerHTML="Please choose begin date!";
      return 0;
    }
    if(date2.length == 0){
      document.getElementById("d").innerHTML="Please choose end date!";
      return 0;
    }
    date1=Date.parse(date1);
    date2=Date.parse(date2);
    if(date1>date2){
      document.getElementById("d").innerHTML="begin date must before end date!";
    }
    else{
      var day=1+(date2-date1)/1000/60/60/24;
      document.getElementById("d").innerHTML="You choose "+day+" days";
    }
  }
</script>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>setGoal</title>
    <!-- Bootstrap Core CSS -->
    <link href="__lib/bootstrap.css" rel="stylesheet">
    <link href="__lib/main.css" rel="stylesheet">
    <!-- Bootstrap Core JS -->
    <script src="__lib/bootstrap.js"></script>
    <style>
      .main-container{
        text-align: center;
        width: 60%;
        margin-left: auto;
        margin-right: auto;
        margin-top: 7%;
        margin-bottom: 7%;        
        padding-top: 0px;
        background: rgba(255,255,255,0.9);
      }
/*
      body{
        background-size: cover;
        background: url(images/bg.jpg);
        background-position-x: center;
      }
*/
      #goal{
        width: 100%;
        background: #2980b9;        
        font-family: Raleway, Arial, Helvetica, sans-serif;
        font-weight: 400;
        line-height: 1.31;
        letter-spacing: 1px;
        font-size: 32px;
        padding: 25px;
        color: rgb(51, 51, 51);
      }
      .sub-container{
        padding: 4% 6% 6% 6%;
      }
      p{
        text-align:left;
        color:#747474;
      }
      a{
        color:#2980b9;
      }
      .input-wrap{
        position: relative;
        min-height: 1px;
        padding-left: 15px;
        padding-right: 15px;
      }
      .label-input{
        font-size: 15px;
        font-family: Raleway,Arial,Helvetica,sans-serif;
        font-weight: 500;
        margin-bottom: 10px;
      }
      .date-input{
        height: 50px;
        padding-top: 0;
        padding-bottom: 0;
        border-color: #dddddd;
        color: #333333;
        background-color: #fbfbfb;
        margin-right: 0;
        box-sizing: border-box;
        border: 1px solid #d2d2d2;
        font-size: 13px;
        color: #747474;
        padding: 8px 15px;
        float: left;
        margin-right: 1%;
        width: 100%;
      }
      .combo-container{
        border: 1px solid #ddd9d8;
        margin: 16px;
        padding: 13px;
        background: #fbfbfb;
        letter-spacing: 1px;
        text-align: left;
      }
      #suggestion{
        padding-left:20px;
      }
      .submit-btn{
        padding: 13px 29px;
        line-height: 17px;
        font-size: 14px;
        background: #2980b9;
        text-transform: uppercase;
        color: #333333;
        border-width: 0px;
        border-style: solid;
        border-color: #333333;
        font: 100% Arial,Helvetica,sans-serif;
        vertical-align: middle;
        cursor:pointer;
      }
      .submit-btn:hover{        
        color: white;
        background:black;
      }
    </style>
</head>
<body class="body">
<?php $showInfo = checkInfo();?>
  <div class="main-container page">
    <p id="goal">Goal: <?php echo $_SESSION['dietary'];?></p>
    <div class="sub-container">
      <p id="weight">Weight(KG): <?php echo $showInfo['weight'];?></p>
      <p id="age">Age: <?php echo $showInfo['age'];?></p>
      <p id="height">Height(CM): <?php echo $showInfo['height'];?></p>
      <p id="gender">Gender: <?php echo $showInfo['gender'];?></p>
      <hr/>
      <form method="post" action="helper_php/insertGoal.php" onsubmit="return checkForm()">
        <?php
        if($_SESSION['dietary'] == "keep"){
          echo "<p><input type=\"hidden\" name=\"goalWeight\" value=\"NULL\"></p>";
        }
        else{
          echo "<p>Goal Weight:<input name=\"goalWeight\" type=\"number\" id=\"goalWeight\" min=\"1\" step=\"0.01\" onblur=\"suggestPeriod()\"></p>";
        }?>
        <div class="row">
          <div class="col-sm-6">
            <p class="input-wrap"><span class="label-input">TIME PERIOD FROM</span></br>
              <input class="date-input" type="date" name="begin" id="begin" onchange="day()">
            </p> 
          </div>
          <div class="col-sm-6">
            <p class="input-wrap"><span class="label-input">TO</br></span>
              <input class="date-input" type="date" name="end" id="end" onchange="day()"> <span id="d"></span>
            </p>
          </div>
        </div>
        <div class="row" style="padding-top:20px;">
          <div class="col-sm-12">
            <p class="input-wrap"><span class="label-input">EXERCISE HABIT</br></span>
              <div class="combo-container">
                <input type="radio" name="exerciseType" value="0">Sedentary
                <input type="radio" name="exerciseType" value="1">Lightly
                <input type="radio" name="exerciseType" value="2">Moderate
                <input type="radio" name="exerciseType" value="3">Very Active
              </div>
            </p>
          </div>
        </div>  
        <div class="row">
          <div class="col-sm-12">
            <p id="suggestion">________________</p>
          </div>
        </div>     
        <div class="row">
          <div class="col-sm-12" style="text-align: left;padding-left: 32px;">
            <input type="submit" class="submit-btn" name="plan" value="PLAN NOW!">
          </div>
        </div>
        
      </form>
    </div>
  </div>
</body>
</html>
<?php ?>