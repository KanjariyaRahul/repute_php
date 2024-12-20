
<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

   
    $query = "SELECT profilepic FROM user WHERE id = '$id'";

    $result = mysqli_query($conn, $query);

    if(!$result)
    {
           die("Error fetching image data: " . mysqli_error($conn));
    }
    
    if(mysqli_num_rows($result)>0){
        $row = mysqli_fetch_assoc($result);

        $imagePath = './uploads/' . $row['profilepic'];
        if(file_exists($imagePath))
        {
            unlink($imagePath);
        }
    }

    $query = "DELETE FROM user WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    session_start();
    if ($result) {
         $message = "<p style='color: white; text-align: center;'>Record deleted successfully!</p>";
        header("Location: manageusers1.php?message= ".urlencode($message));
        exit();
    } else {   
        header("Location: manageusers1.php?message=Error+deleting+user.");
        exit();
    }
}
?>
