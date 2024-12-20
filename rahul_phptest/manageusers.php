<?php
include 'config.php'; 
session_start();

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; 
$offset = ($page - 1) * $limit;

$query = "SELECT * FROM user LIMIT $limit OFFSET $offset";
$execut = mysqli_query($conn, $query);

$total_query = "SELECT COUNT(*) AS total FROM user";
$total_result = mysqli_query($conn, $total_query);

$total_data = mysqli_fetch_assoc($total_result);

$total_users = $total_data['total'];
$total_pages = ceil($total_users / $limit);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <style>
        body {
         
            font-family: Arial, sans-serif;
        }

        .header-container {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            margin-top: 20px;
            margin-bottom: 15px;
        }

        h2 {
            margin: 0;
            text-align: center;
            flex-grow: 0.7;
        }

        .btn-add {
            background-color: rgb(28, 116, 184);
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }

        table {
            width: 100%;
            margin: 0 auto;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        td img {
            width: 80px;
            height: 80px;
        }

        .btn-update {
            background-color: rgb(243, 184, 57);
            display: inline-block;
            padding: 10px 20px;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-update:hover {
            background-color: rgb(250, 217, 96);
        }

        .btn-delete {
            background-color: rgb(255, 0, 0);
            display: inline-block;
            padding: 10px 20px;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-delete:hover {
            background-color: rgb(246, 82, 82);
        }

        .pagination {
            margin-top: 20px;
            text-align: center;
        }

        .pagination a {
            margin: 0 5px;
            padding: 5px 10px;
            background-color: rgb(28, 116, 184);
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: rgb(69, 152, 160);
        }

        .pagination a.disabled {
            background-color: #ccc;
            pointer-events: none;
        }

        .pagination a.active {
            background-color: rgb(28, 116, 184);
        }
          #message {
            width: 50%;
            background-color: #dff0d8;
            color: #3c763d;
            padding: 10px;
            margin: 20px;
            border: 1px solid #d6e9c6;
            border-radius: 4px;
        }

        .success {
            color: green;
        }

        .error {
            color: #f44336;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    </style>
    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this record?");
        }
         window.onload = function() {
            var messageDiv = document.getElementById("message");
            if (messageDiv) {
            
                setTimeout(function() {
                    messageDiv.style.display = "none"; 
                }, 2000); 
            }
        };
    </script>
</head>
<body>
    <div class="header-container">
        <h2>Manage Users</h2>
        <a href="./index.php" class="btn-add">Add Data</a>
    </div>

    <div class="container">
        <?php
        if (isset($_GET['message'])) {
            $message = $_GET['message'];
            if (strpos($message, 'Error') !== false) {
                $message_class = 'error';
            } else {
                $message_class = 'success';
            }
            echo "<div class='message  $message_class' id='message'>$message</div>";
        }
        ?>

        <div>
            <table border="1">
                <tr>
                    <th>UserId</th>
                    <th>Full Name</th>
                    <th>Email Address</th>
                    <th>Profile Picture</th>
                    <th>Hobbies</th>
                    <th>City and State</th>
                    <th>Action</th>
                </tr>

                <?php
                while ($data = mysqli_fetch_assoc($execut)) {
                    echo "<tr>
                        <td>{$data['id']}</td>
                        <td>{$data['fname']} {$data['lname']}</td>
                        <td>{$data['email']}</td>
                        <td><img src='./uploads/{$data['profilepic']}' alt='Profile Picture'></td>
                        <td>{$data['hobbies']}</td>
                        <td>{$data['city']} {$data['state']}</td>
                        <td>
                            <a href='./index.php?id={$data['id']}' class='btn-update'>Edit</a>
                            <a href='delete.php?id={$data['id']}' class='btn-delete' onclick='return confirmDelete()'>Delete</a>
                        </td>
                    </tr>";
                }
                ?>
            </table>
        </div>

        <div class="pagination">
            <a href="?page=<?= ($page - 1) ?>" class="<?= ($page <= 1) ? 'disabled' : '' ?>">Prev</a>
            <?php
         
            for ($i = 1; $i <= $total_pages; $i++) {
                echo "<a href='?page=$i' class='" . ($i == $page ? 'active' : '') . "'>$i</a>";
            }
            ?>
            <a href="?page=<?= ($page + 1) ?>" class="<?= ($page >= $total_pages) ? 'disabled' : '' ?>">Next</a>
        </div>
    </div>

     
</body>
</html>
