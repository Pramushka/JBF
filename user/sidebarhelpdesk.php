

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Learning Course</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
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
            color: #ecf0f1;
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

    </style>
</head>
<body>

    

<div class="sidebar">
 
    <div class="profile">
        <img src="../assets/img/logo/admin.png" alt="Profile Picture">
        <h2>Help Desk</h2>
    </div>
    <nav>
        <ul>
            <li><a href="./view_tickets.php"><i class="fas fa-home"></i> Dashboard</a></li>
            <li><a href="./submit_ticket.php"><i class="fas fa-ticket-alt"></i> submit a ticket</a></li>
            <li><a href="./home.php"><i class="fas fa-check-circle"></i>Back to home page</a></li>
            
        </ul>
    </nav>
</div>

</body>
</html>
