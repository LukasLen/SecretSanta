<?php
  require '../function/class/User.php';
  require '../function/db.php';
  session_start();

  //check if user is logged
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
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Admin</title>
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
            <li class="nav-item active">
              <a class="nav-link" href="admin.php">Admin <span class="sr-only">(current)</span></a>
            </li>
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

      <h1>Admin</h1>
      <p class="lead" id="games">Manage your games and users.</p>

      <h2 class="mt-5 ml-2">Games</h2>
      <p class="lead mb-4 ml-2">These are all existing games.</p>

      <hr>
      <table id="gamesList" class="table table-striped" style="width:100%;">
        <thead>
          <tr>
            <th>Name</th>
            <th class="no-sort">Actions</th>
          </tr>
        </thead>
        <tfoot style="display: table-row-group"><tr class="no-sort"><td rowspan="1"><a href="createGame.php">Create new game</a></td><td></td></tr></tfoot>
        <tbody>
          <?php

            //print our all games with correlating options
            $conn=connectDB();
            $games=getAllGames($conn);
            $conn=null;

            if($games!==false){
              foreach ($games as $game) {
                echo "<tr>\n<td class=\"col-10\">\n";
                echo $game['name'];
                echo "\n</td>\n<td class=\"col-1\">";
                echo "<a href=\"editGame.php?id=".$game['ID']."\" class=\"ml-auto\">";
                  echo '<svg id="i-edit" viewBox="0 0 32 32" width="22" height="22" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                          <path d="M30 7 L25 2 5 22 3 29 10 27 Z M21 6 L26 11 Z M5 22 L10 27 Z" />
                        </svg>
                      </a>';
                echo "<a href=\"downloadGame.php?id=".$game['ID']."\" class=\"ml-auto\">";
                  echo '<svg id="i-download" viewBox="0 0 32 32" width="22" height="22" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                          <path d="M9 22 C0 23 1 12 9 13 6 2 23 2 22 10 32 7 32 23 23 22 M11 26 L16 30 21 26 M16 16 L16 30" />
                        </svg>
                      </a>';
                echo "\n</td>\n</tr>";
              }
            }

          ?>
        </tbody>
      </table>
      <hr>

      <h2 class="mt-5 ml-2" id="users">Users</h2>
      <p class="lead mb-4 ml-2">These are all existing users.</p>

      <hr>
      <table id="usersList" class="table table-striped" style="width:100%;">
        <thead>
          <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th class="no-sort">Actions</th>
          </tr>
        </thead>
        <tfoot style="display: table-row-group"><tr class="no-sort"><td rowspan="1"><a href="createUser.php">Create new user</a></td><td></td><td></td><td></td></tr></tfoot>
        <tbody>
          <?php
            //print out all users
            $conn=connectDB();
            $users=getAllUsersInfo($conn);
            $conn=null;

            if($users!==false){
              foreach ($users as $current) {
                echo "<tr>\n<td>";
                echo $current['fname'];
                echo "\n</td>\n<td>";
                echo $current['lname'];
                echo "\n</td>\n<td>";
                echo $current['email'];
                      if($current['ID']==$user->getID()){
                        echo " (your account)";
                      }
                echo "\n</td>\n<td>";
                echo "<a href=\"editUser.php?id=".$current['ID']."\" class=\"ml-auto\">";
                  echo '<svg id="i-edit" viewBox="0 0 32 32" width="22" height="22" fill="none" stroke="currentcolor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                          <path d="M30 7 L25 2 5 22 3 29 10 27 Z M21 6 L26 11 Z M5 22 L10 27 Z" />
                        </svg>
                      </a>';
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
        $('#gamesList').DataTable( {
          responsive: true,
          ordering: true,
          columnDefs: [{
            orderable: false,
            targets: "no-sort"
          }]
        });
        $('#usersList').DataTable( {
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
