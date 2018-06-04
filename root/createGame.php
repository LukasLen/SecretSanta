<?php
  require '../function/class/User.php';
  require '../function/class/Game.php';
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

  //on form submit
  if(isset($_POST['gamename'], $_POST['existing'])){
    //check for empty values and if at least 3 users are selected
    if(!empty($_POST['gamename']) && !empty($_POST['existing']) && count($_POST['existing'])>2){
      //only aplhanumeric and spaces in game name
      if(ctype_alnum(str_replace(" ","",$_POST['gamename']))){
        //assign users to eachother
        // Shuffle the array
        shuffle($_POST['existing']);
        // change the order of the elements
        for($i=0;$i<count($_POST['existing'])-1;$i++){
          $shuffled[$i+1]=$_POST['existing'][$i];
        }
        $shuffled[0]=$_POST['existing'][count($_POST['existing'])-1];

        $conn=connectDB();
        $users=getAllUsersInfo($conn);
        $conn=null;

        if($users!==false){
          $game=new Game(null, $_POST['gamename']);
            //go through each participant
          	for ($i=0; $i < count($_POST['existing']); $i++) {
              //search in the array of all users for the participants and get the array index
              $idExisting=array_search($_POST['existing'][$i], array_column($users, 'ID'));
              $idShuffled=array_search($shuffled[$i], array_column($users, 'ID'));
              //create a temporary user and add the user that has to be gifted
              $tmpUser=new User($users[$idExisting]['ID'], $users[$idExisting]['email'], $users[$idExisting]['fname'], $users[$idExisting]['lname'], null);
              $tmpUser->setHasToBeGifted(new User($users[$idShuffled]['ID'], $users[$idShuffled]['email'], $users[$idShuffled]['fname'], $users[$idShuffled]['lname'], null));
              //add the user to the game
              $game->addUser($tmpUser);
            }

            //store game in database
            $conn=connectDB();
            $result=createGame($conn, $game);
            $conn=null;

            if($result===true){
              $game->sendInfoToUsers();
              $success="Game ".$game->getName()." created successfully.";
            }else {
              $error="An error occured while creating the game.";
            }
        }else{
          $error="No users.";
        }
      }else{
        $error="The game name can only be alphanumeric.";
      }
    }else{
      $error="Please select at least 3 users.";
    }
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Create Game</title>
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

      <h1>Create Game</h1>

      <p class="lead">Please enter all the necessary information.</p>

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

      <form action="" method="post">
        <div class="form-group row mt-5">
          <label class="col-3 col-form-label" for="gamename">Game Name</label>
          <div class="col-9">
            <input id="gamename" name="gamename" type="text" class="form-control here" <?php if(isset($error)) echo "value=\"".$_POST['gamename']."\""; ?> required="required">
          </div>
        </div>
        <div class="form-group row">
          <label class="col-3 col-form-label" for="existing">Select From Users</label>
          <div class="col-9">
            <select multiple="multiple" id="existing" name="existing[]" class="custom-select" aria-describedby="existingHelpBlock" style="height:300px" required="required">
              <?php
                $conn=connectDB();
                $users=getAllUsersInfo($conn);
                $conn=null;
                if($users===false){
                  echo "<option disabled=\"disabled\">No users found.</option>";
                }else{
                  array_multisort(array_column($users, 'fname'), SORT_ASC, $users);
                  foreach ($users as $current){
                    echo "<option value=\"".$current['ID']."\"";
                    if(isset($error, $_POST['existing']) && in_array($current['ID'],$_POST['existing'])){
                      echo "selected=\"selected\"";
                    }
                    echo ">".$current['fname']." ".$current['lname']." [".$current['email']."]";
                    if($current['ID']==$user->getID()){
                      echo " (your account)";
                    }
                    echo "</option>";
                  }
                }
              ?>
            </select>
            <span id="existingHelpBlock" class="form-text text-muted">Hold ctrl to select multiple users. A minimum of 3 users is required.</span>
          </div>
        </div>
        <div class="form-group row">
          <label class="col-3"></label>
          <div class="col-9">
            <span id="textHelpBlock" class="form-text text-muted"><b>Add Users:</b> <a href="createUser.php">Create new users here</a> and then come back to this page.</span>
          </div>
        </div>
        <div class="form-group row mt-4">
          <div class="offset-3 col-9">
            <button name="submit" type="submit" class="btn btn-primary">Create Game</button>
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
