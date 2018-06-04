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
    $conn=null;
  }

  //if game does not exist go to admin panel
  if($editGame===false){
    header("Location: ./admin.php");
  }

  $conn=connectDB();
  dropGame($conn, $_GET['id']);
  $conn=null;

  header("Location: ./admin.php");
