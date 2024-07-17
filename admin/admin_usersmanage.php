<?php
include '../includes/dbconn.php';
session_start();

// Create connection

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['userId'];
    $status = $_POST['status'];

    $query = "UPDATE user SET status = '$status' WHERE ID = $userId";
    if (mysqli_query($conn, $query)) {
        echo "Status updated successfully.";
    } else {
        echo "Error updating status: " . mysqli_error($conn);
    }
}

// Fetch data from the user table
$sql = "SELECT ID, username, Email, status FROM user";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap');
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            list-style: none;
            font-family: 'Montserrat', sans-serif;
        }
        body {
            background-color: #f9f9fa;
            display: flex;
            min-height: 100vh;
            margin: 0;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
        }
        .padding {
            padding: 2rem;
        }
        .card {
            box-shadow: none;
            width: 80%;
            margin-left: 300px;
            border: 1px solid #d2d2dc;
            background-color: #fff;
        }
        .card .card-title {
            color: #000000;
            margin-bottom: 0.625rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
        }
        .table th,
        .table td {
            font-size: 0.875rem;
        }
        button {
            outline: 0 !important;
        }
        .form-control:focus {
            box-shadow: none !important;
        }
        .badge {
            border-radius: 0;
            font-size: 12px;
            padding: .375rem .5625rem;
            font-weight: normal;
            border: none;
        }
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 250px;
            background-color: #0b2866;
            color: #ecf0f1;
            padding: 20px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .sidebar h2 {
            margin-bottom: 30px;
            font-size: 24px;
        }
        .sidebar .profile img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
        }
        .profile {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }
        .profile img {
            border-radius: 50%;
            width: 100px;
            height: 100px;
        }
        .sidebar .profile h2 {
            margin-top: 10px;
            text-align: center;
        }
        .sidebar nav ul {
            list-style-type: none;
            padding: 0;
        }
        .sidebar nav ul li {
            margin: 10px 0;
        }
        .sidebar nav ul li a {
            color: #ecf0f1;
            text-decoration: none;
            display: block;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .sidebar nav ul li a:hover {
            background-color: #6bcef5;
        }
        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
        }
        input:checked + .slider {
            background-color: green;
        }
        input:checked + .slider:before {
            transform: translateX(26px);
        }
        .slider.round {
            border-radius: 34px;
        }
        .slider.round:before {
            border-radius: 50%;
        }
        .slider.inactive {
            background-color: red;
        }
        input:not(:checked) + .slider.inactive:before {
            transform: translateX(0);
        }
    </style>
</head>
<body>
<div class="sidebar">
    <?php include 'sidebar.php'; ?>
</div>
<div class="content">
    <div class="padding">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title text-center">Manage Users</h4>
                <hr>
                <div class="table-responsive">
                    <table id="users" class="table table-hover">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    $checked = $row['status'] == 'Active' ? 'checked' : '';
                                    echo "<tr>";
                                    echo "<td>" . $row['ID'] . "</td>";
                                    echo "<td>" . $row['username'] . "</td>";
                                    echo "<td>" . $row['Email'] . "</td>";
                                    echo "<td>" . $row['status'] . "</td>";
                                    echo "<td>
                                            <label class='switch'>
                                                <input type='checkbox' $checked onclick='changeStatus(" . $row['ID'] . ", this)'>
                                                <span class='slider round " . ($row['status'] == 'Active' ? '' : 'inactive') . "'></span>
                                            </label>
                                          </td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>No users found</td></tr>";
                            }
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
<script>
    function changeStatus(userId, checkbox) {
        const status = checkbox.checked ? 'Active' : 'Inactive';
        $.post("admin_usersmanage.php", { userId: userId, status: status }, function(data) {
            location.reload();
        });
    }
</script>
</body>
</html>
