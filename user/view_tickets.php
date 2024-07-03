<?php
include '../includes/dbconn.php';
session_start();

// Ensure user_id is set in session
if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT hi.ID, hi.Description, hi.Status, hi.priority, hi.category 
        FROM helpdeskinquiries hi
        JOIN user_helpdeskinquiries uhi ON hi.ID = uhi.Help_ID
        WHERE uhi.UserID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tickets</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
        }
        .sidebar {
            width: 250px;
            background-color: #e0e4ff;
            padding: 20px;
            height: 100vh;
        }
        .sidebar h2 {
            color: #333;
        }
        .container {
            flex: 1;
            background-color: #ffffff;
            padding: 20px;
        }
        .ticket {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .ticket h3 {
            margin: 0 0 10px;
        }
        .ticket p {
            margin: 0;
        }
        .btn {
            display: block;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            color: #ffffff;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            text-align: center;
        }
        .btn:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h2>HELP DESK</h2>
    <button onclick="window.location.href='submit_ticket.php'" class="btn">Submit new request</button>
</div>

<div class="container">
    <h2>My Tickets</h2>

    <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="ticket">
            <h3><?php echo $row['category']; ?></h3>
            <p><?php echo $row['Description']; ?></p>
            <p>Status: <?php echo $row['Status']; ?></p>
            <button onclick="viewTicketDetails(<?php echo $row['ID']; ?>)" class="btn">View Details</button>
        </div>
    <?php } ?>
</div>

<script>
    function viewTicketDetails(ticketID) {
        window.location.href = 'user_ticket_details.php?id=' + ticketID;
    }
</script>

</body>
</html>
