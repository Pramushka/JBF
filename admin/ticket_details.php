<?php
include '../includes/dbconn.php';

$ticket_id = isset($_GET['id']) ? $_GET['id'] : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply_description']) && isset($_POST['ticket_id'])) {
    $reply_description = $_POST['reply_description'];
    $ticket_id = $_POST['ticket_id'];

    $sql = "INSERT INTO helpdeskreply (Reply_Description, TicketID) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $reply_description, $ticket_id);
    if ($stmt->execute()) {
        $update_sql = "UPDATE helpdeskinquiries SET status = 'done' WHERE ID = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("i", $ticket_id);
        $update_stmt->execute();

        echo "<script>alert('Reply submitted and ticket marked as done');</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "');</script>";
    }
}

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
        .sidebarr {
            position: fixed;
            left: 1700px;
            top: 0;
            width: 200px;
            background-color: #343a40;
            color: #fff;
            padding: 20px;
            height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebarr h2 {
            margin-bottom: 30px;
            font-size: 24px;
        }
        .sidebarr button {
            color: #fff;
            width: 100%;
        }
        .container {
            margin-left: 240px;
            background-color: #ffffff;
            padding: 30px;
            flex: 1;
        }
        .ticket-details {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .ticket-details h3 {
            margin-bottom: 10px;
            font-size: 20px;
            color: #333;
        }
        .ticket-details p {
            margin: 0;
            font-size: 16px;
            color: #666;
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
            border: none;
            border-radius: 5px;
            color: #ffffff;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
            text-align: center;
        }
        .btn:hover {
            background-color: #007bff;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>

<div class="sidebarr">
    <h2>HELP DESK</h2>
    <button onclick="window.location.href='open_tickets.php'" class="btn"><i class="fas fa-arrow-left"></i> View Tickets</button>
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

    <?php if ($ticket['status'] !== 'done') { ?>
    <div class="reply-section">
        <h4>Reply to this ticket</h4>
        <form action="ticket_details.php?id=<?php echo $ticket_id; ?>" method="post">
            <div class="form-group">
                <label for="reply_description">Reply</label>
                <textarea name="reply_description" id="reply_description" required></textarea>
            </div>
            <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
            <button type="submit" class="btn">Submit Reply</button>
        </form>
    </div>
    <?php } ?>
</div>

</body>
</html>
