<?php
session_start();
require_once '../includes/dbconn.php';


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

// Retrieve filter values from GET request (for initial load or AJAX request)
$industry = $_GET['industry'] ?? '';
$industrySkillPosition = $_GET['industrySkillPosition'] ?? '';
$experience = $_GET['experience'] ?? '';
$location = $_GET['location'] ?? '';
$recruitment = $_GET['recruitment'] ?? '';
$education = $_GET['education'] ?? '';
$salary = $_GET['salary'] ?? '';
$jobPositions = $_GET['jobPositions'] ?? '';



// Prepare the SQL query dynamically
$sql = "SELECT * FROM jobpost WHERE IsDeleted = 0";
$conditions = [];
$params = [];


if (!empty($industry)) {
    $conditions[] = "Industry = ?";
    $params[] = $industry;
}
if (!empty($industrySkillPosition)) {
    $conditions[] = "Searching_Skill LIKE CONCAT('%', ?, '%')";
    $params[] = $industrySkillPosition;
}
if (!empty($experience) && is_numeric($experience)) {
    $conditions[] = "Experience <= ?";
    $params[] = $experience;
}
if (!empty($location)) {
    $conditions[] = "Location LIKE CONCAT('%', ?, '%')";
    $params[] = $location;
}
if (!empty($recruitment)) {
    $conditions[] = "Recruitment = ?";
    $params[] = $recruitment;
}
if (!empty($education)) {
    $conditions[] = "Education = ?";
    $params[] = $education;
}
if (!empty($salary) && is_numeric($salary)) {
    $conditions[] = "Salary <= ?";
    $params[] = $salary;
}
if (!empty($jobPositions)) {
    $conditions[] = "job_positions = ?";
    $params[] = $jobPositions;
}

if (!empty($conditions)) {
    $sql .= " AND " . implode(" AND ", $conditions);
}

$stmt = $conn->prepare($sql);
// Dynamically bind parameters
if (!empty($params)) {
    $stmt->bind_param(str_repeat("s", count($params)), ...$params);
}



$stmt->execute();
$result = $stmt->get_result();
$jobs = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Search Results</title>
    <link rel="stylesheet" href="../assets/css/jobsearch.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php include 'navbarsearch.php'; ?>


<div class="container-fluid">
    <div class="row">
        <div class="col-md-3">
            <form id="filtersForm" class="sticky-top">
                <h2>All Filters</h2>
                <div class="mb-3">
                    <label for="jobPositions" class="form-label">Job Position</label>
                    <select name="jobPositions" id="jobPositions" class="form-select" onchange="applyFilters()">
                        <option value="">Any</option>
                        <?php
                        $positionQuery = $conn->query("SELECT position_name FROM job_positions");
                        while ($row = $positionQuery->fetch_assoc()) {
                            echo '<option value="' . htmlspecialchars($row['position_name']) . '">' . htmlspecialchars($row['position_name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="industry" class="form-label">Industry</label>
                    <select name="industry" id="industry" class="form-select" onchange="applyFilters()">
                        <option value="">Any</option>
                        <?php
                        $industryQuery = $conn->query("SELECT industry_name FROM job_industries");
                        while ($row = $industryQuery->fetch_assoc()) {
                            echo '<option value="' . htmlspecialchars($row['industry_name']) . '">' . htmlspecialchars($row['industry_name']) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="experienceRange" class="form-label">Experience</label>
                    <input type="range" min="0" max="30" value="1" class="form-range" name="experience" id="experienceRange" oninput="document.getElementById('experienceValue').textContent = this.value; applyFilters();">
                    <p>Experience: <span id="experienceValue">1</span> Years</p>
                </div>
                <div class="mb-3">
                    <label for="recruitment" class="form-label">Recruitment Type</label>
                    <select name="recruitment" id="recruitment" class="form-select" onchange="applyFilters()">
                        <option value="">Any</option>
                        <option value="Full-Time">Full-Time</option>
                        <option value="Part-Time">Part-Time</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="education" class="form-label">Education Required</label>
                    <select name="education" id="education" class="form-select" onchange="applyFilters()">
                        <option value="">Any</option>
                        <option value="Ordinary Level">Ordinary Level</option>
                        <option value="Advanced Level">Advanced Level</option>
                        <option value="HND">HND</option>
                        <option value="Bachelors">Bachelor's</option>
                        <option value="Masters">Master's</option>
                        <option value="PhD">PhD</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="location" class="form-label">Location</label>
                    <input type="text" name="location" id="location" class="form-control" placeholder="Enter location" oninput="applyFilters()">
                </div>
                <button type="button" class="btn btn-secondary" onclick="resetFilters()">Reset Filters</button>
            </form>
        </div>
        <div class="col-md-9">
            <div id="jobListings" class="row row-cols-1 row-cols-md-2 g-4">
                <?php foreach ($jobs as $job): ?>
                    <div class="col">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($job['job_positions']) ?></h5>
                                <p class="card-text"><?= htmlspecialchars($job['Post_Description']) ?></p>
                                <p class="card-text"><small class="text-muted"><?= htmlspecialchars($job['location']) ?> - <?= htmlspecialchars($job['education']) ?></small></p>
                                <p class="card-text">Salary: <?= htmlspecialchars($job['salary']) ?></p>
                            </div>
                            <div class="card-footer">
                            <button class="btn btn-primary apply-btn" onclick='showApplyModal(<?= json_encode($job) ?>)'>Apply Now</button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($jobs)): ?>
                    <p>No jobs found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

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
<script>
var currentJobId = 0; 
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    
    function applyFilters() {
    $.ajax({
        url: 'jobsearch.php',
        type: 'GET',
        data: $('#filtersForm').serialize(),
        success: function(data) {
            console.log("Received data: ", data); // Check what is being received.
            $('#jobListings').html($(data).find('#jobListings').html());
        },
        error: function(xhr) {
            console.log("Error: ", xhr.statusText);
        }
    });
}


function resetFilters() {
    $('#filtersForm').find('input[type=text], input[type=number], select').val('');
    applyFilters();
}

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
        url: 'jobsearch.php',
        type: 'POST',
        data: {
            action: 'apply_for_job',
            jobPostId: currentJobId,
            userId: <?= json_encode($_SESSION['user_id']) ?>
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
