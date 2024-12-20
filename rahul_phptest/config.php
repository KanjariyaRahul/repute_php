<?php

$host = "localhost";
$username = "root";
$password = "";
$dbname = "rahul_phptest"; 

$conn = mysqli_connect($host, $username , $password, $dbname);

if(!$conn)
{
    echo "connection error : ". mysqli_connect_error($conn);
}

 ?>