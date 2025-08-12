<?php
    include 'dbconn.php';
    $id = $_GET['id'];
    $select = "SELECT * FROM forms WHERE id ='$id'";
    $data = mysqli_query($conn,$select);
    $row = mysqli_fetch_array($data);
?>

    <div>
      <form action="" method = POST>
      Id:  <input value = "<?php echo $row['id']?>" type="number" name = "id" ><br><br>

       Name:  <input value = "<?php echo $row['name']?>" type="text" name = "name" ><br><br>

         Email: <input value = "<?php echo $row['email']?>" type="eail" name = "email" ><br><br>

           Password: <input value = "<?php echo $row['password']?>" type="number" name = "password" ><br><br>

           <input type="submit"  name = "update_btn" value = "submit">           
      </form>
    </div>

      <?php
    if(isset($_POST['update_btn'])){
        $id = $_POST['id'];
        $name =  $_POST['name'];
        $email = $_POST['email'];
        $password =  $_POST['password'];

      $update = "UPDATE forms SET id = '$id', name = '$name', email = '$email', password = '$password' WHERE id = '$id'";


        $alldata = mysqli_query($conn,$update);
        
        // alert dekhane ke liye likhaa hai if or else dono
        if($alldata){
           echo '<div style="max-width: 400px;" class="alert alert-success m-2">data updated successfully.</div>';
        }else{
          echo "plese try again leter";
        }
        
    }

    ?> 
    <button><a href="dashboard.php">HOME PAGE</a></button>