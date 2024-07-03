<?php
// Start the PHP session and include necessary files
session_start();
include '../includes/dbconn.php';

// Initialize variables
$industrySkillPosition = '';
$experience = '';
$location = '';

// Define an array to hold job posts
$jobs = [];

// Build the SQL query
$sql = "SELECT * FROM jobpost WHERE IsDeleted = 0";

// Add conditions based on the user input
$conditions = [];
$params = [];

if (!empty($industrySkillPosition) && $industrySkillPosition != 'Not Specified') {
    $conditions[] = "Searching_Skill LIKE ?";
    $params[] = "%$industrySkillPosition%";
}

if (!empty($experience) && $experience != 'Not Specified' && is_numeric($experience)) {
    $conditions[] = "Experience <= ?";
    $params[] = $experience;
}

if (!empty($location) && $location != 'Not Specified') {
    $conditions[] = "Location LIKE ?";
    $params[] = "%$location%";
}

if (!empty($conditions)) {
    $sql .= " AND " . implode(" AND ", $conditions);
} else {
    // Fallback SQL if no conditions are specified
    $sql .= " ORDER BY CreatedOn DESC LIMIT 10"; 
}

// Prepare the statement
$stmt = $conn->prepare($sql);

// Bind the parameters
foreach ($params as $index => $param) {
    // Since mysqli bind_param needs the types, you need to define the type (s for string, i for integer)
    $type = is_numeric($param) ? 'i' : 's';
    $stmt->bind_param($type, $params[$index]);
}

// Execute the query
$stmt->execute();

// Bind result variables and fetch
$result = $stmt->get_result();
$jobs = [];
while ($row = $result->fetch_assoc()) {
    $jobs[] = $row;
}


// Close the statement
$stmt->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Search Results</title>
    <link rel="stylesheet" href="../assets/css/jobsearch.css">
    <link rel="stylesheet" href="../assets/css/homepage.css">

</head>
<body>

<?php include 'navbar.php'; ?>


    <div class="container">
    <div class="sidebar">
    <h2>All Filters</h2>
    <div class="filter-section">
        <h3>Work Mode</h3>
        <label><input type="checkbox" name="work_from_office"> Work from office (717)</label>
        <label><input type="checkbox" name="remote"> Remote (12)</label>
        <label><input type="checkbox" name="hybrid"> Hybrid (5)</label>
    </div>

    <div class="filter-section">
        <h3>Experience</h3>
        <input type="range" min="0" max="30" value="1" class="slider" id="experienceRange">
        <p>Experience: <span id="experienceValue">1</span> Years</p>
    </div>

    <div class="filter-section">
        <h3>Department</h3>
        <label><input type="checkbox" name="engineering"> Engineering - Software (430)</label>
        <label><input type="checkbox" name="merchandising"> Merchandising, Retail (110)</label>
        <label><input type="checkbox" name="procurement"> Procurement & Supply (106)</label>
        <label><input type="checkbox" name="customer_success"> Customer Success, Service (16)</label>
        <button class="view-more">View More</button>
    </div>

    <div class="filter-section">
        <h3>Location</h3>
        <label><input type="checkbox" name="bengaluru"> Bengaluru (131)</label>
        <label><input type="checkbox" name="delhi_ncr"> Delhi / NCR (112)</label>
        <label><input type="checkbox" name="mumbai_all"> Mumbai (All Areas) (103)</label>
        <label><input type="checkbox" name="mumbai"> Mumbai (82)</label>
        <button class="view-more">View More</button>
    </div>
    <div class="filter-section">
    <h3>Post Name</h3>
    <select name="post_name">
        <!-- Dynamically populate this dropdown -->
    </select>
    </div>

    <div class="filter-section">
    <h3>Skills</h3>
    <!-- Dynamically generate checkboxes based on available skills in the database -->
    </div>

    <div class="filter-section">
    <h3>Date Posted</h3>
    <input type="date" name="created_on_from">
    to
    <input type="date" name="created_on_to">
    </div>

    <div class="filter-section">
    <h3>Last Modified</h3>
    <input type="date" name="modified_on_from">
    to
    <input type="date" name="modified_on_to">
    </div>

</div>

<div class="main-content">
    <div class="search-tags">
        <?php if (!empty($industrySkillPosition) && $industrySkillPosition != 'Not Specified'): ?>
            <span class="tag" style="background: #e1ecf4; padding: 5px 15px; border-radius: 20px; font-size: 14px;"><?= htmlspecialchars($industrySkillPosition) ?></span>
        <?php endif; ?>
        <?php if (!empty($experience) && $experience != 'Not Specified'): ?>
            <span class="tag" style="background: #e1ecf4; padding: 5px 15px; border-radius: 20px; font-size: 14px;"><?= htmlspecialchars($experience) ?> years</span>
        <?php endif; ?>
        <?php if (!empty($location) && $location != 'Not Specified'): ?>
            <span class="tag" style="background: #e1ecf4; padding: 5px 15px; border-radius: 20px; font-size: 14px;"><?= htmlspecialchars($location) ?></span>
        <?php endif; ?>
    </div>
    <div class="job-listings">
        <?php if (!empty($jobs)): ?>
            <?php foreach ($jobs as $job): ?>
                <div class="job-card" style="border: 1px solid #ccc; padding: 20px; margin-top: 10px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
                    <div style="display: flex; align-items: center; justify-content: space-between;">
                        <div>
                            <h3 style="margin-top: 0; font-size: 18px;"><?= htmlspecialchars($job['Post_Name']) ?></h3>
                            <p style="margin: 5px 0; font-size: 14px;"><?= htmlspecialchars($job['Post_Description']) ?></p>
                            <p style="margin: 5px 0; font-size: 14px;"><?= htmlspecialchars($job['Searching_Skill']) ?></p>
                            <p style="margin: 5px 0; font-size: 14px;"><?= htmlspecialchars($job['Contact_Info']) ?></p>
                        </div>
                     
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="job-card not-found" style="border: 1px solid #ccc; padding: 20px; border-radius: 5px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); text-align: center;">
                <p>No jobs found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

        <div class="guidance">
    <div class="card">
        <img src="../assets/img/guidance.png" alt="Naukri FastForward" style="width: 100%; height: auto;">
        <div class="card-content">
            <h2>Get 3X more profile views from warehouse management recruiters</h2>
            <p>Increase your chances of callback with Naukri FastForward</p>
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

</body>
</html>
