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

  //check if user is participating in a game
  $conn=connectDB();
  $result=isUserFree($conn,$_GET['id']);
  $conn=null;

  if($result===true){
    $conn=connectDB();
    dropUser($conn, $_GET['id']);
    $conn=null;
  }

  header("Location: ./admin.php");
