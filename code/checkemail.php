<?php
# email ajax function
  try
  {
    require('connection.php');

    $email = $_GET['email'];
    $sql = "SELECT COUNT(*) FROM user WHERE email = :email";
    
    $stmt = $db -> prepare($sql);
    $stmt -> bindParam(':email', $email);
    $stmt -> execute();
    
    $count = $stmt -> fetchColumn();

    if ($count > 0) 
    {
      echo "taken";
    } 
    else if ($count == 0) 
    {
      echo "free";
    }

    $db = null;
  } 
  catch (PDOException $ex) 
  {
    die($ex->getMessage());
  }
?>

