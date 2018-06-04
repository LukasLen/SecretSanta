<?php
  require '../function/class/User.php';
  require '../function/db.php';
  session_start();

  if(isset($_SESSION['user'])){
    header("Location: ./dashboard.php");
  }

  //check if request is sent and variables are not empty
  if(isset($_POST['email'], $_POST['password'])){
    if(!empty($_POST['email']) && !empty($_POST['password'])){

      //connect to database and pull information about the user
      $conn=connectDB();
      $user=getUser($conn, $_POST['email']);
      $conn=null;

      //check if user even exists
      if($user!==false){
        //check password and set session variables
        if(password_verify($_POST['password'], $user['password'])){
          $_SESSION['user']=new User($user['ID'], $user['email'], $user['fname'], $user['lname'], $user['password']);
          if($user['admin']==true){
            $_SESSION['isAdmin']=true;
          }
          //send to dashboard
          header("Location: ./dashboard.php");
        }else{
          $error="Email or password wrong.";
        }
      }else{
        $error="Email or password wrong.";
      }
    }else{
      $error="Email or password wrong.";
    }
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Login</title>
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
              <a class="nav-link" href="login.php">Login <span class="sr-only">(current)</span></a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
    <div class="headerimg"></div>
    <div class="container mt-5">

      <h1>Login</h1>

      <?php
        if(isset($error)){
      ?>
        <div class="alert alert-dismissible alert-danger mt-5">
          <button type="button" class="close" data-dismiss="alert">&times;</button>
          <?php echo $error; ?>
        </div>
      <?php } ?>

      <form action="" method="post" class="mt-5">
        <div class="form-group row">
          <label class="col-3 col-form-label" for="email">Email</label>
          <div class="col-9">
            <input id="email" name="email" type="text" required="required" class="form-control here">
          </div>
        </div>
        <div class="form-group row">
          <label for="password" class="col-3 col-form-label">Password</label>
          <div class="col-9">
            <input id="password" name="password" type="password" required="required" class="form-control here">
          </div>
        </div>
        <div class="form-group row">
          <div class="offset-3 col-9">
            <button name="submit" type="submit" class="btn btn-primary">Login</button>
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
