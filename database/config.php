<?php

require __DIR__ .'\secret.php';

$conn = mysqli_connect($dbhost,$dbusername,$dbpassword,$dbname); //connection created
if(!$conn)  //checking if connection successful
{
  die('Connection error'.mysqli_connect_error()); //printing error message if connection not successful
}

