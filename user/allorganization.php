<?php
// Start the PHP session and include necessary files
session_start();
include '../includes/dbconn.php';

// Fetch all organizations
$sql = "SELECT * FROM organization"; // Assuming you have an IsDeleted column
$stmt = $conn->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();
$organizations = [];
while ($row = $result->fetch_assoc()) {
    $organizations[] = $row;
}
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Organizations</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f0f0f0;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 1200px;
        margin: auto;
        padding: 20px;
    }

    .cards {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center; /* Center align cards */
    }

    .card {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        padding: 50px; /* Increase padding for larger cards */
        width: 350px; /* Increase width for larger cards */
        background-color: #333;
        color: #fff;
        transition: transform 0.3s, box-shadow 0.3s;
        position: relative;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

    .card::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(to bottom, #ff416c, #ff4b2b);
        border-radius: 10px 0 0 10px;
    }

    .card h2 {
        color: #fff;
        font-size: 24px;
        margin-bottom: 10px;
    }

    .card p {
        color: #bbb;
        font-size: 16px;
        line-height: 1.6;
        margin-bottom: 8px;
    }

    .card .btn {
        display: inline-block;
        margin-top: 15px;
        padding: 10px 20px;
        background: linear-gradient(to right, #ff416c, #ff4b2b);
        color: #fff;
        text-decoration: none;
        border-radius: 5px;
        transition: background 0.3s;
    }

    .card .btn:hover {
        background: linear-gradient(to right, #ff4b2b, #ff416c);
    }
    </style>
</head>
<body>
<?php include 'navbar.php'; ?>

    <div class="container">
        <h1>All Organizations</h1>
        <div class="cards">
            <?php foreach ($organizations as $org): ?>
                <div class="card">
                    <h2><?= htmlspecialchars($org['Org_Name']) ?></h2>
                    <p>Email: <?= htmlspecialchars($org['Org_Email']) ?></p>
                    <p>Location: <?= htmlspecialchars($org['Org_Location']) ?: 'Not Specified' ?></p>
                    <p>Industry: <?= htmlspecialchars($org['Org_Industry']) ?: 'Not Specified' ?></p>
                    <p>Registration No: <?= htmlspecialchars($org['Org_Register_no']) ?></p>
                    <p>Contact: <?= htmlspecialchars($org['Verification_Contact']) ?: 'Not Specified' ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
