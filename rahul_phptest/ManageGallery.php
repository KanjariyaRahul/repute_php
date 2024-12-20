<?php

        include 'connection.php';

        $sql = "SELECT * FROM image_data";
        $result = mysqli_query($conn, $sql)or die("Connection Failed");

        if(mysqli_num_rows($result) > 0 )
        {

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Gellery</title>
</head>
<body>
    
    <h1>Manage Gallery</h1>


    <?php

    while($row = mysqli_fetch_assoc($result))
    {


    ?>

    <table border="1">
        



        <tbody>
            <tr>
                <td>
                    <img src="sampleImages/ft.png" alt="" width:100px height=100px>
                </td>
            </tr>
            <tr>
                <td>
                    <h5><?php echo $row['img_name']; ?> </h5>
                </td>
            </tr>
         
        </tbody>

        

        <tfoot>
            <tr>
                <td>
                    <button ><a href="edit.php?id='<?php echo $row['image_id']; ?>'">Edit</a></button>
                    <button  id="delete-btn" ><a href="delete.php?id='<?php echo $row['image_id']; ?>'">delete </a></button>
                </td>
               
            </tr>
        </tfoot>

        <?php } ?>

    </table>


    <script>


             let del-btn = document.getElementById("delete-btn").addEventlistner(click,function(){

                alert("are you sure tou want to add data");

             })

            
             

    </script>


    <?php

        }
        else
        {
            echo "No record found";
        }

        mysqli_close($conn);

    ?>

</body>
</html>