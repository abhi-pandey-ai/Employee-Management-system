<?php
include 'dbconn.php';
$id=$_GET['id'];
$query = "DELETE FROM forms WHERE id='$id'";
$data = mysqli_query($conn,$query);
if($data){
    echo "delete successfully";
    
}else{
    echo "this data not deleted please tryagain";
}
?>
