<?php
session_start();
include '../includes/dbconn.php';

// Ensure user_id and OrgID are set in session or get passed via GET request
if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];
$org_id = $_GET['org_id'] ?? '';  // Receive OrgID via GET request

// Validate OrgID
if (empty($org_id)) {
    die("Organization ID is required");
}

// Fetch the username of the logged-in user
$user_sql = "SELECT username FROM user WHERE id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result === false || $user_result->num_rows == 0) {
    die("Error fetching user details or user not found.");
}

$user_row = $user_result->fetch_assoc();
$username = $user_row['username'];

$sqlOrg = "SELECT * FROM organization WHERE ID = ?";
$stmtOrg = $conn->prepare($sqlOrg);
$stmtOrg->bind_param('i', $org_id);
$stmtOrg->execute();
$organization = $stmtOrg->get_result()->fetch_assoc();
$stmtOrg->close();


// Check if organization exists
if (!$organization) {
    die("Organization not found.");
}

// Fetching industries for the dropdown
$industryQuery = "SELECT id, industry_name FROM job_industries";
$industryResult = $conn->query($industryQuery);
$industries = [];

while ($industry = $industryResult->fetch_assoc()) {
    $industries[] = $industry;
}

// Fetch job posts for the organization
$sqlJobs = "SELECT jp.id, jp.job_positions, jp.job_category, jp.Benifits, jp.salary, org.Org_Name
            FROM jobpost jp 
            JOIN org_post op ON jp.id = op.JobPostID
            JOIN organization org ON op.OrgID = org.ID
            WHERE op.OrgID = ? AND jp.IsDeleted = 0
            ORDER BY jp.CreatedOn DESC";
$stmtJobs = $conn->prepare($sqlJobs);
$stmtJobs->bind_param('i', $org_id);
$stmtJobs->execute();
$jobPostsResult = $stmtJobs->get_result();
$jobPosts = [];
while ($jobPost = $jobPostsResult->fetch_assoc()) {
    $jobPosts[] = $jobPost;
}
$stmtJobs->close();

// Handling POST request for updating organization details
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['orgId'])) {
    $orgName = $_POST['orgName'];
    $orgDes = $_POST['orgDes'];
    $orgEmail = $_POST['orgEmail'];
    $orgRegisterNo = $_POST['orgRegisterNo'];
    $orgLocation = $_POST['orgLocation'];
    $orgIndustry = $_POST['orgIndustry'];
    $verificationContact = $_POST['verificationContact'];
    
    $updateSql = "UPDATE organization SET Org_Name=?, Org_Des=?, Org_Email=?, Org_RegisterNo=?, Org_Location=?, Org_Industry=?, Verification_Contact=? WHERE ID=?";
    $updateStmt = $conn->prepare($updateSql);
    $updateStmt->bind_param("sssssssi", $orgName, $orgDes, $orgEmail, $orgRegisterNo, $orgLocation, $orgIndustry, $verificationContact, $org_id);
    $updateStmt->execute();
    if ($updateStmt->affected_rows > 0) {
        echo "<script>alert('Organization updated successfully');</script>";
    } else {
        echo "<script>alert('No changes were made to the organization');</script>";
    }
    $updateStmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Post Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../assets/css/jobpostdash.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<?php include 'navbarsearch.php'; ?>

    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                <!-- Bg -->
                <div class="pt-20 rounded-top" style="background: url(https://bootdey.com/image/480x480/00FFFF/000000) no-repeat; background-size: cover;"></div>
                <div class="card rounded-bottom smooth-shadow-sm">
                    <div class="d-flex align-items-center justify-content-between pt-4 pb-6 px-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar-xxl avatar-indicators avatar-online me-2 position-relative d-flex justify-content-end align-items-end mt-n10">
                                <img src="https://bootdey.com/img/Content/avatar/avatar8.png" class="avatar-xxl rounded-circle border border-2" alt="Image">
                            </div>
                            <div class="lh-1">
                                <h2 class="mb-0"><?= htmlspecialchars($organization['Org_Name']) ?> Post Dashboard</h2>
                                <p class="mb-0 d-block">@<?php echo htmlspecialchars($username); ?></p>
                            </div>
                        </div>
                        <div>
                            <a href="job_post.php?org_id=<?= htmlspecialchars($org_id) ?>" class="btn btn-outline-primary">Create Job Post</a>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#organizationModal">Update Organization</button>
                            </div>
                    </div>
                    <ul class="nav nav-lt-tab px-4" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Job Posts</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="py-6">
            <div class="row">
                <?php
                if (!empty($jobPosts)) {
                    foreach ($jobPosts as $jobPost) {
                        echo "<div class='col-lg-4 col-12'>";
                        echo "<div class='card mb-5 rounded-3'>";
                        echo "<div><img src='https://bootdey.com/image/480x180/191970/ffffff' alt='Image' class='img-fluid rounded-top'></div>";
                        echo "<div class='avatar avatar-xl mt-n7 ms-4'><img src='https://bootdey.com/img/Content/avatar/avatar1.png' alt='Image' class='rounded-circle border-4 border-white-color-40'></div>";
                        echo "<div class='card-body'>";
                        echo "<h4 class='mb-1'>" . htmlspecialchars($jobPost['job_positions']) . "</h4>";
                        echo "<p>" . htmlspecialchars($jobPost['job_category']) . "</p>";
                        echo "<p>" . htmlspecialchars($jobPost['Benifits']) . "</p>";
                        echo "<p>$" . number_format($jobPost['salary']) . "</p>";
                        echo "<div class='d-flex justify-content-between align-items-center'>";
                        echo "<a href='edit_jobpost.php?job_id=" . htmlspecialchars($jobPost['id']) . "&org_id=" . htmlspecialchars($org_id) . "' class='btn btn-outline-primary'>View</a>";
                        echo "<a href='view_applicants.php?job_id=" . htmlspecialchars($jobPost['id']) . "' class='btn btn-info'>View Applicants</a>"; 
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No job posts found.</p>";
                }

                $conn->close();
                ?>
            </div>
        </div>
    </div>

<!-- Modal for Organization Management -->
<div class="modal fade" id="organizationModal" tabindex="-1" aria-labelledby="organizationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="organizationModalLabel">Edit Organization</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="orgForm">
                    <div class="mb-3">
                        <label for="orgName" class="form-label">Organization Name:</label>
                        <input type="text" class="form-control" id="orgName" name="orgName" value="<?= htmlspecialchars($organization['Org_Name']) ?>" required>
                    </div>
        
                    <div class="mb-3">
                        <label for="orgDes" class="form-label">About Organization:</label>
                        <textarea class="form-control" id="orgDes" name="orgDes" rows="4" required><?= htmlspecialchars($organization['Org_descript']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="orgEmail" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="orgEmail" name="orgEmail" value="<?= htmlspecialchars($organization['Org_Email']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="orgLocation" class="form-label">Location:</label>
                        <input type="text" class="form-control" id="orgLocation" name="orgLocation" value="<?= htmlspecialchars($organization['Org_Location']) ?>">
                    </div>
                    <div class="mb-3">
                        <label for="orgIndustry" class="form-label">Industry:</label>
                        <select class="form-control" id="orgIndustry" name="orgIndustry">
                            <?php foreach ($industries as $industry): ?>
                                <option value="<?= $industry['industry_name'] ?>" <?= $industry['industry_name'] == $organization['Org_Industry'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($industry['industry_name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="orgRegisterNo" class="form-label">Registration Number:</label>
                        <input type="text" class="form-control" id="orgRegisterNo" name="orgRegisterNo" value="<?= htmlspecialchars($organization['Org_Register_no']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="verificationContact" class="form-label">Verification Contact:</label>
                        <input type="text" class="form-control" id="verificationContact" name="verificationContact" value="<?= htmlspecialchars($organization['Verification_Contact']) ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    $('#orgForm').submit(function(event) {
        event.preventDefault(); // Stop form from submitting normally
        var formData = $(this).serialize(); // serialize the form data

        // Post the form data using ajax
        $.ajax({
            type: 'POST',
            url: 'update_organization.php', // ensure this URL is correct
            data: formData,
            success: function(response) {
                alert('Organization updated successfully.');
                $('#organizationModal').modal('hide'); // hide the modal on success
            },
            error: function() {
                alert('Error updating organization.');
            }
        });
    });
});

</script>

</body>
</html>
