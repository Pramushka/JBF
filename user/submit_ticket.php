<?php
include '../includes/dbconn.php';
session_start();

// Ensure user_id is set in session
if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $status = 'pending';

    // Start a transaction
    $conn->begin_transaction();

    try {
        $sql = "INSERT INTO helpdeskinquiries (Description, Status, priority, category) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $description, $status, $priority, $category);
        $stmt->execute();
        $help_id = $stmt->insert_id;

        $sql_link = "INSERT INTO user_helpdeskinquiries (Help_ID, UserID) VALUES (?, ?)";
        $stmt_link = $conn->prepare($sql_link);
        $stmt_link->bind_param("ii", $help_id, $user_id);
        $stmt_link->execute();

        // Commit transaction
        $conn->commit();

        echo "<script>
                alert('New record created successfully');
                window.location.href = 'submit_ticket.php';
              </script>";
    } catch (Exception $e) {
        // Rollback transaction if something goes wrong
        $conn->rollback();
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }

    $stmt->close();
    $stmt_link->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Ticket</title>
    <link rel="stylesheet" href="../assets/css/helpdesk.css">
</head>

<body>

    <div class="sidebar">
        <h2>HELP DESK</h2>
        <button onclick="window.location.href='submit_ticket.php'" class="btn">Submit new request</button>
        <button onclick="window.location.href='view_tickets.php'" class="btn">View Tickets</button>
    </div>

    <div class="container">
        <div class="form-container">
            <h2>Submit a New IT Request</h2>
            <form action="submit_ticket.php" method="post">
                <div class="form-group">
                    <label for="category">Category</label>
                    <select name="category" id="category" required>
                        <option value="">Choose one</option>
                        <option value="Hardware">Hardware</option>
                        <option value="Software">Software</option>
                        <option value="Network">Network</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Request Details</label>
                    <textarea name="description" id="description" placeholder="How can we help?" required></textarea>
                </div>
                <div class="form-group">
                    <label for="priority">Priority</label>
                    <select name="priority" id="priority" required>
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>
                <button type="submit" class="btn">Submit</button>
            </form>
        </div>
    </div>

</body>
</html>
