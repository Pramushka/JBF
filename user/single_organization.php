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

$showJobPostsButton = isset($_SESSION['user_id']) && $_SESSION['user_id'] == $organization['UserID'];

// Fetch job posts for the organization
$sqlJobs = "SELECT jp.* FROM jobpost jp 
            JOIN org_post op ON jp.id = op.JobPostID
            WHERE op.OrgID = ? AND jp.IsDeleted = 0
            ORDER BY jp.CreatedOn DESC LIMIT 10";
$stmtJobs = $conn->prepare($sqlJobs);
$stmtJobs->bind_param('i', $orgId);
$stmtJobs->execute();
$jobPostsResult = $stmtJobs->get_result();
$jobPosts = [];
while ($jobPost = $jobPostsResult->fetch_assoc()) {
    $jobPosts[] = $jobPost;
}
$stmtJobs->close();

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



<?php if ($showJobPostsButton): ?>
        <a href="jobpost_dashboard.php?org_id=<?= $orgId ?>" class="btn btn-primary">View Job Posts</a>
    <?php endif; ?>

    <!-- Job Posts Section -->
    <div class="job-posts">
        <h2>Recent Job Posts</h2>
        <?php foreach ($jobPosts as $jobPost): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title"><?= htmlspecialchars($jobPost['job_positions']) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($jobPost['Post_Description']) ?></p>
                    <p class="card-text"><small class="text-muted">Posted on <?= date('F j, Y', strtotime($jobPost['CreatedOn'])) ?></small></p>
                    <!--<a href="apply_job.php?job_id=<?= $jobPost['id'] ?>" class="btn btn-primary">Apply Now</a>-->
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($jobPosts)): ?>
            <p>No job posts found.</p>
        <?php endif; ?>
    </div>
    </div>

<?php include  'footer.php'; ?>

</body>
</html>
