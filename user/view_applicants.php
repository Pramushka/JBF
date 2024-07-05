<?php
session_start();
require_once '../includes/dbconn.php';

$jobPostId = $_GET['job_id'] ?? null;

if ($jobPostId === null) {
    echo "Job post ID is required.";
    exit;
}

// Query to get all applicants for the job post
$sql = "SELECT u.* FROM user u
        JOIN job_appliers ja ON ja.UserID = u.ID
        WHERE ja.JobPostID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $jobPostId);
$stmt->execute();
$applicants = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Applicants</title>
    <link rel="stylesheet" href="../assets/css/jobsearch.css">
</head>
<body>
<?php include 'navbarsearch.php'; ?>

<div class="container">
    <h2>Applicants for the Job Post</h2>
    <?php foreach ($applicants as $applicant): ?>
        <div class="applicant">
            <h5><?= htmlspecialchars($applicant['First_Name']) . ' ' . htmlspecialchars($applicant['Last_Name']) ?></h5>
            <p>Email: <?= htmlspecialchars($applicant['Email']) ?></p>
            <p>Skills: <?= htmlspecialchars($applicant['Skill']) ?></p>
            <p>About: <?= htmlspecialchars($applicant['about']) ?></p>
        </div>
    <?php endforeach; ?>
    <?php if (empty($applicants)): ?>
        <p>No applicants found.</p>
    <?php endif; ?>
</div>
</body>
</html>
