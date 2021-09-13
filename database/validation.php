<?php

require __DIR__ .'\config.php';       //including the database connection

$error = $email = '';       //initializing error and email as empty variables
if(isset($_POST['submit']))     //POST method is used for email validation
{
    if(empty($_POST['email']))      //Checking if the email field is empty
    {
      $error = 'Email required...';     // if email field is empty then give an error message
    }
    else
    {
        $email = mysqli_real_escape_string($conn, $_POST['email']); //checking for malicious sql code
        $email = filter_var($email, FILTER_SANITIZE_EMAIL); //removing all illegal characters from the input

      if (!filter_var($email, FILTER_VALIDATE_EMAIL)) //checking if the email is valid
      {
        $error = 'Enter a valid email...';
      }
      else
      {
        $sql = 'SELECT * FROM users WHERE email = ? AND status = 1' ;      //sql query for checking if email already exists in database and is verified
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('s',$email);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        if(count($result)>0)      //if it returned more than 0 row then the email exists
        {
          $error = 'Email already subscribed...';       //Give an error message
        }
        else    //if the email is present in the database but it is not verified 
        {
          $sql = 'SELECT * FROM users WHERE email = ? AND status = 0' ;      //sql query for checking if email already exists in database and is not verified
          $stmt = $conn->prepare($sql);
          $stmt->bind_param('s',$email);
          $stmt->execute();
          $result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

          if(count($result)>0)      //if it returned more than 1 row then the email exists
          {
            $token = $result[0]['token'];
            require './mails/verify_mail.php';       //send a mail for verification 
          }

          else    //if the mail is not present in the database
          {
            $token = md5('$email').rand(10,9999);    //generate random value for email verification
            $utoken = md5($_POST['email'].$token).rand(10,9999);    //generate random value for unsubscribing
            $sql = 'INSERT INTO users (email,token,utoken) VALUES(?,?,?)';  //writing insert query
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('sss',$email,$token,$utoken);
            $stmt->execute();

            if($stmt) //checking if values succesfully inserted into the SQLite Database
            {
            require './mails/verify_mail.php';   //include verify_mail.php page
            }
            else
            {
                $error =  'query error'.mysqli_error($conn);      //printing error message if values not inserted successfully
            }
          }
        }
      }
    }
}
mysqli_close($conn);        //sql connection closed

?>
