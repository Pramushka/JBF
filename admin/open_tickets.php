<?php
include '../includes/dbconn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['reply_description']) && isset($_POST['ticket_id'])) {
    $reply_description = $_POST['reply_description'];
    $ticket_id = $_POST['ticket_id'];
    
    $sql = "INSERT INTO helpdeskreply (Reply_Description, TicketID) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $reply_description, $ticket_id);
    $stmt->execute();
    
    $update_sql = "UPDATE helpdeskinquiries SET status = 'done' WHERE ID = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("i", $ticket_id);
    $update_stmt->execute();
    
    echo "<script>alert('Reply submitted and ticket marked as done');</script>";
}

$category_filter = isset($_GET['category']) ? $_GET['category'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'pending';

$sql = "SELECT * FROM helpdeskinquiries WHERE status = ?";
if ($category_filter) {
    $sql .= " AND category = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $status_filter, $category_filter);
} else {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $status_filter);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Tickets</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background-color: #f4f4f9;
    margin: 0;
    padding: 0;
    display: flex;
}

.sideba {
    position: fixed; /* Ensure the sidebar is fixed */
    left: 1700px; /* Move the sidebar 20px from the left */
    top: 0;
    width: 200px;
    background-color: #343a40;
    color: #fff;
    padding: 20px;
    height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    box-shadow: 2px 0 5px rgba(0,0,0,0.1); /* Add a subtle shadow for better separation */
}

.sideba h2 {
    margin-bottom: 30px;
    font-size: 24px;
}

.sideba button {
    width: 100%;
}

.container {
    left: 390px; 
    background-color: #ffffff;
    padding: 30px;
    flex: 1;
}

.ticket {
    background-color: #f9f9f9;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.ticket h3 {
    margin-bottom: 10px;
    font-size: 20px;
    color: #333;
}

.ticket p {
    margin: 0;
    font-size: 16px;
    color: #666;
}

.btn {
    display: inline-block;
    padding: 10px 20px;
    border: none;
    border-radius: 10px;
    color: #ffffff;
    font-size: 16px;
    cursor: pointer;
    margin-top: 10px;
    text-align: center;
}

.btn:hover {
    background-color: #007bff;
}

.filter-form {
    margin-bottom: 30px;
    display: flex;
    gap: 10px;
}

.filter-form select {
    padding: 10px;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #f9f9f9;
    min-width: 150px;
}

.filter-form select:focus {
    border-color: #007bff;
    outline: none;
}

    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="sideba">
    <h2>HELP DESK</h2>
    <button onclick="window.location.href='submit_ticket.php'" class="btn btn-primary"><i class="fas fa-plus"></i> Submit new request</button>
</div>

<div class="container">
    <h2>All Requests</h2>
    <form class="filter-form" method="get" action="open_tickets.php">
        <select name="category" onchange="this.form.submit()">
            <option value="">All Categories</option>
            <option value="Hardware" <?php if ($category_filter == 'Hardware') echo 'selected'; ?>>Hardware</option>
            <option value="Software" <?php if ($category_filter == 'Software') echo 'selected'; ?>>Software</option>
            <option value="Network" <?php if ($category_filter == 'Network') echo 'selected'; ?>>Network</option>
            <option value="Other" <?php if ($category_filter == 'Other') echo 'selected'; ?>>Other</option>
        </select>
        <select name="status" onchange="this.form.submit()">
            <option value="pending" <?php if ($status_filter == 'pending') echo 'selected'; ?>>To do</option>
            <option value="done" <?php if ($status_filter == 'done') echo 'selected'; ?>>Done</option>
        </select>
    </form>

    <?php while ($row = $result->fetch_assoc()) { ?>
        <div class="ticket">
            <h3><?php echo $row['category']; ?></h3>
            <p><?php echo $row['Description']; ?></p>
            <button onclick="openTicket(<?php echo $row['ID']; ?>)" class="btn btn-primary">View</button>
        </div>
    <?php } ?>
</div>

<script>
    function openTicket(ticketID) {
        window.location.href = 'ticket_details.php?id=' + ticketID;
    }
</script>

</body>
</html>
