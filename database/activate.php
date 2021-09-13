<?php

    require __DIR__ .'\secret.php';
    require __DIR__ .'\config.php';
    

    if(isset($_GET['token']))       //if the link is clicked
    {
        $token = $conn->real_escape_string($_GET['token']);        //get the token from the user

        $stmt = $conn->prepare('UPDATE users SET status = 1 WHERE token = ? ');   //query to change status to 1 wherever token is matching
        $stmt->bind_param('s', $token);

        if($stmt->execute())     //run query
        {
            header('Location: ../verified.html');      //goto verified.html 
            exit('successful');
        }
        else
        {
            echo 'something went wrong';        //print error if query not successful
        }
    }

?>