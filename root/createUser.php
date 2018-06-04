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


  //check if form is submitted
  if(isset($_POST['fname'], $_POST['lname'], $_POST['email'])){
    //check for empty values
    if(!empty($_POST['fname']) && !empty($_POST['lname']) && !empty($_POST['email'])){
      //check if name only uses alphabetic characters
      if(ctype_alpha($_POST['fname']) && ctype_alpha($_POST['lname'])){
        //validate email
        if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
          //create password
          $pw=substr(hash('sha512',rand()),0,6);
          //create user
          $userNew=new User(NULL, $_POST['email'], $_POST['fname'], $_POST['lname'], $pw);
          //TODO:send notification to user
          $userNew->sendPWEmail();
          //hash password
          $userNew->setPassword(password_hash($pw,PASSWORD_DEFAULT));
          //store new user in database
          $conn=connectDB();
          $result=createUser($conn, $userNew);
          $conn=null;
          if(!$result){
            $error="An error occured while creating.";
          }elseif ($result===2) {
            $error="This email is already in use.";
          }else{
            $success="User ".$_POST['email']." created successfully.";
          }
        }else{
          $error="Email is invalid.";
        }
      }else{
        $error="The name can only include alphabetic characters.";
      }
    }
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Create User</title>
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

      <h1>Create User</h1>

      <p class="lead">Please enter all the necessary information.</p>

      <?php
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

      <form action="" method="post">
        <div class="form-group row mt-5">
          <label for="fname" class="col-3 col-form-label">First Name</label>
          <div class="col-9">
            <input id="fname" name="fname" type="text" class="form-control here" required="required" <?php if(isset($error)) echo "value=\"".$_POST['fname']."\""; ?>>
          </div>
        </div>
        <div class="form-group row">
          <label for="lname" class="col-3 col-form-label">Last Name</label>
          <div class="col-9">
            <input id="lname" name="lname" type="text" class="form-control here" required="required" <?php if(isset($error)) echo "value=\"".$_POST['lname']."\""; ?>>
          </div>
        </div>
        <div class="form-group row">
          <label for="email" class="col-3 col-form-label">Email</label>
          <div class="col-9">
            <input id="email" name="email" type="email" class="form-control here" required="required" <?php if(isset($error)) echo "value=\"".$_POST['email']."\""; ?>>
          </div>
        </div>
        <div class="form-group row mt-4">
          <div class="offset-3 col-9">
            <button name="submit" type="submit" class="btn btn-primary">Create User</button>
          </div>
        </div>
      </form>


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
