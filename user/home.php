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

// SQL to fetch top organizations either from the same industry or just the most recent ones, now including the ID
$sql = "SELECT ID, Org_Name, Org_Location, Org_Industry FROM organization WHERE Org_Industry = ? ORDER BY ID DESC LIMIT 5";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userIndustry);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // If no organization found in the user's industry, fetch the most recent organizations instead
    $result = $conn->query("SELECT ID, Org_Name, Org_Location, Org_Industry FROM organization ORDER BY ID DESC LIMIT 5");
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

   // Set a session variable to show the alert after redirecting
   $_SESSION['alert'] = "Your inquiry has been submitted successfully. Thank you!";
    
   // Redirect to the same page to avoid form re-submission issues
   header("Location: " . $_SERVER['PHP_SELF']);
   exit;
}

// Check if there is a session alert set and show it
if (isset($_SESSION['alert'])) {
    echo "<script>alert('" . $_SESSION['alert'] . "');</script>";
    // Clear the session alert so it doesn't keep appearing
    unset($_SESSION['alert']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home - JobForce</title>
    <link rel="stylesheet" href="../assets/css/homepage.css">
    <link rel="stylesheet" href="../assets/css/single_input.css">
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/cardtemp.css"> 
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
<br>
<br>
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
                <button onclick="window.location.href='single_organization.php?id=<?= $company['ID'] ?>'" style="padding: 8px 16px; background-color: #007BFF; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 16px;">View Jobs</button>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<br>
<br>
<br>
<br>
<br>
<br>
        <!--This is the Course Content Section-->
<div class="sponsored-companies">
    <h2>Top Learning Courses</h2>
    <div class="company-grid">
        <?php foreach ($courses as $course): ?>
            <div class="card" >
                <!-- Placeholder for course image or icon -->
                <img class="image">
                <div class="content">
                    <a href="#">
                        <span class="title">
                            <?= htmlspecialchars($course['Course_Name']) ?>
                        </span>
                    </a>   
                    <p><strong>Skill Focus:</strong> <?= htmlspecialchars($course['Skill']) ?></p>
                    <p><strong>Industry:</strong> <?= htmlspecialchars($course['Industry']) ?></p>
                    <p><?= htmlspecialchars($course['Description']) ?></p>
                    <p><strong>Price:</strong> $<?= number_format($course['Price'], 2) ?></p>
                    <!-- Optional button if you want to add actions like 'Enroll Now' -->
                    <a class="action" href="#">
                        Find out more
                        <span aria-hidden="true">
        →
                        </span>
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>


<br>
<br>

    <!-- Job Section -------------------------------------------------------------------------------------->
        <div class="job-section">
 
        <h1>The Fastest Track to Your Next Job</h1>
    
    <!-- Create your Profile -->
        <div class="job-item">
            <img src="../assets/img/home_page/create_your_profile.png" alt="Create your Profile">
            <h2>Create your Profile</h2>
            <p>Craft a Personalized Profile: Showcase Your Skills, Achievements, and Ambitions.</p>
        </div>

    <!-- Explore job -->
        <div class="job-item">
            <img src="../assets/img/home_page/explore_job.jpg" alt="Explore job">
            <h2>Explore job</h2>
            <p>Unlock Your Career Potential: Find, Apply, and Succeed - Make it Simple and Effective.</p>
        </div>

    <!-- Get hired -->
        <div class="job-item">
            <img src="../assets/img/home_page/get_heired.jpg" alt="Get heired">
            <h2>Get heired</h2>
            <p>Discover Opportunities, Apply with Confidence, and Secure Your Dream Job. Your Next Career Move is Just a Click Away.</p>
        </div>
    </div>

<br>
<br>
    <!-- Build Post Section -->
<div class="build-post">
    <!-- Topic: You want to add topic addddddddddd --------------------------------------------->
    <h1></h1>
    
    <div class="post-item">
        <img src="../assets/img/others/image1.png" alt="Build Your Job Post">
        <div>
            <h2>Build Your Job Post</h2>
            <p>Join our dynamic team and be a part of a vibrant workplace that values innovation, collaboration, and growth. We are seeking enthusiastic individuals with a passion for Developer to contribute their skills and expertise.</p>
        </div>
    </div>
    <div class="post-item">
        <img src="../assets/img/others/image2.png" alt="Post Your Job">
        <div>
            <h2>Post Your Job</h2>
            <p>Join our dynamic team and be a part of a vibrant workplace that values innovation, collaboration, and growth. We are seeking enthusiastic individuals with a passion for Developer to contribute their skills and expertise.</p>
        </div>
    </div>
</div>

<br>
<br>

<div class="helpdesk-form-container" style="background-color: #f2f2f2; height: 400px; display: flex; align-items: center; justify-content: center;">
    <div style="width: 100%; max-width: 1200px; display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding: 20px;">
        <!-- Contact Form -->
        <div>
            <h2>Contact Help Desk</h2>
            <form action="home.php" method="POST">
                <div class="inputGroup">
                    <input type="email" id="email" name="email" required="" autocomplete="off" placeholder="Email">
                    <label for="email"></label>
                </div>
                <div class="inputGroup">
                    <input type="textarea" id="description" name="description" required="" autocomplete="off" placeholder="Message">
                    <label for="description"></label>
                </div>

                <button  class="btn" type="submit">Submit</button>
            </form>
        </div>
        <!-- Instructions or Additional Content -->
        <div>
            <h2>How can we help you?</h2>
            <br>
            <p>Please fill out the form with your query or any issue you are facing, and our helpdesk team will get back to you as soon as possible.</p>
            <br>
            <p>By fillig this you will automatically accept our policies and you will recive our new updates and news to youre email</p>
        </div>
    </div>
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
<br>
<?php include 'footer.php'; ?>

</body>
</html>
