<?php
session_start();
include '../includes/dbconn.php';

// Ensure the organization ID is provided
$orgId = $_GET['id'] ?? '';
if (empty($orgId)) {
    header("Location: errorPage.php"); // Redirect to an error page or the organization listing page if ID is not provided
    exit;
}

// Fetch organization details
$sqlOrg = "SELECT * FROM organization WHERE ID = ?";
$stmtOrg = $conn->prepare($sqlOrg);
$stmtOrg->bind_param('i', $orgId);
$stmtOrg->execute();
$organization = $stmtOrg->get_result()->fetch_assoc();
$stmtOrg->close();

// Check if organization exists
if (!$organization) {
    header("Location: errorPage.php"); // Redirect if the organization does not exist
    exit;
}

// Fetch user details
$sqlUser = "SELECT * FROM user WHERE ID = ?";
$stmtUser = $conn->prepare($sqlUser);
$stmtUser->bind_param('i', $organization['UserID']);
$stmtUser->execute();
$user = $stmtUser->get_result()->fetch_assoc();
$stmtUser->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobForce</title>
    <!--<link rel="stylesheet" href="../assets/css/organization.css">-->
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

<?php include 'navbarsearch.php'; ?>

<section class="main-banner">
    <div class="banner-content">
        <h1><?= htmlspecialchars($organization['Org_Name']) ?></h1>
        <!--<h2>UnitedHealth Group | Optum | UnitedHealthcare</h2>-->
    </div>
</section>


<div class="container mt-5">
    <p>Email: <?= htmlspecialchars($organization['Org_Email']) ?></p>
    <p>Location: <?= htmlspecialchars($organization['Org_Location']) ?></p>
    <p>Industry: <?= htmlspecialchars($organization['Org_Industry']) ?></p>
    <p>Registration No: <?= htmlspecialchars($organization['Org_Register_no']) ?></p>
    <p>Contact: <?= htmlspecialchars($organization['Verification_Contact']) ?></p>
    <p>Created by: <?= htmlspecialchars($user['First_Name']) ?> <?= htmlspecialchars($user['Last_Name']) ?> (<?= htmlspecialchars($user['Email']) ?>)</p>
</div>

<a href="jobpost_dashboard.php?org_id=<?= $orgId ?>">View Job Posts</a>


<?php include  'footer.php'; ?>

</body>
</html>
