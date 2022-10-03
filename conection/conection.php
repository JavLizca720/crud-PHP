<?php
$host="localhost";
$bd="empresa";
$user="root";
$pass="12345";

try {
    $conn=new PDO("mysql:host=$host;dbname=$bd",$user,$pass);
    //if($conn){echo "Connection established";}
}catch( Exception $ex){
echo $ex->getMessage();
}
?>