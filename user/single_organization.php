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
    <link rel="stylesheet" href="../assets/css/s-organization.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <style>
        .short-text {
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 4;
            -webkit-box-orient: vertical;
            position: relative;
        }
        .short-text::after {
            content: '... ';
            position: absolute;
            right: 0;
            bottom: 0;
        }
        .full-text {
            display: none;
        }
        .toggle-link {
            color: blue;
            cursor: pointer;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<?php include 'navbarsearch.php'; ?>

<section class="background">
        <div class="back">
        <a href="#"><img src="" alt=""></a>  
        </div> 
</section>

<section class="main-banner">
    <div class="banner-content">
        <div class="org-details">
            <img src="../assets/img/company_logo.png" alt="Organization Logo" class="org-logo">
            <div>
                <h1 class="org-name"><?= htmlspecialchars($organization['Org_Name']) ?></h1>
                <div class="org-meta">
                    <span class="org-rating">4.0</span>
                    <span class="org-reviews">(10 reviews)</span>
                </div>
                <div class="org-tags">
                    <span class="badge bg-secondary">Iron & Steel</span>
                    <span class="badge bg-secondary">Public</span>
                    <span class="badge bg-secondary">Corporate</span>
                    <span class="badge bg-secondary">B2B</span>
                </div>
            </div>
        </div>
        <div class="org-actions">
        
        <?php if ($showJobPostsButton): ?>
        <a href="jobpost_dashboard.php?org_id=<?= $orgId ?>" class="btn btn-primary">Edit</a>
        <?php endif; ?>

        </div>
    </div>
</section>

<div class="container mt-5">
    <h2>About our organization</h2>
    <div class="cards">
        <div class="card-body">
        <p class="short-text" id="shortDescription">
            <?= htmlspecialchars($organization['Org_descript']) ?>
            </p>
            <p class="full-text" id="fullDescription">
            <?= htmlspecialchars($organization['Org_descript']) ?>
            </p>
            <span class="toggle-link" id="toggleLink" onclick="toggleText()">See More</span>
       
            <div id="organizationDetails" style="display: none;">
                <p><strong>Email:</strong> <?= htmlspecialchars($organization['Org_Email']) ?></p>
                <p><strong>Location:</strong> <?= htmlspecialchars($organization['Org_Location']) ?></p>
                <p><strong>Industry:</strong> <?= htmlspecialchars($organization['Org_Industry']) ?></p>
                <p><strong>Registration No:</strong> <?= htmlspecialchars($organization['Org_Register_no']) ?></p>
                <p><strong>Contact:</strong> <?= htmlspecialchars($organization['Verification_Contact']) ?></p>
                <p><strong>Created by:</strong> <?= htmlspecialchars($user['First_Name']) ?> <?= htmlspecialchars($user['Last_Name']) ?> (<?= htmlspecialchars($user['Email']) ?>)</p>
            </div>
            <button class="btn btn-primary" id="showDetailsBtn" onclick="toggleDetails()">See Organization Overview</button>
        </div>
    </div>
</div>

<!-- Modal for organization -->
<div class="modal fade" id="organizationModal" tabindex="-1" aria-labelledby="organizationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="organizationModalLabel">Organization Overview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="cardo">
                    <div class="card-body">
                        <p><strong>Email:</strong> <?= htmlspecialchars($organization['Org_Email']) ?></p>
                        <p><strong>Location:</strong> <?= htmlspecialchars($organization['Org_Location']) ?></p>
                        <p><strong>Industry:</strong> <?= htmlspecialchars($organization['Org_Industry']) ?></p>
                        <p><strong>Registration No:</strong> <?= htmlspecialchars($organization['Org_Register_no']) ?></p>
                        <p><strong>Contact:</strong> <?= htmlspecialchars($organization['Verification_Contact']) ?></p>
                        <p><strong>Created by:</strong> <?= htmlspecialchars($user['First_Name']) ?> <?= htmlspecialchars($user['Last_Name']) ?> (<?= htmlspecialchars($user['Email']) ?>)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

<br>
<br>

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
                <div class="alert alert-warning" role="alert">
                    We hope that you finish your user profile or you might get rejected because your data won't go to the recruiter properly. We recommend you complete the user profile completely.
                </div>
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



function toggleDetails() {
    var organizationModal = new bootstrap.Modal(document.getElementById('organizationModal'), {
        keyboard: false
    });
    organizationModal.show();
}

</script>


<script>
        function toggleText() {
            const shortDescription = document.getElementById('shortDescription');
            const fullDescription = document.getElementById('fullDescription');
            const toggleLink = document.getElementById('toggleLink');
            
            if (fullDescription.style.display === "none") {
                fullDescription.style.display = "block";
                shortDescription.style.display = "none";
                toggleLink.textContent = "See Less";
            } else {
                fullDescription.style.display = "none";
                shortDescription.style.display = "-webkit-box";
                toggleLink.textContent = "See More";
            }
        }
    </script>
</body>
</html>
