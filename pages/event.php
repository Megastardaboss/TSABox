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

$username = $_SESSION['username'];
$team = $_SESSION['team'];

if(isset($_POST['task-name'])){

  $taskname = validate($_POST['task-name']);
  $taskdesc = validate($_POST['task-description']);
  $taskweight = validate($_POST['task-weight']);

  require('../php/connect.php');
  $query = "INSERT INTO tasks (name, description, team, creator, weight) VALUES ('$taskname', '$taskdesc', '$team', '$username', '$taskweight')";
  $result = mysqli_query($link,$query);
  if (!$result){
      die('Error: ' . mysqli_error($link));
  }
  mysqli_close($link);

}

if(isset($_POST['task-delete'])){

  $taskdelete = validate($_POST['task-delete']);

  require('../php/connect.php');
  $query = "DELETE FROM tasks WHERE id='$taskdelete'";
  $result = mysqli_query($link,$query);
  if (!$result){
      die('Error: ' . mysqli_error($link));
  }
  mysqli_close($link);

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
    <nav class="header bg-blue navbar navbar-expand-lg navbar-dark" style="min-height:95px; z-index: 1000;">
        <a class="navbar-brand" href="index.html">
          <div class="row">
            <div class="col nopadding">
                <img src="../images/logo.png" class="d-inline-block verticalCenter" alt="" style="height:2.5rem;">
            </div>
  <a class="navbar-brand" href="#">TSABox</a>
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
          <a class="dropdown-item" href="chapter.php">My Chapter</a>
          <a class="dropdown-item" href="social.php">Find Friends</a>
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

    <div class="container" id="content">
      <h1>
      <?php

        require('../php/connect.php');

        $query="SELECT name FROM events WHERE id IN (SELECT event FROM teams WHERE id='$team')";
        $result = mysqli_query($link, $query);
        if (!$result){
          die('Error: ' . mysqli_error($link));
        }
        list($eventname) = mysqli_fetch_array($result);
        echo $eventname;
      ?>
      </h1>
      <small>Manage your team tasks, files, and communication</small>

      <div class="row pt-5">
        <div class="col-sm-12">
        <h3 class="band-red">Tasks in Progress</h3>

        <div class="d-flex justify-content-start flex-wrap">
          <?php

              require('../php/connect.php');

              $query="SELECT id, name, description, creator, date, weight FROM tasks WHERE team='$team' AND status='progress'";
              $result = mysqli_query($link, $query);
              if (!$result){
                die('Error: ' . mysqli_error($link));
              }

              if(mysqli_num_rows($result) == 0){
                echo "No Tasks in Progress";
              }
              else{
                while($resultArray = mysqli_fetch_array($result)){

                  $taskname = $resultArray['name'];
                  $taskid = $resultArray['id'];
                  $taskdesc = $resultArray['description'];
                  $taskcreator = $resultArray['creator'];
                  $taskdate = $resultArray['date'];
                  $taskweight = $resultArray['weight'];

                  $query2="SELECT firstname, lastname FROM users WHERE username='$taskcreator'";
                  $result2 = mysqli_query($link, $query2);
                  if (!$result2){
                    die('Error: ' . mysqli_error($link));
                  }
                  list($firstname,$lastname) = mysqli_fetch_array($result2);

                  ?>
                  
                  <div class="taskcard">
                    <div class="row">
                      <div class="col-8">
                        <h5><?php echo $taskname; ?></h5>
                      </div>
                      <div class="col-4">
                        <h3><?php echo $taskweight; ?></h3>
                      </div>
                    </div>
                    <hr>
                    <p><?php echo $taskdesc; ?></p>
                    <small>Started <?php echo $taskdate; ?> by <?php echo $firstname . " " . $lastname; ?></small>
                    <hr>
                    <div class="row">
                      <div class="col-sm-6">
                        <form method="post">
                          <input type="hidden" name="task-complete" value="<?php echo $taskid; ?>">
                          <input type="submit" value="Complete" class="btn btn-link">
                        </form>
                      </div>
                      <div class="col-sm-6">
                        <form method="post">
                          <input type="hidden" name="task-delete" value="<?php echo $taskid; ?>">
                          <input type="submit" value="Delete" class="btn btn-danger">
                        </form>
                      </div>
                    </div>
                  </div>

                  <?php
                }
              }
            ?>
        </div>
        </div>
      </div>

      <div class="row pt-5">
        <div class="col-sm-12">
        <h3 class="band-grey">Backlog</h3>

        <div class="d-flex justify-content-start flex-wrap">
          <?php

              require('../php/connect.php');

              $query="SELECT id, name, description, creator, date, weight FROM tasks WHERE team='$team' AND status='backlog'";
              $result = mysqli_query($link, $query);
              if (!$result){
                die('Error: ' . mysqli_error($link));
              }

              if(mysqli_num_rows($result) == 0){
                echo "No Backlogged Tasks";
              }
              else{
                while($resultArray = mysqli_fetch_array($result)){

                  $taskname = $resultArray['name'];
                  $taskid = $resultArray['id'];
                  $taskdesc = $resultArray['description'];
                  $taskcreator = $resultArray['creator'];
                  $taskdate = $resultArray['date'];
                  $taskweight = $resultArray['weight'];

                  $query2="SELECT firstname, lastname FROM users WHERE username='$taskcreator'";
                  $result2 = mysqli_query($link, $query2);
                  if (!$result2){
                    die('Error: ' . mysqli_error($link));
                  }
                  list($firstname,$lastname) = mysqli_fetch_array($result2);

                  ?>
                  
                  <div class="taskcard">
                    <div class="row">
                      <div class="col-8">
                        <h5><?php echo $taskname; ?></h5>
                      </div>
                      <div class="col-4">
                        <h3><?php echo $taskweight; ?></h3>
                      </div>
                    </div>
                    <hr>
                    <p><?php echo $taskdesc; ?></p>
                    <small>Created <?php echo $taskdate; ?> by <?php echo $firstname . " " . $lastname; ?></small>
                    <hr>
                    <div class="row">
                      <div class="col-sm-6">
                        <form method="post">
                          <input type="hidden" name="task-begin" value="<?php echo $taskid; ?>">
                          <input type="submit" value="Begin" class="btn btn-link">
                        </form>
                      </div>
                      <div class="col-sm-6">
                        <form method="post">
                          <input type="hidden" name="task-delete" value="<?php echo $taskid; ?>">
                          <input type="submit" value="Delete" class="btn btn-danger">
                        </form>
                      </div>
                    </div>
                  </div>

                  <?php
                }
              }
            ?>
        </div><br>

          <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#taskModal">
            Create Task
          </button>
        </div>
      </div>

      <div class="row pt-5">
        <div class="col-sm-12">
        <h3 class="band-blue">Complete Tasks</h3>

        <div class="d-flex justify-content-start flex-wrap">
          <?php

              require('../php/connect.php');

              $query="SELECT id, name, description, creator, date, weight FROM tasks WHERE team='$team' AND status='complete'";
              $result = mysqli_query($link, $query);
              if (!$result){
                die('Error: ' . mysqli_error($link));
              }

              if(mysqli_num_rows($result) == 0){
                echo "No Completed Tasks";
              }
              else{
                while($resultArray = mysqli_fetch_array($result)){

                  $taskname = $resultArray['name'];
                  $taskid = $resultArray['id'];
                  $taskdesc = $resultArray['description'];
                  $taskcreator = $resultArray['creator'];
                  $taskdate = $resultArray['date'];
                  $taskweight = $resultArray['weight'];

                  $query2="SELECT firstname, lastname FROM users WHERE username='$taskcreator'";
                  $result2 = mysqli_query($link, $query2);
                  if (!$result2){
                    die('Error: ' . mysqli_error($link));
                  }
                  list($firstname,$lastname) = mysqli_fetch_array($result2);

                  ?>
                  
                  <div class="taskcard">
                    <div class="row">
                      <div class="col-8">
                        <h5><?php echo $taskname; ?></h5>
                      </div>
                      <div class="col-4">
                        <h3><?php echo $taskweight; ?></h3>
                      </div>
                    </div>
                    <hr>
                    <p><?php echo $taskdesc; ?></p>
                    <small>Completed <?php echo $taskdate; ?> by <?php echo $firstname . " " . $lastname; ?></small>
                    <hr>
                    <form method="post">
                      <input type="hidden" name="task-delete" value="<?php echo $taskid; ?>">
                      <input type="submit" value="Delete" class="btn btn-danger">
                    </form>
                  </div>

                  <?php
                }
              }
            ?>
        </div><br>
        </div>
      </div>

    </div>

    <!-- Task Modal -->
    <div class="modal fade" id="taskModal" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Create Task</h4>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form method="POST" class="pt-4">
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="task-name">Task Name</label>
                  <input type="text" maxlength="50" class="form-control" id="task-name" name="task-name" placeholder="Name">
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-12">
                  <label for="task-name">Task Description</label>
                  <textarea class="form-control" maxlength="500" id="task-description" name="task-description" rows="3"></textarea>
                </div>
              </div>
              <div class="form-row">
                <div class="form-group col-md-6">
                  <small>
                  Task weight is a number that tracks how important this task is to the project. A task with weight 4 is twice as important as one with weight 2.
                  </small>
                </div>
                <div class="form-group col-md-6">
                  <label for="task-weight">Task Weight</label>
                  <input type="number" value="1" min="0" max="100" name="task-weight">
                </div>
              </div>
              <button type="submit" class="btn btn-primary">Create Task</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="../js/jquery-3.3.1.slim.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../bootstrap-4.1.0/js/bootstrap.min.js"></script>
    <script src="../js/scripts.js"></script>
  </body>

  <footer style="position:relative;">
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

<?php 

?>