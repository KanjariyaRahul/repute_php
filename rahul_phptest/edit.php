<?php


    include 'connection.php';

    $uid =$_GET['id'];

    $sql = "SELECT * FROM  image_data WHERE  image_id ={$uid} ";
    $result = mysqli_query($conn,$sql)or die("Query failed at this time");

    if(mysqli_num_rows($result) > 0)
    {
       

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Galery </title>
</head>
<body>

        <h1>Edit Gallery</h1>
        <form action="Update_data.php"  method='post'  enctype="multipart/form-data">

        <?php

             while($row=mysqli_fetch_assoc($result))
             {
     
        ?>

        
        <input type="hidden" name="img_id" id="imgid" Placeholder=""   value="<?php echo $row['image_id']?>"> <br><br>
        <input type="text" name="IName" id="imgName" Placeholder="Enter the Image Name"   value="<?php echo $row['img_name']?>"> <br><br>

        <label for="">Image Description:
        <textarea name="ImageDecs" id="img-desc" cols="30" rows="10" placeholder="Enter description about Image" value="<?php echo $row['imageDesc']?>"></textarea>
        </label> <br> <br>



        <input type="file" name="Image"  id="image-id" >

        <br> <br>
        <label for="">
        <input type="checkbox" name="category" classname= "category">Natural
        </label><br>
        <label for="">
        <input type="checkbox" name="category" classname= "category">Books
        </label><br>
        <label for="">
        <input type="checkbox" name="category" classname= "category">movie
        </label><br>
        <label for="">
        <input type="checkbox" name="category" classname= "category">Natural
        </label><br><br>


        
        <input type="radio" name="status" class="radio"><p>Active</p>
        <input type="radio" name="status" class="radio"><p>Deactive</p>

        <button type="submit" name="save" onclick="isnull()">Update Data</button>    


        </form>

    <?php

        }
    }

?>

</body>
</html>