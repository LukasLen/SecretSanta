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
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
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
            <li class="nav-item active">
              <a class="nav-link" href="dashboard.php">Dashboard <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="participations.php">Participations</a>
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

      <h1>Dashboard</h1>

      <p class="lead">Welcome, Merry Christmas to you <?php echo $user->getFname(); ?>.</p>

      <?php
        //only show if admin
        if(isset($_SESSION['isAdmin'])){
      ?>
        <div class="row mt-5">
          <div class="col-sm-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Manage Games</h5>
                <p class="card-text">Create and edit your Secret Santa games.</p>
                <a href="admin.php#games" class="btn btn-outline-success">Games</a>
              </div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title">Manage Users</h5>
                <p class="card-text">Add and manage your users.</p>
                <a href="admin.php#users" class="btn btn-outline-success">Users</a>
              </div>
            </div>
          </div>
        </div>
      <?php } ?>
      <div class="row mt-5">
        <div class="col-sm-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Your Participations</h5>
              <p class="card-text">See and check all your current Secret Santa participations.</p>
              <a href="participations.php" class="btn btn-outline-success">Participations</a>
            </div>
          </div>
        </div>
        <div class="col-sm-6">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Account Settings</h5>
              <p class="card-text">Manage your account and update your information.</p>
              <a href="settings.php" class="btn btn-outline-success">Settings</a>
            </div>
          </div>
        </div>
      </div>

    </div>

    <footer class="footer mt-5">
      <div class="container text-center">
        <span class="text-muted">&copy; 2018 Benjamin Buzek, Alexander Gaddy, Lukas Lenhardt | <a href="legal.php">Legal</a></span>
      </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  </body>
</html>
