<?php
  require '../function/class/User.php';
  require '../function/db.php';
  session_start();

  //check if user is logged in
  if(!isset($_SESSION['user'])){
    header("Location: ./login.php");
  }

  //set session variable and update user information
  $user=$_SESSION['user'];
  $user->checkUser();

  //only if user is admin
  if(!isset($_SESSION['isAdmin'])){
    header("Location: ./dashboard.php");
  }

  if(!isset($_GET['id'])){
    //if no ID is specified
    $editGame=false;
  }else{
    //pull to be edited game information
    $conn=connectDB();
    $editGame=getGameName($conn, $_GET['id']);
    $gameInfo=getGameInfo($conn, $_GET['id']);
    $conn=null;
  }

  //if game does not exist go to admin panel
  if($editGame===false){
    header("Location: ./admin.php");
  }

  //on form submit
  if(isset($_POST['gamename'])){
    //check for empty values and if at least 3 users are selected
    if(!empty($_POST['gamename'])){
      //only aplhanumeric and spaces in game name
      if(ctype_alnum(str_replace(" ","",$_POST['gamename']))){
        //if no changes were made
        if($_POST['gamename']!=$editGame['name']){
          //update the name and notify user
          $conn=connectDB();
          $result=updateGameName($conn, $_GET['id'], $_POST['gamename']);
          $conn=null;

          if($result===true){
            $editGame['name']=$_POST['gamename'];
            $success="Successfully updated the game name.";
          }else{
            $error="There was an error updating the game name.";
          }
        }else{
          $success="Successfully updated the game name.";
        }
      }else{
        $error="The game name can only be alphanumeric.";
      }
    }else{
      $error="Please enter a game name.";
    }
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Edit Game</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.16/r-2.2.1/datatables.min.css"/>
    <link rel="stylesheet" href="assets/css/main.css">
    <link href="favicon.ico" rel="icon" type="image/x-icon"/>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light sticky-top">
      <div class="container">
        <a class="navbar-brand" href="index.php">
          <img src="assets/img/logo2.svg" height="30" alt="">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation" style="">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarColor02">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="index.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="participations.php">Participations</a>
            </li>
            <?php
              //only show if admin
              if(isset($_SESSION['isAdmin'])){
            ?>
              <li class="nav-item active">
                <a class="nav-link" href="admin.php">Admin <span class="sr-only">(current)</span></a>
              </li>
            <?php } ?>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Account
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="settings.php">Settings</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout.php">Logout</a>
              </div>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="headerimg"></div>
    <div class="container mt-5">

      <h1>Edit Game</h1>

      <p class="lead">Here you can edit the game.</p>

      <?php
        //echo all errors and success messages
        if(isset($success)){
      ?>
        <div class="alert alert-dismissible alert-success">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <?php echo $success; ?>
        </div>
      <?php
        }elseif(isset($error)){
      ?>
        <div class="alert alert-dismissible alert-danger">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <?php echo $error; ?>
        </div>
      <?php } ?>

      <form action="" method="post"  class="mt-5">
        <div class="form-group row">
          <label for="gamename" class="col-3 col-form-label">Game Name</label>
          <div class="col-9">
            <input id="gamename" name="gamename" type="text" required="required" class="form-control here" <?php echo "value=\"".$editGame['name']."\""; ?>>
          </div>
        </div>
        <div class="form-group row">
          <div class="offset-3 col-9">
            <button name="submit" type="submit" class="btn btn-primary">Update</button>
          </div>
        </div>
      </form>

      <h2 class="mt-5">Participants and Assignment</h2>
      <p class="lead">You cannot edit this.</p>

      <hr class="mt-5">
      <table id="gameAssign" class="table table-striped" style="width:100%;">
        <thead>
          <tr>
            <th colspan="3">From</th>
            <th colspan="3">To</th>
          </tr>
          <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
          </tr>
        </thead>
        <tbody>
          <?php
            //print out all users

            foreach ($gameInfo as $current) {
              echo "<tr>\n<td>";
              echo $current['fromfname'];
              echo "\n</td>\n<td>";
              echo $current['fromlname'];
              echo "\n</td>\n<td>";
              echo $current['fromemail'];
              echo "\n</td>\n<td>";
              echo $current['tofname'];
              echo "\n</td>\n<td>";
              echo $current['tolname'];
              echo "\n</td>\n<td>";
              echo $current['toemail'];
              echo "\n</td>\n</tr>";
            }

          ?>
        </tbody>
      </table>
      <hr>

      <h3 class="mt-5">Danger Zone</h3>
      <p class="lead">This action cannot be undone. Be careful.</p>
      <a href="removeGame.php?id=<?php echo $_GET['id']; ?>" class="btn btn-danger">Delete this game</a>

    </div>

    <footer class="footer mt-5">
      <div class="container text-center">
        <span class="text-muted">&copy; 2018 Benjamin Buzek, Alexander Gaddy, Lukas Lenhardt | <a href="legal.php">Legal</a></span>
      </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.16/r-2.2.1/datatables.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        $('#gameAssign').DataTable( {
          responsive: true,
          ordering: true,
          columnDefs: [{
            orderable: false,
            targets: "no-sort"
          }]
        });
      });
    </script>
  </body>
</html>
