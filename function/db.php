<?php
  require_once __DIR__.'\class\User.php';
  require_once __DIR__.'\class\Game.php';
  //Load config file
  require_once __DIR__.'\..\config.php';

/*
*   Connect to Databse
*/
  function connectDB(){
    //database connection credentials
    global $db_servername;
    global $db_dbname;
    global $db_username;
    global $db_password;

    //try to connect to the database - throw error if unsuccessful
    try {
      $conn = new PDO("mysql:host=$db_servername;dbname=$db_dbname", $db_username, $db_password);

      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      return $conn;
    }catch(PDOException $e){
      die("Database error");
    }
  }

/*
*   Get USER information
*/
  function getUser($conn, $email){
    // prepare sql and bind parameters
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=:email");
    $stmt->bindParam(':email', $email);

    try{
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if(count($result)==0){
        return false;
      }else{
        return $result[0];
      }
    }catch(PDOException $e){
      return false;
    }
  }
/*
*   Get USER information
*/
  function getUserByID($conn, $id){
    // prepare sql and bind parameters
    $stmt = $conn->prepare("SELECT * FROM users WHERE id=:id");
    $stmt->bindParam(':id', $id);

    try{
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if(count($result)==0){
        return false;
      }else{
        return $result[0];
      }
    }catch(PDOException $e){
      return false;
    }
  }
/*
*   Get ALL USER information
*/
  function getAllUsersInfo($conn){
    // prepare sql and bind parameters
    $stmt = $conn->prepare("SELECT * FROM users");
    $stmt->bindParam(':email', $email);

    try{
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if(count($result)==0){
        return false;
      }else{
        return $result;
      }
    }catch(PDOException $e){
      return false;
    }
  }
/*
*   Update USER information
*/
  function updateUser($conn, $user, $userNew){
    // prepare sql and bind parameterss
    $stmt = $conn->prepare("UPDATE users SET fname = :fname, lname = :lname, email = :email, password = :password WHERE id = :id");
    $fname=$userNew->getFname();
    $lname=$userNew->getLname();
    $email=$userNew->getEmail();
    $password=$userNew->getPassword();
    $id=$user->getID();
    $stmt->bindParam(':fname', $fname);
    $stmt->bindParam(':lname', $lname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':id', $id);

    try{
      $stmt->execute();
      $result = $stmt->rowCount();

      if($result==0){
        return false;
      }else{
        return true;
      }
    }catch(PDOException $e){
      //email already in use
      if($e->getCode()==23000){
        return 2;
      }
      return false;
    }
  }
/*
*   CREATE new USER
*/
  function createUser($conn, $userNew){
    // prepare sql and bind parameterss
    $stmt = $conn->prepare("insert into users (email, fname, lname, password) values (:email, :fname, :lname, :password)");
    $fname=$userNew->getFname();
    $lname=$userNew->getLname();
    $email=$userNew->getEmail();
    $password=$userNew->getPassword();
    $stmt->bindParam(':fname', $fname);
    $stmt->bindParam(':lname', $lname);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);

    try{
      $stmt->execute();
      $result = $stmt->rowCount();

      if($result==0){
        return false;
      }else{
        return true;
      }
    }catch(PDOException $e){
      //email already in use
      if($e->getCode()==23000){
        return 2;
      }
      return false;
    }
  }
/*
*   Test if user is free
*/
  function isUserFree($conn, $id){
    // prepare sql and bind parameters
    $stmt = $conn->prepare("SELECT * FROM assignment WHERE users_ID_from=:id OR users_ID_to=:id;");
    $stmt->bindParam(':id', $id);

    try{
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if(count($result)==0){
        return true;
      }else{
        return false;
      }
    }catch(PDOException $e){
      return false;
    }
  }
/*
*   remove user
*/
  function dropUser($conn, $id){
    // prepare sql and bind parameterss
    $stmt = $conn->prepare("DELETE FROM users WHERE ID=:id");
    $stmt->bindParam(':id', $id);

    try{
      $stmt->execute();
      $result = $stmt->rowCount();

      if($result==0){
        return false;
      }else{
        return true;
      }
    }catch(PDOException $e){
      return false;
    }
  }
/*
*   Get all GAMES information
*/
  function getAllGames($conn){
    // prepare sql
    $stmt = $conn->prepare("SELECT * FROM games");

    try{
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if(count($result)==0){
        return false;
      }else{
        return $result;
      }
    }catch(PDOException $e){
      return false;
    }
  }
/*
*   Get info about game through game id
*/
  function getGameInfo($conn, $id){
    // prepare sql
    $stmt = $conn->prepare("SELECT users_id_to   AS toid,
                                   email         AS toemail,
                                   fname         AS tofname,
                                   lname         AS tolname,
                                   users_id_from AS fromid,
                                   fromemail,
                                   fromfname,
                                   fromlname
                            FROM   (
                                          SELECT users_id_from,
                                                 users_id_to,
                                                 email AS fromemail,
                                                 fname AS fromfname,
                                                 lname AS fromlname
                                          FROM   assignment
                                          JOIN   users
                                          where  users_id_from=id
                                          AND    games_id=:id) t
                            JOIN   users
                            WHERE  t.users_id_to=id");

    $stmt->bindParam(':id',$id);

    try{
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if(count($result)==0){
        return false;
      }else{
        return $result;
      }
    }catch(PDOException $e){
      return false;
    }
  }
/*
*   Get gamename
*/
  function getGameName($conn, $id){
    // prepare sql
    $stmt = $conn->prepare("SELECT name FROM games where ID=:id");
    $stmt->bindParam(':id',$id);

    try{
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if(count($result)==0){
        return false;
      }else{
        return $result[0];
      }
    }catch(PDOException $e){
      return false;
    }
  }
/*
*   Update game name
*/
  function updateGameName($conn, $id, $name){
    // prepare sql and bind parameterss
    $stmt = $conn->prepare("UPDATE games SET name = :name WHERE ID = :id");
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':name', $name);

    try{
      $stmt->execute();
      $result = $stmt->rowCount();

      if($result==0){
        return false;
      }else{
        return true;
      }
    }catch(PDOException $e){
      return false;
    }
  }
/*
*   Get participations
*/
  function getParticipations($conn, $id){
    // prepare sql
    $stmt = $conn->prepare("SELECT name as gamename, email as toemail, fname as tofname, lname as tolname FROM assignment join games,users where games_ID=games.ID and users_ID_from=:id and users_ID_to=users.ID");
    $stmt->bindParam(':id',$id);

    try{
      $stmt->execute();
      $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if(count($result)==0){
        return false;
      }else{
        return $result;
      }
    }catch(PDOException $e){
      return false;
    }
  }
/*
*   add new GAME
*/
  function createGame($conn, $game){
    // prepare sql
    try{
      // begin the transaction
      $conn->beginTransaction();
      //SQL statements
      $conn->exec("insert into games (name) values ('".$game->getName()."')");
      $gameID=$conn->lastInsertId();
      foreach ($game->getUsers() as $current) {
        $conn->exec("insert into assignment values ('$gameID', '".$current->getID()."', '".$current->getHasToBeGifted()->getID()."')");
      }

      // commit the transaction
      $conn->commit();
      return true;
    }catch(PDOException $e){
      // roll back the transaction if something failed
      $conn->rollback();
      return false;
    }
  }
/*
*   remove game
*/
  function dropGame($conn, $id){
    // prepare sql and bind parameterss
    $stmt = $conn->prepare("DELETE FROM games WHERE ID=:id");
    $stmt->bindParam(':id', $id);

    try{
      $stmt->execute();
      $result = $stmt->rowCount();

      if($result==0){
        return false;
      }else{
        return true;
      }
    }catch(PDOException $e){
      return false;
    }
  }
