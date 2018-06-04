<?php
  require '../function/class/User.php';
  require '../function/db.php';
  //Load config file
  require_once __DIR__.'\..\config.php';
  session_start();

  //check if user is logged in
  if(!isset($_SESSION['user'])){
    header("Location: ./login.php");
  }

  //set session variable and update user information
  $user=$_SESSION['user'];
  $user->checkUser();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Participations</title>
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
              <a class="nav-link active" href="participations.php">Participations <span class="sr-only">(current)</span></a>
            </li>
            <?php
              //only show if admin
              if(isset($_SESSION['isAdmin'])){
            ?>
              <li class="nav-item">
                <a class="nav-link" href="admin.php">Admin</a>
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

      <h1>Your Participations</h1>

      <p class="lead">The maxmium amount to spend on a gift is <?php global $cost; echo $cost; ?>.</p>

      <hr class="mt-5">
      <table id="gameAssign" class="table table-striped" style="width:100%;">
        <thead>
          <tr>
            <th></th>
            <th colspan="3">Make a gift to</th>
          </tr>
          <tr>
            <th>Game Name</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
          </tr>
        </thead>
        <tbody>
          <?php
            //print out all participations
            $conn=connectDB();
            $part=getParticipations($conn,$user->getID());
            $conn=null;

            if($part!==false){
              foreach ($part as $current) {
                echo "<tr>\n<td>";
                echo $current['gamename'];
                echo "\n</td>\n<td>";
                echo $current['tofname'];
                echo "\n</td>\n<td>";
                echo $current['tolname'];
                echo "\n</td>\n<td>";
                echo $current['toemail'];
                echo "\n</td>\n</tr>";
              }
            }

          ?>
        </tbody>
      </table>
      <hr>

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
