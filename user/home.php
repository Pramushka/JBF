<?php
include '../includes/dbconn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
require_once '../includes/dbconn.php';

//FOR THE SEACH BAR OPERATIONS
// Fetching for this Serch bar
$industryResult = $conn->query("SELECT ID, industry_name as name, 'industry' as type FROM job_industries");
$positionResult = $conn->query("SELECT ID, position_name as name, 'position' as type FROM job_positions");

$allOptions = [];

while ($row = $industryResult->fetch_assoc()) {
    $allOptions[] = $row;
}
while ($row = $positionResult->fetch_assoc()) {
    $allOptions[] = $row;
}

//FOR TOP HIRING SECTION
// Fetch the user's industry 
$userIndustryResult = $conn->query("SELECT Job_Industry FROM user WHERE ID = $user_id");
$userIndustry = $userIndustryResult->fetch_assoc()['Job_Industry'];

// SQL to fetch top organization either from the same industry or just the most recent ones
$sql = "SELECT Org_Name, Org_Location, Org_Industry FROM organization WHERE Org_Industry = ? ORDER BY ID DESC LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userIndustry);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // If no organization found in the user's industry, fetch the most recent organization instead
    $result = $conn->query("SELECT Org_Name, Org_Location, Org_Industry FROM organization ORDER BY ID DESC LIMIT 5");
}

$companies = [];
while ($row = $result->fetch_assoc()) {
    $companies[] = $row;
}


// Fetch courses either from the same industry or just the most recent ones
$coursesSql = "SELECT Course_Name, Skill, Industry, Description, Price FROM learning_courses 
               WHERE Industry = ? AND IsDeleted = 0 ORDER BY CreatedOn DESC LIMIT 5";
$stmt = $conn->prepare($coursesSql);
$stmt->bind_param("s", $userIndustry);
$stmt->execute();
$coursesResult = $stmt->get_result();

$courses = [];
if ($coursesResult->num_rows == 0) {
    $coursesResult = $conn->query("SELECT Course_Name, Skill, Industry, Description, Price FROM learning_courses 
                                   WHERE IsDeleted = 0 ORDER BY CreatedOn DESC LIMIT 5");
}
while ($row = $coursesResult->fetch_assoc()) {
    $courses[] = $row;
}


// Check if the Inqury form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $description = $_POST['description'];
    $userId = $_SESSION['user_id']; // Assuming the user ID is stored in session


    //FOR THE STATUS 1 = PENDING
    // Insert into helpdeskinquiries table
    $stmt = $conn->prepare("INSERT INTO helpdeskinquiries (email, Description, CreatedOn, Status) VALUES (?, ?, NOW(), 1)");
    $stmt->bind_param("ss", $email, $description);
    $stmt->execute();
    $helpId = $stmt->insert_id; // Get the ID of the inserted record

    // Link the user with the help desk inquiry
    $stmt = $conn->prepare("INSERT INTO user_helpdeskinquiries (Help_ID, UserID) VALUES (?, ?)");
    $stmt->bind_param("ii", $helpId, $userId);
    $stmt->execute();

    echo "Your inquiry has been submitted successfully. Thank you!";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - JobForce</title>
    <link rel="stylesheet" href="../assets/css/homepage.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
</head>
<body>

<?php include 'navbar.php'; ?>


    <section class="home">
        <div class="home-content">
            <h1>Find your dream job now</h1>
        </div>
    </section>
    
<!-- Search Section -->
<div class="search-container">
    <form action="jobsearch.php" method="GET" class="search-form">
        <div class="search-bar">
            <!-- Industry/Skill Position with Icon -->
            <div class="search-field">
                <i class="fas fa-briefcase"></i>
                <select class="form-control" id="industry_skill_position" name="industry_skill_position">
                    <?php foreach ($allOptions as $option): ?>
                        <option value="<?= $option['ID'] ?>"><?= $option['name'] ?> (<?= $option['type'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- Experience with Icon -->
            <div class="search-field">
                <i class="fas fa-calendar-alt"></i>
                <select class="form-control" name="experience" id="experience">
                    <option value="0">Fresher (less than a year)</option>
                    <?php for ($i = 1; $i <= 30; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?> Year<?= $i > 1 ? 's' : '' ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <!-- Location with Icon -->
            <div class="search-field">
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" name="location" placeholder="Enter Location">
            </div>
            <!-- Submit Button -->
            <button class="search-button">Find Jobs</button>
        </div>
    </form>
</div>

<br>
<br>

<div class="sponsored-companies">
    <h2>Top companies hiring now</h2>
    <div class="company-grid">
        <?php foreach ($companies as $company): ?>
            <div class="company-card" style="background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,0.1); padding: 20px; border-radius: 8px; text-align: center; margin-bottom: 20px;">
                <!-- Using a default image if the specific company image does not exist -->
                <img src="../assets/img/default_company_logo.png" alt="<?= htmlspecialchars($company['Org_Name']) ?>" style="height: 100px; width: auto; margin-bottom: 10px;">
                <h3 style="margin-top: 10px; margin-bottom: 5px;"><?= htmlspecialchars($company['Org_Name']) ?></h3>
                <p style="margin-bottom: 5px;"><strong>Industry:</strong> <?= htmlspecialchars($company['Org_Industry']) ?></p>
                <p style="margin-bottom: 10px;"><?= htmlspecialchars($company['Org_Location']) ?></p>
                <!-- Button to view jobs, linking to a job search page filtered by this company -->
                <button onclick="window.location.href='jobsearch.php?company=<?= urlencode($company['Org_Name']) ?>'" style="padding: 8px 16px; background-color: #007BFF; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">View Jobs</button>
            </div>
        <?php endforeach; ?>
    </div>
</div>



<div class="courses-container">
    <h2>Top Learning Courses</h2>
    <div class="courses-list">
        <?php foreach ($courses as $course): ?>
            <div class="course">
                <h3><?= htmlspecialchars($course['Course_Name']) ?></h3>
                <p><strong>Skill Focus:</strong> <?= htmlspecialchars($course['Skill']) ?></p>
                <p><strong>Industry:</strong> <?= htmlspecialchars($course['Industry']) ?></p>
                <p><?= htmlspecialchars($course['Description']) ?></p>
                <p><strong>Price:</strong> $<?= number_format($course['Price'], 2) ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="helpdesk-form-container">
    <h2>Contact Help Desk</h2>
    <form action="home.php" method="POST">
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="description">Message:</label>
            <textarea id="description" name="description" required></textarea>
        </div>
        <button type="submit">Submit</button>
    </form>
</div>

<script>
$(document).ready(function() {
    $('#industry_skill_position').select2({
        placeholder: 'Select Industry/Skill/Position',
        allowClear: true
    });

    $('#experience').select2({
        placeholder: 'Select Experience',
        allowClear: true
    });
});
</script>

<?php include 'footer.php'; ?>

</body>
</html>
