<?php
session_start();
require_once '../includes/dbconn.php';

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

</head>
<body>

<?php include 'navbarsearch.php'; ?>


<div class="container">
    <div class="sidebar">
        <form id="filtersForm">
            <h2>All Filters</h2>
            <div class="filter-section">
                <h3>Job Position</h3>
                <select name="jobPositions" onchange="applyFilters()">
                    <option value="">Any</option>
                    <?php
                    $positionQuery = $conn->query("SELECT position_name FROM job_positions");
                    while ($row = $positionQuery->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($row['position_name']) . '">' . htmlspecialchars($row['position_name']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="filter-section">
                <h3>Industry</h3>
                <select name="industry" onchange="applyFilters()">
                    <option value="">Any</option>
                    <?php
                    $industryQuery = $conn->query("SELECT industry_name FROM job_industries");
                    while ($row = $industryQuery->fetch_assoc()) {
                        echo '<option value="' . htmlspecialchars($row['industry_name']) . '">' . htmlspecialchars($row['industry_name']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div class="filter-section">
                <h3>Experience</h3>
                <input type="range" min="0" max="30" value="1" class="slider" name="experience" id="experienceRange" oninput="document.getElementById('experienceValue').textContent = this.value; applyFilters();">
                <p>Experience: <span id="experienceValue">1</span> Years</p>
            </div>
            <div class="filter-section">
                <h3>Recruitment Type</h3>
                <select name="recruitment" onchange="applyFilters()">
                    <option value="">Any</option>
                    <option value="Full-Time">Full-Time</option>
                    <option value="Part-Time">Part-Time</option>
                </select>
            </div>
            <div class="filter-section">
                <h3>Education Required</h3>
                <select name="education" onchange="applyFilters()">
                    <option value="">Any</option>
                    <option value="Ordinary Level">Ordinary Level</option>
                    <option value="Advanced Level">Advanced Level</option>
                    <option value="HND">HND</option>
                    <option value="Bachelors">Bachelor's</option>
                    <option value="Masters">Master's</option>
                    <option value="PhD">PhD</option>
                </select>
            </div>
          
            <div class="filter-section">
                <h3>Location</h3>
                <input type="text" name="location" placeholder="Enter location" oninput="applyFilters()">
            </div>
            <button type="button" onclick="resetFilters()">Reset Filters</button>
        </form>
    </div>

    <div class="main-content" id="jobListings">
        <?php foreach ($jobs as $job): ?>
            <div class="job-card">
                <h3><?= htmlspecialchars($job['job_positions']) ?></h3>
                <p><?= htmlspecialchars($job['Post_Description']) ?></p>
                <p><?= htmlspecialchars($job['location']) ?> - <?= htmlspecialchars($job['education']) ?></p>
                <p>Salary: <?= htmlspecialchars($job['salary']) ?></p>
            </div>
        <?php endforeach; ?>
        <?php if (empty($jobs)): ?>
            <p>No jobs found.</p>
        <?php endif; ?>
    </div>




        <div class="guidance">
    <div class="card">
        <img src="../assets/img/guidance.png" alt="Naukri FastForward" style="width: 100%; height: auto;">
        <div class="card-content">
            <h2>Get 10X more profile views from warehouse management recruiters</h2>
            <p>Increase your chances of callback with JobForce</p>
            <button class="learn-more">Know More</button>
        </div>
    </div>
</div>

    </div>

<script>
document.getElementById('experienceRange').oninput = function() {
    document.getElementById('experienceValue').textContent = this.value;
}
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function applyFilters() {
    $.ajax({
        url: 'jobsearch.php',
        type: 'GET',
        data: $('#filtersForm').serialize(),
        success: function(data) {
            $('#jobListings').html($(data).find('#jobListings').html());
        }
    });
}

function resetFilters() {
    $('#filtersForm').find('input[type=text], input[type=number], select').val('');
    applyFilters();
}

document.getElementById('experienceRange').oninput = function() {
    document.getElementById('experienceValue').textContent = this.value;
}
</script>


</body>
</html>
