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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'apply_for_job') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['error' => 'You need to log in to apply.']);
        exit;
    }
header('Content-Type: application/json'); // Ensure JSON content type

    $jobPostId = $_POST['jobPostId'] ?? null;
    $userId = $_SESSION['user_id'];

    if (empty($jobPostId)) {
        echo json_encode(['error' => 'Job Post ID is required']);
        exit;
    }

    $sql = "INSERT INTO job_appliers (JobPostID, UserID) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("ii", $jobPostId, $userId);
        $stmt->execute();
        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => 'Application submitted successfully!']);
        } else {
            echo json_encode(['error' => 'Failed to submit application. Error: ' . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Failed to prepare the statement. Error: ' . $conn->error]);
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobForce</title>
    <!--<link rel="stylesheet" href="../assets/css/organization.css">-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>

<?php include 'navbarsearch.php'; ?>

<section class="main-banner">
    <div class="banner-content">
        <h1><?= htmlspecialchars($organization['Org_Name']) ?></h1>
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
<div class="job-posts container mt-4">
    <h2>Recent Job Posts</h2>
    <div class="row">
        <?php foreach ($jobPosts as $jobPost): ?>
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($jobPost['job_positions']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($jobPost['Post_Description']) ?></p>
                        <p class="card-text"><small class="text-muted">Posted on <?= date('F j, Y', strtotime($jobPost['CreatedOn'])) ?></small></p>
                        <button class="btn btn-primary apply-btn" onclick='showApplyModal(<?= json_encode($jobPost) ?>)'>Apply Now</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (empty($jobPosts)): ?>
            <p>No job posts found.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'footer.php'; ?>

<!-- Modal for Job Application -->
<div class="modal fade" id="applyJobModal" tabindex="-1" aria-labelledby="applyJobModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="applyJobModalLabel">Apply for Job</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 id="jobTitle"></h4>
                <p id="jobDescription"></p>
                <p id="jobLocation"></p>
                <p id="jobSalary"></p>
                <button class="btn btn-secondary">Upload CV (Placeholder)</button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="sendApplication()">Send Application</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
var currentJobId = 0;

function showApplyModal(job) {
    currentJobId = job.ID;  // Ensure this matches the ID attribute in your job objects

    document.getElementById('jobTitle').textContent = job.job_positions;
    document.getElementById('jobDescription').textContent = job.Post_Description;
    document.getElementById('jobLocation').textContent = job.location + " - " + job.education;
    document.getElementById('jobSalary').textContent = "Salary: " + job.salary;

    var applyModal = new bootstrap.Modal(document.getElementById('applyJobModal'));
    applyModal.show();
}

function sendApplication() {
    $.ajax({
        url: 'single_organization.php?id=<?= $orgId ?>',
        type: 'POST',
        data: {
            action: 'apply_for_job',
            jobPostId: currentJobId
        },
        dataType: 'json', // Expect JSON response
        success: function(result) {
            console.log(result); // Log to console to inspect the actual response
            if (result.success) {
                alert(result.success);
            } else if (result.error) {
                alert(result.error);
            }
            var applyModal = bootstrap.Modal.getInstance(document.getElementById('applyJobModal'));
            applyModal.hide();
        },
        error: function(xhr, status, error) {
            alert('Failed to submit application: ' + xhr.responseText || error);
        }
    });
}
</script>

</body>
</html>
