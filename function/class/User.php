<?php
/*
 *    User Class
 */

 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\Exception;

 //Load Composer's autoloader
 require_once __DIR__.'\..\composer\vendor\autoload.php';

 //Load config file
 require_once __DIR__.'\..\..\config.php';

  class User{
    private $id;
    private $email;
    private $fname;
    private $lname;
    private $password;
    private $hasToBeGifted;

    function __construct($id, $email, $fname, $lname, $password){
      $this->id=$id;
      $this->email=$email;
      $this->fname=$fname;
      $this->lname=$lname;
      $this->password=$password;
    }

    public function getID(){
      return $this->id;
    }
    public function getEmail(){
      return $this->email;
    }
    public function getFname(){
      return $this->fname;
    }
    public function getLname(){
      return $this->lname;
    }
    public function getPassword(){
      return $this->password;
    }
    public function getHasToBeGifted(){
      return $this->hasToBeGifted;
    }
    public function setID($id){
      $this->id=$id;
    }
    public function setEmail($email){
      $this->email=$email;
    }
    public function setFname($fname){
      $this->fname=$fname;
    }
    public function setLname($lname){
      $this->lname=$lname;
    }
    public function setPassword($password){
      $this->password=$password;
    }
    public function setHasToBeGifted($hasToBeGifted){
      $this->hasToBeGifted=$hasToBeGifted;
    }

    public function checkUser(){
      //connect to database and pull recent information about the user
      $conn=connectDB();
      $user=getUser($conn, $this->email);
      $conn=null;

      //check if user still exists and update Information
      if($user!==false){
        $this->id=$user['ID'];
        $this->email=$user['email'];
        $this->fname=$user['fname'];
        $this->lname=$user['lname'];
        $this->password=$user['password'];

        //set session variable
        $_SESSION['user']=$this;

        //check if user is still admin and if not remove admin status
        if($user['admin']==true){
          $_SESSION['isAdmin']=true;
        }elseif(isset($_SESSION['isAdmin'])) {
          unset($_SESSION['isAdmin']);
        }

      }else{
        //if user does not exist anymore logout
        header("Location: ./logout.php");
      }
    }

    public function sendPWEmail(){
      //import from config file
      Global $smtpMailOut;
      Global $smtpMailOutPort;
      Global $smtpMailOutEncryption;
      Global $smtpMailOutAuth;
      Global $smtpMailOutUsername;
      Global $smtpMailOutPassword;
      Global $sendEmailFrom;
      Global $loginLink;
      Global $accountEmailTitle;
      Global $accountEmailText;

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

      //reciever
      $mail->addAddress($this->email, $this->fname .' '. $this->lname);

      //Message content
      $mail->Subject = $accountEmailTitle;
      $mail->msgHTML($accountEmailText."<br><br>You can log in with following details on $loginLink:<br><br>Your account: $this->email<br>Your password: $this->password");
      $mail->AltBody = $accountEmailText."\n\nYou can log in with following details on $loginLink:\n\nYour account: $this->email\nYour password: $this->password";

      //Error check
      if (!$mail->send()) {
        echo "Error trying to send email: " . $mail ->ErrorInfo;
      }
    }
  }
