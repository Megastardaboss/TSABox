<?php
//Basic function to sanitize input data
function validate($data){
  $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $data = str_replace('\\', '', $data);
    $data = str_replace('/', '', $data);
    $data = str_replace("'", '', $data);
    $data = str_replace(";", '', $data);
    $data = str_replace("(", '', $data);
    $data = str_replace(")", '', $data);
    return $data;
}

session_start();
$search = "";
$username = $_SESSION['username'];

//Inputting form data into database
if(isset($_POST['search'])){

  //Accept POST variables, reassign for query
  $search = validate($_POST['search']);
}

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../bootstrap-4.1.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styles.css">

    <title>TSABox</title>
  </head>

  <body>    
    <!-- Nav Bar -->
    <nav class="header bg-blue navbar navbar-expand-sm navbar-dark" style="min-height:95px; z-index: 1000;">
        <a class="navbar-brand" href="main.php">
          <div class="row">
            <div class="col nopadding">
                <img src="../images/logo.png" class="d-inline-block verticalCenter" alt="" style="height:2.5rem;">
            </div>
  <a class="navbar-brand" href="main.php">TSABox</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          OfficerBox
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="president.php">President</a>
          <a class="dropdown-item" href="vice.php">Vice President</a>
          <a class="dropdown-item" href="secretary.php">Secretary</a>
          <a class="dropdown-item" href="treasurer.php">Treasurer</a>
          <a class="dropdown-item" href="reporter.php">Reporter</a>
          <a class="dropdown-item" href="parliamentarian.php">Parliamentarian</a>
        </div>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          EventBox
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="myevents.php">My Events</a>
          <a class="dropdown-item" href="rules.php">Rules</a>
          <a class="dropdown-item" href="selection.php">Event Selection</a>
          <a class="dropdown-item" href="quiz.php">Interest Quiz</a>
        </div>
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          SocialBox
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="profile.php">My Profile</a>
          <a class="dropdown-item" href="social.php">Find Friends</a>
          <a class="dropdown-item" href="inbox.php">My Inbox</a>
        </div>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="../php/logout.php">
          Logout
        </a>
      </li>
    </ul>
  </div>
</nav>

<!-- THE MEAT -->

<div class = "container" style="padding-bottom: 25px">
  <div class = "row">
    <h3>
      Here you can find other people in TSA
      </h3>
  </div>
</div>


<div class="container" style="padding-bottom: 25px;">
  <div class = "row">
  	<h5>
    Here are some of your chapter members
	</h5>
  </div>
  <?php 
    //display all people in your chapter
    require('../php/connect.php');

    $query = "SELECT chapter FROM user_chapter_mapping WHERE username = '$username'";
    $result = mysqli_query($link, $query);
    if(!$result){
      die('Error: ' . mysqli_error($link));
    }
    list($chapter) = mysqli_fetch_array($result);

    $query = "SELECT username FROM user_chapter_mapping WHERE chapter='$chapter'";
    $result = mysqli_query($link, $query);

    if(!$result){
      die('Error: ' . mysqli_error($link));
    } 

    while(list($chapterMates) = mysqli_fetch_array($result)){

    $query1 = "SELECT username, firstname, lastname FROM users WHERE username='$chapterMates' AND username!='$username'";
    $result1 = mysqli_query($link, $query1);

    if(!$result1){
      die('Error: ' . mysqli_error($link));
    } 
    list($Username, $firstname, $lastname) = mysqli_fetch_array($result1);

      ?>
      <div class= "row" >
        <div class = "col-sm-3" >
          <a href=<?php echo "profile.php?user=" . $Username; ?>>
            <?php echo $firstname . " " . $lastname ?>
          </a>
        </div>
      </div>
      <?php
    }

  ?>
</div>

<div class = "container" id="content">
    <div class="row" style="padding-top:1rem; padding-bottom:1rem;">
          <h3 style="border-bottom:2px solid #CF0C0C">Other Users</h3>
      </div>
          <input class="form-control" id="userSearch" type="text" placeholder="Search...">
          <table id="userTable" class="table table-striped">
            <tbody>
            <?php
        		require('../php/connect.php');
              	$query = "SELECT chapter FROM user_chapter_mapping WHERE username = '$username'";
		    	$result = mysqli_query($link, $query);
			    if(!$result){
			      die('Error: ' . mysqli_error($link));
			    } 
			    list($chapter) = mysqli_fetch_array($result);
			    $query = "SELECT username FROM user_chapter_mapping WHERE chapter !='$chapter'";
			    $result = mysqli_query($link, $query);

			    if(!$result){
			      die('Error: ' . mysqli_error($link));
			    } 
                //Iterate every event at my level
                while(list($otherPeople) = mysqli_fetch_array($result)){
                  
                    $query1 = "SELECT username, firstname, lastname FROM users WHERE username='$otherPeople'";
				    $result1 = mysqli_query($link, $query1);

				    if(!$result1){
				      die('Error: ' . mysqli_error($link));
				    } 
				    list($Username, $firstname, $lastname) = mysqli_fetch_array($result1);

				    
				      ?>

            	<tr>
            		<td>
				          <a href=<?php echo "profile.php?user=" . $Username; ?>>
				            <?php echo $firstname . " " . $lastname ?>
				          </a>
				      </td>
				  </tr>
				      <?php
                  }
            ?>
            </tbody>
          </table>
  </div>


<!-- people not in your chapter -->
<!--
<div class="container" style="padding-bottom: 25px;">
  <div class = "row">
  	<h5>
    Here are some other TSA members
	</h5>
  </div>
  <?php 
    //display people not in your chapter that match the search
    require('../php/connect.php');

    $query = "SELECT chapter FROM user_chapter_mapping WHERE username = '$username'";
    $result = mysqli_query($link, $query);
    if(!$result){
      die('Error: ' . mysqli_error($link));
    } 
    list($chapter) = mysqli_fetch_array($result);
    $query = "SELECT username FROM user_chapter_mapping WHERE chapter !='$chapter'";
    $result = mysqli_query($link, $query);

    if(!$result){
      die('Error: ' . mysqli_error($link));
    } 

    while(list($otherPeople) = mysqli_fetch_array($result)){
    $query1 = "SELECT username, firstname, lastname FROM users WHERE username='$otherPeople'";
    $result1 = mysqli_query($link, $query1);

    if(!$result1){
      die('Error: ' . mysqli_error($link));
    } 
    list($Username, $firstname, $lastname) = mysqli_fetch_array($result1);

    echo $search . " " . "." . $Username;
	echo strpos("." . $Username, $search);
    if($search==""||(strpos(" " . $Username, $search))>=0){
      ?>
      <div class= "row" >
        <div class = "col-sm-3" >
          <a href=<?php echo "profile.php?user=" . $Username; ?>>
            <?php echo $firstname . " " . $lastname ?>
          </a>
        </div>
      </div>
      <?php
    }
}
  ?>
</div>
-->
  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="../js/jquery-3.3.1.slim.min.js"></script>
  <script src="../js/popper.min.js"></script>
  <script src="../bootstrap-4.1.0/js/bootstrap.min.js"></script>
  <script src="../js/scripts.js"></script>

  <script>
    $(document).ready(function(){
      $("#userSearch").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#userTable > tbody > tr").filter(function() {
          $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
      });
    });
  </script>


  </body>

  <footer style = "position: relative;">
    <div class="bg-blue color-white py-3">
        <center>
        <p>
          For more information, visit <a href="about.php" style="color:white;">The About Page</a>.
        </p>
        <p>
          Made by Team T1285, 2018-2019, All Rights Reserved
        </p>
        </center>
    </div>
  </footer>

</html>