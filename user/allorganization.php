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
        .container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
}

.cards {
    display: flex;
    flex-wrap: wrap;
    gap: 20px;
    justify-content: space-around;
}

.card {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    border-radius: 10px;
    padding: 20px;
    width: 300px;
    background-color: #fff;
}

.card h2 {
    color: #333;
    font-size: 20px;
}

.card p {
    font-size: 16px;
    color: #666;
    line-height: 1.5;
    margin-bottom: 10px;
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
</body>
</html>
