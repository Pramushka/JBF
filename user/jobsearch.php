<?php
// Start the PHP session and include necessary files
session_start();
include '../includes/dbconn.php';

// Initialize variables
$industrySkillPosition = '';
$experience = '';
$location = '';

// Check if the page was accessed with a GET request
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $industrySkillPosition = $_GET['industry_skill_position'] ?? 'Not Specified';
    $experience = $_GET['experience'] ?? 'Not Specified';
    $location = $_GET['location'] ?? 'Not Specified';
}
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
</div>

        <div class="main-content">
            <div class="search-tags">
            <?php if (!empty($industrySkillPosition) && $industrySkillPosition != 'Not Specified'): ?>
                <span class="tag">Industry/Skill/Position: <?= htmlspecialchars($industrySkillPosition) ?></span>
            <?php endif; ?>
            <?php if (!empty($experience) && $experience != 'Not Specified'): ?>
                <span class="tag">Experience: <?= htmlspecialchars($experience) ?> years</span>
            <?php endif; ?>
            <?php if (!empty($location) && $location != 'Not Specified'): ?>
                <span class="tag">Location: <?= htmlspecialchars($location) ?></span>
            <?php endif; ?>
        </div>
            <div class="job-listings">
                <!-- Placeholder for dynamic job listings -->
                <?php if (!empty($jobs)): ?>
                    <?php foreach ($jobs as $job): ?>
                        <div class="job-card">
                            <h3><?= $job['title'] ?></h3>
                            <p><?= $job['description'] ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="job-card not-found">
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
