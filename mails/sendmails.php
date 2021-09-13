
<?php

require __DIR__ .'/../curl/imageDownload.php';
require __DIR__ .'/../database/config.php';
require __DIR__ .'/../database/secret.php';

$var = 1;

$stmt = $conn->prepare('SELECT email,utoken FROM users WHERE status = ?');      //getting the email of users whose status = 1
$stmt->bind_param('i', $var);
$stmt->execute();
$result = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$res = count($result);
$i = 0;

if($res>0)  //if there is 1 or more verified email
{
    while($i<$res)  //get rows one by one
    {
        $email = $result[$i]['email']; //fetch emails from each row
    
        $img = file_get_contents('image.jpg');
        $utoken = $result[$i]['utoken'];


        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $server = 'https://' . $_SERVER['HTTP_HOST'];
        } else {
                $server = 'http://' . $_SERVER['HTTP_HOST'];
        }

        //body of the email
        $body = "<img src = 'cid:image'><br><br><br>
        <p>To unsubscribe click <a href = $server/php-sefali229/database/unsubscribe.php?id=$utoken style='position'>here</a></p>";
        $subject = $imageTitle;     //subject
        
            

        $headers = array(
            "Authorization: Bearer $API_KEY",
            'Content-Type: application/json'
        );

        $data = array(
            'personalizations' => array(
                array(
                    'to' => array(
                        array(
                            'email' => $email
                        )
                    )
                )
            ),
            'from' => array(
                'email' => $from,
                'name' => 'XKCD Challenge'
            ),
            'subject' => $subject,
            'content' => array(
                array(
                    'type' => 'text/html',
                    'value' => $body
                )
            ),

            'attachments' => array(         
                array(
                    'content' => base64_encode($img),   //inline image
                    'type' => 'image/jpeg',
                    'filename' => 'image',
                    'disposition' => 'inline',
                    'content_ID' => 'image'    
                ),
                array(
                    'content' => base64_encode($img),   //image as attachment
                    'type' => 'image/jpeg',
                    'filename' => 'image',
                    'disposition' => 'attachment',
                )
            )         
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://api.sendgrid.com/v3/mail/send');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));   //encode the data in json format
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);

        echo $response;

        $i++;
    }
}
?>