<?php

  require_once __DIR__.'\..\function\composer\vendor\autoload.php';

  use PhpOffice\PhpSpreadsheet\Spreadsheet;
  use PhpOffice\PhpSpreadsheet\PHPExcel_Shared_Font;
  use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

  //establishes a connection to the Database
  $conn=connectDB();
  $currentGameName = getGameName($conn,$_GET['id']);
  $currentGameInfo = getGameInfo($conn, $_GET['id']);
  $conn=null;

  if($currentGameInfo!==false && $currentGameName!==false){
    $currentGameName = $currentGameName['name'];
    $currentUserNumber = count($currentGameInfo);

    //creates a new Spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $spreadsheet->getActiveSheet()->setTitle("Assignment");

    // Create a new worksheet called "Participants"
    $participantsSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Participants');
    // Attach the "Participants" worksheet as the first worksheet in the Spreadsheet object
    $spreadsheet->addSheet($participantsSheet, 0);

    //first worksheet
    $currentUser = 2;
    $participantsSheet->setCellValue('A1',"ID");
    $participantsSheet->setCellValue('B1',"First Name");
    $participantsSheet->setCellValue('C1',"Last Name");
    $participantsSheet->setCellValue('D1',"Email");
    for($row = 0; $row < $currentUserNumber; $row++){

      //set id
      $participantsSheet->setCellValue('A'.$currentUser,$currentGameInfo[$row]['fromid']);
      //set firstname
      $participantsSheet->setCellValue('B'.$currentUser,$currentGameInfo[$row]['fromfname']);
      //set lastname
      $participantsSheet->setCellValue('C'.$currentUser,$currentGameInfo[$row]['fromlname']);
      //set email
      $participantsSheet->setCellValue('D'.$currentUser,$currentGameInfo[$row]['fromemail']);

      $currentUser++;
    }
    $participantsSheet->getColumnDimension('A')->setAutoSize(true);
    $participantsSheet->getColumnDimension('B')->setAutoSize(true);
    $participantsSheet->getColumnDimension('C')->setAutoSize(true);
    $participantsSheet->getColumnDimension('D')->setAutoSize(true);

    //second worksheet
    $currentUser = 3;
    $sheet->setCellValue('A1',"From");
    $sheet->setCellValue('E1',"To");
    $sheet->mergeCells('A1:D1');
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $sheet->mergeCells('E1:H1');
    $sheet->getStyle('E1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    //set from
    $sheet->setCellValue('A2',"ID");
    $sheet->setCellValue('B2',"First Name");
    $sheet->setCellValue('C2',"Last Name");
    $sheet->setCellValue('D2',"Email");
    //set to
    $sheet->setCellValue('E2',"ID");
    $sheet->setCellValue('F2',"First Name");
    $sheet->setCellValue('G2',"Last Name");
    $sheet->setCellValue('H2',"Email");
    for ($row = 0; $row < $currentUserNumber; $row++) {
      //set from
      $sheet->setCellValue('A'.$currentUser,$currentGameInfo[$row]['fromid']);
      $sheet->setCellValue('B'.$currentUser,$currentGameInfo[$row]['fromfname']);
      $sheet->setCellValue('C'.$currentUser,$currentGameInfo[$row]['fromlname']);
      $sheet->setCellValue('D'.$currentUser,$currentGameInfo[$row]['fromemail']);

      //set to
      $sheet->setCellValue('E'.$currentUser,$currentGameInfo[$row]['toid']);
      $sheet->setCellValue('F'.$currentUser,$currentGameInfo[$row]['tofname']);
      $sheet->setCellValue('G'.$currentUser,$currentGameInfo[$row]['tolname']);
      $sheet->setCellValue('H'.$currentUser,$currentGameInfo[$row]['toemail']);

      //next user
      $currentUser++;
    }
    $sheet->getColumnDimension('A')->setAutoSize(true);
    $sheet->getColumnDimension('B')->setAutoSize(true);
    $sheet->getColumnDimension('C')->setAutoSize(true);
    $sheet->getColumnDimension('D')->setAutoSize(true);
    $sheet->getColumnDimension('E')->setAutoSize(true);
    $sheet->getColumnDimension('F')->setAutoSize(true);
    $sheet->getColumnDimension('G')->setAutoSize(true);
    $sheet->getColumnDimension('H')->setAutoSize(true);

    //creates a new spreadsheet
    $writer = new Xlsx($spreadsheet);

    //defines the gameName as filename
    header("Content-type: text/plain");
    header("Content-Disposition: attachment; filename=".date("Y-m-d")." Secret Santa Game $currentGameName.xlsx");

    //write to php output
    $writer->save("php://output");

    //free memory
    $spreadsheet->disconnectWorksheets();
    unset($spreadsheet);
    exit;
  }
  header("Location: .\admin.php");
