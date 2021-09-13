<?php

    require __DIR__ .'/../database/secret.php';
    
    $to = $email;       //email of the users
    $subject = 'Email verification';        //subject of the email

    //body of the email

    if (isset($_SERVER["HTTPS"]) && $_SERVER['HTTPS'] == 'on') {
        $server = "https://" . $_SERVER["HTTP_HOST"];
    } else {
            $server = "http://" . $_SERVER["HTTP_HOST"];
    }
    
    $body = "Hi $email, Please click on the link below to confirm your email- id <br> $server/php-sefali229/database/activate.php?token=$token";  //body of the mail
    
    /* Using sendgrid api to send mail */
    $headers = array(
        "Authorization: Bearer $API_KEY",
        'Content-Type: application/json'
    );

    /* contents of the mail in json format */
    $data = array(
        'personalizations' => array(
            array(
                'to' => array(
                    array(
                        'email' => $to,         //receiver's email
                    )
                )
            )
        ),
        'from' => array(
            'email' => $from,     //sender's email
            'name' => 'XKCD Challenge'
        ),
        'subject' => $subject,      //subject
        'content' => array(
            array(
                'type' => 'text/html',      //body type
                'value' => $body        
            )
        )
    );
    $ch = curl_init();      
    curl_setopt($ch, CURLOPT_URL, 'https://api.sendgrid.com/v3/mail/send');     //sendgrid api
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));       //data is encoded in json format before sending
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);    //headers
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $response = curl_exec($ch);     //execute curl
    curl_close($ch);        //close curl

    if(!$response)      //if email is successfully sent
    {
        $error = 'An email is sent to your registered email-id. Please confirm';        //Tell the user to check their email address
    }
    else
    {
        $error =  $response;     //Show error if anything goes wrong
    }

?>