<?php
include_once("helper_php/dietaryGoal.php");
require_once("login/loginCheck.php");
if(!LoginCheck::isLoggedIn()){
  header('Location: login/login.php');
  exit;
}
if(!isset($_SESSION['dietary'])){
  header('Location: setDietary.php');
  exit;
}
//echo jsonPeriod();
?>
<script type="text/javascript">
  //var text = '{"jsonArr":<?php //echo jsonPeriod();?>//}';
  //jsonObj = JSON.parse(text);
  //alert(jsonObj.jsonArr.length);
  // function periodOverlaps(date1,date2,date3,date4){
  //   if(date1<date3 && date2<date3){
  //     return true;
  //   }
  //   if(date1>date4 && date2>date4){
  //     return true;
  //   }
  //   return false;
  // }
  function periodOverlaps(startDate1, endDate1, startDate2, endDate2) {
      return (startDate1 <= endDate2) && (endDate1 >= startDate2)
  }
  function checkForm(){
    var check= 0;
    var check_p = 0;
    var message="";
    var text = '{"jsonArr":<?php echo jsonPeriod();?>}';
    var jsonObj = JSON.parse(text);
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
        console.log(goal);
      //alert("please set goal in dietary");
      message+="please set goal in dietary<br>";
      check++;
      //return false;
    }
    if(weight.substr(weight.length-1) == ">"){
      //alert("please set weight in profile");
      message+="please set weight in profile<br>";
      check++;
      //return false;
    }
    else{
        check_p++;
    }
    if(age.substr(age.length-1) == ">"){
      //alert("please set age in profile");
      message+="please set age in profile<br>";
      check++;
      //return false;
    }
    if(height.substr(height.length-1) == ">"){
      //alert("please set height in profile");
      message+="please set height in profile<br>";
      check++;
      //return false;
    }
    if(gender.substr(gender.length-1) == ">"){
      alert("please set gender in profile");
      message+="please set gender in profile<br>";
      check++;
      //return false;
    }

    var begin = document.getElementById("begin").value;
    var end = document.getElementById("end").value;
    var diet = document.getElementById("goal").innerHTML;
    diet = diet.substr(diet.length-4);
    //if(diet == "keep"){
      if(diet != "keep"){
          var goalWeight = document.getElementById("goalWeight").value;
          if(goalWeight.length == 0){
              //alert("Please set goal weight!");//error04
              message+="Please set goal weight!<br>";
              check++;
              //return false;
          }
          else{
              check_p++;
          }
      }


      if(begin.length == 0||end.length == 0){
        //alert("Please set begin and end date!");//error00
        message+="Please set begin and end date!<br>";
        check++;
        //return false;
      }
      else{
          if(begin>end){
              //alert("Begin date must before end date!");//error01
              message+="Begin date must before end date!<br>";
              check++;
              //return false;
          }
          else{
              check_p++;
          }
      }
      if(check_p == 3){
          <?php $showInfo = checkInfo();?>
          var weightCur = <?php  echo floatval($showInfo['weight']);?>;
          weightCur = parseFloat(weightCur);
          goalWeight = parseFloat(goalWeight);
          console.log(weightCur);
          console.log(goalWeight);
          var goal = Math.abs(weightCur-goalWeight);
          var suggestDay = goal*7000/1000;

          var date1=Date.parse(begin);
          var date2=Date.parse(end);
          if(date1>date2){
              check++;
          }
          else{
              var day=1+(date2-date1)/1000/60/60/24;
          }
          if(suggestDay>day){
              check++;
              if(diet == "lose"){
                  message+="It’s not healthy to reach this goal within this time period. Please either increase the goal weight or make the period longer<br>";
              }
              else{
                  message+="It’s not healthy to reach this goal within this time period. Please either decrease the goal weight or make the period longer.<br>";
              }

          }

      }

      var habitLen = document.getElementsByName("exerciseType").length;
      var count=0;
      for(var i=0;i<habitLen;i++){
        if(document.getElementsByName("exerciseType")[i].checked){
          count++;
        }
      }
      if(!count){
        //alert("Please choose EXERCISE HABIT!");//error02
        message+="Please choose EXERCISE HABIT!<br>";
        check++;
        //return false;
      }

      var td = new Date();
      var y = td.getUTCFullYear();
      var m = td.getUTCMonth()+1;
      var d = td.getUTCDate();
      if(d<10){
        d="0"+d;
      }
      var today = y+"-"+m+"-"+d;
      //alert(today+" "+begin+" "+typeof today+" "+typeof begin);
        var todayN = new Date(today);
        var beginN = new Date(begin);
        if(todayN>beginN){
        //alert("Please choose the begin date after today!");
        message+="Please choose the begin date after today!<br>";
        check++;
        //return false;
      }
        var datesOverlap1 = [];
        for(i=0;i<jsonObj.jsonArr.length;i++) {
            if(periodOverlaps(begin,end,jsonObj.jsonArr[i].begin,jsonObj.jsonArr[i].end)){
                //alert("There are some days in your period in your another plan!")
                console.log(begin+" "+" "+end);
                datesOverlap1.push({
                    startDate : jsonObj.jsonArr[i].begin,
                    endDate : jsonObj.jsonArr[i].end
                });
            }
        }
        console.log(datesOverlap1);
        console.log(datesOverlap1.length);
        if (datesOverlap1.length !== 0) {
            console.log(datesOverlap1);
            message += "Your current plan is overlapping with the following plans: <br>";
            for (var counter = 0; counter < datesOverlap1.length; counter++) {
                console.log(counter);
                var currentOverlapCounter = datesOverlap1[counter];
                console.log(currentOverlapCounter);
                message += "From: " + currentOverlapCounter.startDate + " To : " + currentOverlapCounter.endDate + "<br>";
            }
            //alert(message1);

            check++;
            //return false;
        }

        if(check!=0){
            document.getElementById("message").className="row alert alert-warning";
            document.getElementById("message").innerHTML="<h5 style=\"color:red\">Message:</h5><br>"+message;
            return false;
        }

    //}
    // else{
    //     var goalWeight = document.getElementById("goalWeight").value;
    //     if(goalWeight.length == 0){
    //         //alert("Please set goal weight!");//error04
    //         message+="Please set goal weight!<br>";
    //         check++;
    //         //return false;
    //     }
    //     if(begin.length == 0||end.length == 0){
    //         //alert("Please set begin and end date!");
    //         message+="Please set begin and end date!<br>";
    //         check++;
    //         //return false;
    //     }
    //     else{
    //         if(begin>end){
    //             //alert("Begin date must before end date!");
    //             message+="Begin date must before end date!<br>";
    //             check++;
    //             //return false;
    //         }
    //     }
    //
    //     var habitLen = document.getElementsByName("exerciseType").length;
    //     var count=0;
    //     for(i=0;i<habitLen;i++){
    //         if(document.getElementsByName("exerciseType")[i].checked){
    //             count++;
    //         }
    //     }
    //     if(!count){
    //         // alert("Please choose EXERCISE HABIT!");
    //         message+="Please choose EXERCISE HABIT!<br>";
    //         check++;
    //         // return false;
    //     }
    //     var td = new Date();
    //     var y = td.getUTCFullYear();
    //     var m = td.getUTCMonth()+1;
    //     var d = td.getUTCDate();
    //     if(d<10){
    //         d="0"+d;
    //     }
    //     var today = y+"-"+m+"-"+d;
    //     //alert(stringDate);
    //     var todayN = new Date(today);
    //     var beginN = new Date(begin);
    //     // alert(todayN.getTime());
    //     // alert(beginN.getTime());
    //     if(todayN>beginN){
    //         message+="Please choose the begin date after today!<br>";
    //         check++;
    //         // alert("Please choose the begin date after today!");
    //         // return false;
    //     }
    //
    //     var datesOverlap = [];
    //     for(i=0;i<jsonObj.jsonArr.length;i++) {
    //         if(periodOverlaps(begin,end,jsonObj.jsonArr[i].begin,jsonObj.jsonArr[i].end)){
    //             //alert("There are some days in your period in your another plan!")
    //             datesOverlap1.push({
    //                 startDate : jsonObj.jsonArr[i].begin,
    //                 endDate : jsonObj.jsonArr[i].end
    //             });
    //         }
    //     }
    //     if (datesOverlap.length !== 0) {
    //         message += "Your current plan is overlapping with the following plans:<br>"
    //         for (var index = 0; index < datesOverlap.length; ++index) {
    //             var currentOverlap = datesOverlap[index];
    //             message += "From: " + currentOverlap.startDate + " To : " + currentOverlap.endDate + "<br>";
    //         }
    //         //alert(message);
    //         check++;
    //         // return false;
    //     }
    //     if(check!=0){
    //         document.getElementById("message").innerHTML="<h5 style=\"color:red\">Message:</h5><br>"+message;
    //         return false;
    //     }
    //     // return true;
    // }

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
      day = Math.ceil(day);
      document.getElementById("suggestion").innerHTML="please choose a period at least "+day+" days to reach your goal and keep a health lifestle!";
    }
    else{
      document.getElementById("infoGW").innerHTML="Please input goal weight!";
    }
  }

  function day(){
    //alert("go");
    var date1=document.getElementById('begin').value;
    var date2=document.getElementById('end').value;
    if(date1.length == 0){
      document.getElementById("d").innerHTML="<br>Please choose begin date!";
      return 0;
    }
    if(date2.length == 0){
      document.getElementById("d").innerHTML="<br>Please choose end date!";
      return 0;
    }
    date1=Date.parse(date1);
    date2=Date.parse(date2);
    if(date1>date2){
      document.getElementById("d").innerHTML="<br>begin date must before end date!";
    }
    else{
      var day=1+(date2-date1)/1000/60/60/24;
      document.getElementById("d").innerHTML="<br>You choose "+day+" days";
      return day;
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
        font-family: Raleway, Arial, Helvetica, sans-serif;
        font-weight: 400;
        line-height: 1.31;
        letter-spacing: 1px;
        font-size: 32px;
        padding: 25px;
        color:black;
      }
      .sub-container{
        padding: 4% 6% 6% 6%;
		
      }
      p{
        text-align:left;
        color:#747474;
      }
      a{
        color:#007bff;
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

  <!-- Navigation Bar -->
  <?php include('includes/navigation.php'); ?>

  <!-- Page Body Start -->
<?php $showInfo = checkInfo();?>
 <div class="wrapper">
  <div class="container conrainer-fluid white-bg page row">
    <h1 id="goal">Goal: <?php echo $_SESSION['dietary'];?></h1>
      <div class="sub-container col-md-8" style="border-top: 1px solid #BABABA;padding-top: 25px; ">
      <div class="" role="alert" style="" id="message"></div>
      <p id="weight">Weight(KG): <?php echo $showInfo['weight'];?></p>
      <p id="age">Age: <?php if($showInfo['age']==-1){echo "Please set your birthday(must before today) correctly in <a href=\"login/profile.php\">profile</a>";}else{echo $showInfo['age'];}?></p>
      <p id="height">Height(CM): <?php echo $showInfo['height'];?></p>
      <p id="gender">Gender: <?php echo $showInfo['gender'];?></p>
      <hr/>
      <form method="post" action="helper_php/insertGoal.php" onsubmit="return checkForm()">
        <?php
        if($_SESSION['dietary'] == "keep"){
          echo "<p><input type=\"hidden\" name=\"goalWeight\" value=\"".$showInfo['weight']."\"></p>";
        }
        elseif($_SESSION['dietary'] == "lose"){
          echo "<p>Goal Weight:<input name=\"goalWeight\" type=\"number\" id=\"goalWeight\" max=\"".($showInfo['weight']-0.01)."\"min=\"1\" step=\"0.01\" onblur=\"suggestPeriod()\"><span id=\"infoGW\"> </span></p>";
        }
        else{
          echo "<p>Goal Weight:<input name=\"goalWeight\" type=\"number\" id=\"goalWeight\" min=\"".($showInfo['weight']+0.01)."\" step=\"0.01\" onblur=\"suggestPeriod()\"><span id=\"infoGW\"> </span></p>";
        }
          ?>
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
  </div>
</body>
</html>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>

<?php ?>
