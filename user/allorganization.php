<?php
session_start();
include '../includes/dbconn.php';

// Fetch all organizations
$sql = "SELECT * FROM organization";
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
    <link rel="stylesheet" href="../assets/css/allorganization.css">
</head>
<body>
<?php include 'navbarsearch.php'; ?>

    <div class="container">
        <h1 class="center-title">All Organizations</h1>
        <div class="cards">
            <?php foreach ($organizations as $org): ?>
                <div class="card">
                    <div class="content">
                        <h2><?= htmlspecialchars($org['Org_Name']) ?></h2>
                        <p>Email: <?= htmlspecialchars($org['Org_Email']) ?></p>
                        <p>Location: <?= htmlspecialchars($org['Org_Location']) ?: 'Not Specified' ?></p>
                        <p>Industry: <?= htmlspecialchars($org['Org_Industry']) ?: 'Not Specified' ?></p>
                        <p>Registration No: <?= htmlspecialchars($org['Org_Register_no']) ?></p>
                        <p>Contact: <?= htmlspecialchars($org['Verification_Contact']) ?: 'Not Specified' ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>

