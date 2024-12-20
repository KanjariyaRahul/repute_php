<?php

    include 'connection.php';


    echo $id = $_POST['img_id'];
    
    $image_Name = $_POST['IName'];
    $Image_desc = $_POST['ImageDecs'];
    // $checkbox = $_POST['category'];
    // $img = $_FILES['Image'];
    //  $radio_button = $_POST['status'];



    $sql = "UPDATE image_data SET img_name='{$image_Name}',imageDesc='{$Image_desc}' WHERE image_id='{$id}' ";

    $result =mysqli_query($conn,$sql)or die("Query Failed");

    
    header("Location:http://localhost/rahul_phptest/ManageGallery.php");


?>