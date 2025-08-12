<?php
$servername = "localhost";
$username = "root";
$password = "";
$databse = "register";
//create a connection

$conn = mysqli_connect($servername,$username,$password,$databse);
if(!$conn){
    die("sorry we are not connect".mysqli_connect_error());
}
// else{
//     echo "connection successfully";
// }
?>