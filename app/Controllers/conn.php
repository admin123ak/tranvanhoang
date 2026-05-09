<?php

$servername = "localhost";
$username = "hcloucom_db1";
$password = "hcloucom_db1";
$dbname = "hcloucom_db1";

$conn = mysqli_connect($servername,$username,$password,$dbname);

if(!$conn) {

die(" PROBLEM WITH CONNECTION : " . mysqli_connect_error());

}

?>