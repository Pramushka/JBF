<?php
include '../includes/dbconn.php';

$ticket_id = isset($_GET['id']) ? $_GET['id'] : 0;

$sql = "SELECT * FROM helpdeskinquiries WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$ticket_result = $stmt->get_result();
$ticket = $ticket_result->fetch_assoc();

$reply_sql = "SELECT * FROM helpdeskreply WHERE TicketID = ?";
$reply_stmt = $conn->prepare($reply_sql);
$reply_stmt->bind_param("i", $ticket_id);
$reply_stmt->execute();
$reply_result = $reply_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Details</title>
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
        .ticket-details {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .ticket-details h3 {
            margin: 0 0 10px;
        }
        .ticket-details p {
            margin: 0;
        }
        .reply-section {
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333333;
        }
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #dddddd;
            border-radius: 5px;
            box-sizing: border-box;
            height: 100px;
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
    <h2>helpdesk</h2>
    <button onclick="window.location.href='submit_ticket.php'" class="btn">Submit new request</button>
</div>

<div class="container">
    <div class="ticket-details">
        <h3><?php echo $ticket['category']; ?></h3>
        <p><?php echo $ticket['Description']; ?></p>
        <h4>Replies</h4>
        <?php while ($reply = $reply_result->fetch_assoc()) { ?>
            <div class="reply">
                <p><?php echo $reply['Reply_Description']; ?></p>
                <p><small><?php echo $reply['CreatedOn']; ?></small></p>
            </div>
        <?php } ?>
    </div>
</div>

</body>
</html>
