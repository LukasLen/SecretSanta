<?php

  /*    Database Settings
   *    Please enter the connection details to the database
   */
    
    // mysql || sqlite || pgsql
    $database_type = "mysql";

    // Mysql & Pgsql Database
    $db[$database_type]['host'] ="localhost";
    $db[$database_type]['database']="secretsanta";
    $db[$database_type]['username']="root";
    $db[$database_type]['password']="";
    
    // Sqlite Database
    $db[$database_type]['path'] = "";


  /*    SMTP Outgoing Mail Settings
   *    Please enter your mail credentials
   */
    //Set the hostname of the mail server
    $smtpMailOut = 'smtp.gmail.com';
    //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    $smtpMailOutPort = 587;
    //Set the encryption system to use - ssl (deprecated) or tls
    $smtpMailOutEncryption = 'tls';
    //Whether to use SMTP authentication
    $smtpMailOutAuth = true;
    //Username to use for SMTP authentication - use full email address for gmail
    $smtpMailOutUsername = "john.doe@gmail.com";
    //Password to use for SMTP authentication
    $smtpMailOutPassword = "*****";
    //Set where the emails are sent from
    $sendEmailFrom = "john.doe@gmail.com";


  /*    Mail Content Settings
   *    Here you can change the content of the emails
   */
   //Set how much money is allowed to be spent by participants
   $cost="10 Euro";
   //Set where the user can log in
   $loginLink="http://localhost/login.php";
   //Set the title of the email that will be sent on account creation and edit
   $accountEmailTitle="Your account on Secret Santa";
   //Set the text of the email that will be sent on account creation and edit - a text with email and password will automatically be appended
   $accountEmailText="The game administrator has created your account or made changes for you.";
   //Set the title of the email that will be sent on game creation to each user - the game name will automatically be appended
   $gameEmailTitle="Secret Santa Game";
   //Set the text of the email that will be sent on game creation to each user - a text with game name, the person that has to be gifted, and cost maximum will automatically be appended
   $gameEmailText="You are now participating in a Secret Santa Game. See the details below.";
