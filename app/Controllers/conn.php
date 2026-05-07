<?php

$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database";

$conn = mysqli_connect($servername,$username,$password,$dbname);

if(!$conn) {

die(" PROBLEM WITH CONNECTION : " . mysqli_connect_error());

}
  
?>