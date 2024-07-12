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
$sql = "SELECT ID, Org_Name, Org_Location, Org_Industry FROM organization WHERE Org_Industry = ? ORDER BY ID DESC ";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userIndustry);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    // If no organization found in the user's industry, fetch the most recent organizations instead
    $result = $conn->query("SELECT ID, Org_Name, Org_Location, Org_Industry FROM organization ORDER BY ID DESC");
}

$companies = [];
while ($row = $result->fetch_assoc()) {
    $companies[] = $row;
}


// Fetch courses either from the same industry or just the most recent ones
$coursesSql = "SELECT Course_Name, Skill, Industry, Description, Price FROM learning_courses 
               WHERE Industry = ? AND IsDeleted = 0 ORDER BY CreatedOn DESC LIMIT 6";
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
    <link rel="stylesheet" href="../assets/css/nhome.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Red+Hat+Display:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

</head>
<body>

<?php include 'navbar.php'; ?>


<section class="home">
    <div class="home-content">
        <h1>FIND YOUR DREAM JOB NOW</h1>
        <p>Welcome to Jobforce, the leading job searching network with a community of thousands of members in over 50 countries and territories worldwide</p>
        <div class="form-container">
            <form action="jobsearch.php" method="GET" class="search-form">
                <div class="search-bar">
                    <!-- Industry/Skill Position with Icon -->
                    <div class="search-field">
                        <i class="fas fa-briefcase"></i>
                        <select class="form-control" id="industry_skill_position" name="industry_skill_position">
                            <?php foreach ($allOptions as $option): ?>
                                <option value="<?= $option['name'] ?>"><?= $option['name'] ?> (<?= $option['type'] ?>)</option>
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
    </div>
</section>

<br>

<h1 class="latest-companies-title">LATEST COMPANIES HIRING NOW</h1>
<div class="latest-companies-container">
    <div class="company-grid">
        <?php foreach ($companies as $company): ?>
            <div class="company-card">
                <div class="company-logo">
                    <img src="../assets/img/company_logo/C01.gif" alt="<?= htmlspecialchars($company['Org_Name']) ?>">
                </div>
                <div class="company-name"><?= htmlspecialchars($company['Org_Name']) ?></div>
                <div class="company-info"><?= htmlspecialchars($company['Org_Industry']) ?></div>
                <div class="company-info"><?= htmlspecialchars($company['Org_Location']) ?></div>
                <button class="view-jobs-btn" onclick="window.location.href='single_organization.php?id=<?= $company['ID'] ?>'">View Jobs</button>
            </div>
        <?php endforeach; ?>
    </div>
    
</div>




<div class="ser-container">
        <h1 class="services-title">Discover Our Services</h1><br>
        <div class="services-grid">
            <div class="service-card">
                <i class="fas fa-file-alt"></i>
                <h3>Resume Building</h3>
                <p>Create a professional resume that stands out and impresses recruiters.</p>
            </div>
            <div class="service-card">
                <i class="fas fa-bell"></i>
                <h3>Job Alerts</h3>
                <p>Receive timely notifications about new job openings matching your skills.</p>
            </div>
            <div class="service-card">
                <i class="fas fa-chalkboard-teacher"></i>
                <h3>Interview Preparation</h3>
                <p>Prepare thoroughly with expert tips and mock interview sessions.</p>
            </div>
            <div class="service-card">
                <i class="fas fa-user-tie"></i>
                <h3>Career Counseling</h3>
                <p>Get personalized advice from career experts to navigate your career path.</p>
            </div>
            <div class="service-card">
                <i class="fas fa-laptop-code"></i>
                <h3>Skill Development</h3>
                <p>Enhance your skills through certified courses tailored to industry demands.</p>
            </div>
        </div>
        <br>
    </div>


<br>
<br>

<div class="learning-courses-section">
        <div class="learning-courses-container">
            <div class="courses-carousel">
                <h2 class="courses-title">Top Learning Courses</h2>
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php foreach ($courses as $course): ?>
                            <div class="swiper-slide course-card">
                                <a href="#">
                                    <span class="title"><?= htmlspecialchars($course['Course_Name']) ?></span>
                                </a>
                                <p><strong>Skill Focus:</strong> <?= htmlspecialchars($course['Skill']) ?></p>
                                <p><strong>Industry:</strong> <?= htmlspecialchars($course['Industry']) ?></p>
                                <p><?= htmlspecialchars($course['Description']) ?></p>
                                <p><strong>Price:</strong> $<?= number_format($course['Price'], 2) ?></p>
                                <a class="action" href="#">Find out more</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <!-- Add Arrows -->
                    <div class="swiper-button-next"></div>
                    <div class="swiper-button-prev"></div>
                </div>
            </div>
        </div>
    </div>



<br>
<br>


<div class="helpdesk-form-section">
        <div class="helpdesk-form-container">
            <div class="form-column">
                <h2>Stay Updated with Our Latest News</h2>
                <form action="home.php" method="POST">
                    <div class="inputGroup">
                        <input type="email" id="email" name="email" required autocomplete="off" placeholder="Email">
                    </div>
                    <div class="inputGroup">
                        <input type="textarea" id="description" name="description" required autocomplete="off" placeholder="Message">
                    </div>
                    <button class="btn" type="submit">Submit</button>
                </form>
            </div>
            <div class="text-column">
                <p>Subscribe to our newsletter to receive the latest updates, news, and special offers directly in your inbox. Simply fill out the form below with your email address and message, and we'll make sure you're always in the know.</p>
            </div>
        </div>
    </div>

<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
<script>
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 'auto',
            spaceBetween: 20,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            breakpoints: {
                1024: {
                    slidesPerView: 'auto',
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 'auto',
                    spaceBetween: 15,
                },
                640: {
                    slidesPerView: 'auto',
                    spaceBetween: 10,
                }
            }
        });
    </script>
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
