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
    <link rel="stylesheet" href="../assets/css/applicants.css"> <!-- Link to the new CSS file -->
</head>
<body>
<?php include 'navbarsearch.php'; ?>

<div class="container">
    <h2 class="center-text">Applicants for the Job Post</h2>
    <table class="applicants-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Skills</th>
                <th>About</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($applicants as $applicant): ?>
                <tr>
                    <td><?= htmlspecialchars($applicant['First_Name']) . ' ' . htmlspecialchars($applicant['Last_Name']) ?></td>
                    <td><?= htmlspecialchars($applicant['Email']) ?></td>
                    <td><?= htmlspecialchars($applicant['Skill']) ?></td>
                    <td><?= htmlspecialchars($applicant['about']) ?></td>
                    <td>
                        <button onclick="location.href='cv_generate.php?user_id=<?= htmlspecialchars($applicant['ID']) ?>'">Go to CV</button>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if (empty($applicants)): ?>
                <tr>
                    <td colspan="5">No applicants found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
</body>
</html>
