<?php
include 'config.php'; 
session_start();

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 3; 
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
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fb;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .header-container {
            display: flex;
            align-items: center;
            background-color: #ecf3fd;
            padding: 15px 30px;
            box-shadow: 0px 4px 10px #c5c5c5;
        }
        h2 {
            width: 100%;
            text-align:  center;
            margin: 0;
            color: #0f0f0f;
            font-family : sans-serif;
            font-size: 30px;
        }

        .btn-add {
           width: 5%;
           background-color: #1485fff1;
           padding: 10px 20px;
           text-decoration: none;
           border-radius: 5px;
           color: white;
           font-size: 16px;
        }

        .btn-add:hover {
            background-color: #000000;
        }

        .container {
            width: 80%;
            margin: 30px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f1f1f1;
            font-weight: 700;
        }

        td img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }

        tr:hover {
            background-color: #f9f9f9;
        }

        .btn-update, .btn-delete {
            display: inline-block;
            padding: 8px 16px;
            margin: 5px;
            border-radius: 5px;
            text-decoration: none;
            font-size: 14px;
            color: white;
        }

        .btn-update {
            background-color: #ffc107;
        }

        .btn-update:hover {
            background-color: #e0a800;
        }

        .btn-delete {
            background-color: #dc3545;
        }

        .btn-delete:hover {
            background-color: #c82333;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }

        .pagination a {
            padding: 8px 15px;
            margin: 0 5px;
            text-decoration: none;
            background-color: #4b5055;
            color: white;
            border-radius: 5px;
        }

        .pagination a:hover {
            background-color: #007bff;
        }

        .pagination .active {
            background-color: #007bff;
            color: #ffffff;
        }

        .pagination .disabled {
            background-color: #ddd;
            pointer-events: none;
        }

        .message {
            margin: 20px;
            padding: 10px;
            border-radius: 5px;
            color: #ffffff;
            text-align: center;
            font-weight: bold;
        }

        .success {
            background-color: #45c062;
            color : white;
        }

        .error {
            background-color: #dc3545;
        }
        span {
            color: #424b4e;
            font-size: 30px;
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
            echo "<div class='message $message_class' id='message'>$message</div>";
        }
        ?>

        <div>
            <table>
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

       <?php
        echo '<div class="pagination">';

            echo "<a href='?page=1' class='" . ($page <= 1 ? 'disabled' : '') . "'><<</a>";
            echo "<a href='?page=" . ($page - 1) . "' class='" . ($page <= 1 ? 'disabled' : '') . "'><</a>";

            $st_page = max(1, $page - 3); 
            $end_page = min($total_pages, $page + 3); 

            for ($i = $st_page; $i <= min($page - 1, $end_page); $i++) {
                echo "<a href='?page=$i' class='" . ($i == $page ? 'active' : '') . "'>$i</a>";
            }

            if ($page - 1) {
                echo "<span>...</span>";
            }
            echo "<a href='?page=$page' class='active'>$page</a>";

            if ($page + 1 < $total_pages + 1) {
                echo "<span>...</span>";
            }   

            for ($i = max($page + 1, $st_page); $i <= $end_page; $i++) {
                echo "<a href='?page=$i' class='" . ($i == $page ? 'active' : '') . "'>$i</a>";
            }

            echo "<a href='?page=" . ($page + 1) . "' class='" . ($page >= $total_pages ? 'disabled' : '') . "'>></a>";
            echo "<a href='?page=$total_pages' class='" . ($page >= $total_pages ? 'disabled' : '') . "'>>></a>";

        echo '</div>';
        ?>
    </div>
</body>
</html>
