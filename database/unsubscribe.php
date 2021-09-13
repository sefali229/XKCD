<?php

    require __DIR__ .'\secret.php';
    require __DIR__ .'\config.php';
    

    if(isset($_GET['id']))       //if the link is clicked
    {
        $utoken = $conn->real_escape_string($_GET['id']);        //get the token from the user

        $stmt = $conn->prepare('DELETE FROM users WHERE utoken = ?');   //query to delete the user from the token wherever id matches.
        $stmt->bind_param('s', $utoken);

        if($stmt->execute())     //run query
        {
            header('Location: ../unsubscribe.html');      //goto unsubscribe.html
            exit('successful');
        }
        else
        {
            echo 'something went wrong';    //print error if query not successful
        }
    }

?>