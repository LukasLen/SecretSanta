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
    $editUser=false;
  }elseif($_GET['id']==$user->getID()) {
    //if the user edits his own account
    header("Location: ./settings.php");
  }else{
    //pull to be edited user information
    $conn=connectDB();
    $editUser=getUserByID($conn, $_GET['id']);
    $conn=null;
  }

  //if user does not exist go to admin panel
  if($editUser===false){
    header("Location: ./admin.php");
  }
  //create user object
  $editUser=new User($editUser['ID'], $editUser['email'], $editUser['fname'], $editUser['lname'], $editUser['password']);

  //check if form is submitted
  if(isset($_POST['fname'], $_POST['lname'], $_POST['email'], $_POST['pass'], $_POST['passconf'])){
    //check for empty values
    if(!empty($_POST['fname']) && !empty($_POST['lname']) && !empty($_POST['email'])){
      //did the information stay the same?
      if($_POST['fname']==$editUser->getFname() && $_POST['lname']==$editUser->getLname() && $_POST['email']==$editUser->getEmail() && empty($_POST['pass']) && empty($_POST['passconf'])){
        $success="Information updated successfully.";
      }else{
        //check if name only uses alphabetic characters
        if(ctype_alpha($_POST['fname']) && ctype_alpha($_POST['lname'])){
          //validate email
          if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            //decide if user wants to chenge password too
            if(empty($_POST['pass']) && empty($_POST['passconf'])){
              /*dont set password too*/
              $userNew=new User($editUser->getID(), $_POST['email'], $_POST['fname'], $_POST['lname'], $editUser->getPassword());
              $conn=connectDB();
              $result=updateUser($conn, $editUser, $userNew);
              $conn=null;
              if(!$result){
                $error="An error occured while updating.";
              }elseif ($result===2) {
                $error="This email is already in use.";
              }else{
                $success="Information updated successfully.";
                //update user information
                $editUser->setEmail($_POST['email']);
                $editUser->setFname($_POST['fname']);
                $editUser->setLname($_POST['lname']);
              }
            }elseif($_POST['pass']==$_POST['passconf']){
              /*set password*/
              $userNew=new User($editUser->getID(), $_POST['email'], $_POST['fname'], $_POST['lname'], $_POST['pass']);
              //send new password to user
              $userNew->sendPWEmail();
              $userNew->setPassword(password_hash($_POST['pass'], PASSWORD_DEFAULT));
              $conn=connectDB();
              $result=updateUser($conn, $editUser, $userNew);
              $conn=null;
              if(!$result){
                $error="An error occured while updating.";
              }elseif ($result===3) {
                $error="This email is already in use.";
              }else{
                $success="Information of ".$_POST['email']." updated successfully.";
                //update user information
                $editUser->setEmail($_POST['email']);
                $editUser->setFname($_POST['fname']);
                $editUser->setLname($_POST['lname']);
              }
            }else{
              $error="Passwords did not match.";
            }
          }else{
            $error="Email is invalid.";
          }
        }else{
          $error="The name can only include alphabetic characters.";
        }
      }
    }else{
      /*error*/
      $error="An error occured while updating.";
    }
  }

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Edit User</title>
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
                <a class="dropdown-item active" href="settings.php">Settings</a>
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

      <h1>Edit User</h1>

      <p class="lead">Edit <?php echo $editUser->getEmail(); ?> account information here.</p>

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


      <form action="" method="post" class="mt-5">
        <div class="form-group row">
          <label for="fname" class="col-3 col-form-label">First Name</label>
          <div class="col-9">
            <input id="fname" name="fname" type="text" class="form-control here" value="<?php echo $editUser->getFname(); ?>" required="required">
          </div>
        </div>
        <div class="form-group row">
          <label for="lname" class="col-3 col-form-label">Last Name</label>
          <div class="col-9">
            <input id="lname" name="lname" type="text" class="form-control here" value="<?php echo $editUser->getLname(); ?>" required="required">
          </div>
        </div>
        <div class="form-group row">
          <label for="email" class="col-3 col-form-label">Email</label>
          <div class="col-9">
            <input id="email" name="email" type="email" class="form-control here" value="<?php echo $editUser->getEmail(); ?>" required="required">
          </div>
        </div>
        <div class="form-group row">
          <label for="pass" class="col-3 col-form-label">Password</label>
          <div class="col-9">
            <input id="pass" name="pass" type="password" aria-describedby="passHelpBlock" class="form-control here">
            <span id="passHelpBlock" class="form-text text-muted">Only enter a password here if you want to change it.</span>
          </div>
        </div>
        <div class="form-group row">
          <label for="passconf" class="col-3 col-form-label">Confirm Password</label>
          <div class="col-9">
            <input id="passconf" name="passconf" type="password" aria-describedby="passconfHelpBlock" class="form-control here">
            <span id="passconfHelpBlock" class="form-text text-muted">Only enter a password here if you want to change it.</span>
          </div>
        </div>
        <div class="form-group row mt-5">
          <div class="offset-3 col-9">
            <button name="submit" type="submit" class="btn btn-primary">Update</button>
          </div>
        </div>
      </form>

      <h2 class="mt-5">Participations</h2>
      <p class="lead">You cannot edit this.</p>

      <hr class="mt-5">
      <table id="gameAssign" class="table table-striped" style="width:100%;">
        <thead>
          <tr>
            <th></th>
            <th colspan="3">To be gifted</th>
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
            $part=getParticipations($conn,$_GET['id']);
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

      <h3 class="mt-5">Danger Zone</h3>
      <p class="lead">This action cannot be undone. Be careful.</p>
      <?php
        //check if user is participating in a game
        $conn=connectDB();
        $result=isUserFree($conn,$_GET['id']);
        $conn=null;

        if($result===true){ ?>
          <a href="removeUser.php?id=<?php echo $_GET['id']; ?>" class="btn btn-danger">Delete this user</a>
      <?php }else{ ?>
          <p class="text-danger">This user cannot be deleted. He is currently participating in one game or more.</p>
      <?php } ?>
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
