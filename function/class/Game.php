<?php
/*
 *    Game Class
 */

 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\Exception;

 //Load Composer's autoloader
 require_once __DIR__.'\..\composer\vendor\autoload.php';

 //Load User class
 require_once __DIR__.'\User.php';

 //Load config file
 require_once __DIR__.'\..\..\config.php';

  class Game{
    private $id;
    private $name;
    private $users; //User array

    function __construct($id, $name){
      $this->id=$id;
      $this->name=$name;
    }

    public function getID(){
      return $this->id;
    }
    public function getName(){
      return $this->name;
    }
    public function getUsers(){
      return $this->users;
    }
    public function setID($id){
      $this->id=$id;
    }
    public function setName($name){
      $this->name=$name;
    }
    public function addUser($user){
      $this->users[]=$user;
    }
    public function sendInfoToUsers(){
      //import from config file
      Global $smtpMailOut;
      Global $smtpMailOutPort;
      Global $smtpMailOutEncryption;
      Global $smtpMailOutAuth;
      Global $smtpMailOutUsername;
      Global $smtpMailOutPassword;
      Global $sendEmailFrom;
      Global $gameEmailTitle;
      Global $gameEmailText;
      Global $cost;

      //start PHPMailer
      $mail = new PHPMailer(true);

      //basic PHPMailer config
      $mail->isSMTP();
      $mail->SMTPDebug = 0;
      $mail->Host = $smtpMailOut;

      $mail->Port = $smtpMailOutPort;
      $mail->SMTPSecure = $smtpMailOutEncryption;
      $mail->SMTPAuth = $smtpMailOutAuth;

      $mail->Username = $smtpMailOutUsername;
      $mail->Password = $smtpMailOutPassword;

      //transmitter
      $mail->setFrom($sendEmailFrom, 'Secret Santa');

      //Set subject (won't change)
      $mail->Subject = $gameEmailTitle." ".$this->name;

      foreach ($this->users as $current) {
        //reciever
        $mail->addAddress($current->getEmail(), $current->getFname() .' '. $current->getLname());

        $mail->msgHTML($gameEmailText."<br><br>You have to make a gift to ".$current->getHasToBeGifted()->getFname()." ".$current->getHasToBeGifted()->getLname().".<br>The maximum amount you can spend is ".$cost.".");
        $mail->AltBody = $gameEmailText."\n\nYou have to make a gift to ".$current->getHasToBeGifted()->getFname()." ".$current->getHasToBeGifted()->getLname().".\nThe maximum amount you can spend is ".$cost.".";

        //Error check
        if (!$mail->send()) {
          echo "Error trying to send email: " . $mail ->ErrorInfo;
        }

        $mail->ClearAllRecipients();
      }
    }
  }
