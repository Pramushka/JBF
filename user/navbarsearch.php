<?php
include '../includes/dbconn.php';
//FOR THE SEACH BAR OPERATIONS
// Fetching for this Serch bar
// Get the name of the current script to dynamically set active class
$current_page = basename($_SERVER['SCRIPT_NAME']);
$industryResult = $conn->query("SELECT ID, industry_name as name, 'industry' as type FROM job_industries");
$positionResult = $conn->query("SELECT ID, position_name as name, 'position' as type FROM job_positions");

$allOptions = [];

while ($row = $industryResult->fetch_assoc()) {
    $allOptions[] = $row;
}
while ($row = $positionResult->fetch_assoc()) {
    $allOptions[] = $row;
}
?>


<!doctype html>
<html lang="en">
<head>
    <title>JobForce - Find Your Career</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,700" rel="stylesheet">
    
    <link rel="stylesheet" href="../assets/css/text.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>

.search-bar-container {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
    margin-top: 20px;
    margin-bottom: 20px;
    max-width: 100%;
}

.search-bar {
    display: flex;
    align-items: center;
    background: #ffffff;
    padding: 10px;
    border-radius: 5px;
    width: 80%;
    max-width: 1000px;
    height: 60px;
    margin: 0 auto;
    border: 1px solid #ccc;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.search-field {
    display: flex;
    align-items: center;
    padding: 0 20px;
    flex: 1;
    border-right: 1px solid #ccc;
}

.search-field:last-child {
    border-right: none;
}

.search-field i {
    margin-right: 10px;
    color: #888;
}

.search-field input,
.search-field select {
    border: none;
    outline: none;
    font-size: 16px;
    width: 100%;
}

.search-field input::placeholder {
    color: #ccc;
}

.search-field select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background: none;
    padding: 0 10px;
    font-size: 16px;
}

.search-button {
    background: #03045E;
    color: white;
    border: none;
    border-radius: 50px;
    padding: 10px 20px;
    font-size: 16px;
    cursor: pointer;
    transition: background 0.3s;
    margin-left: 10px;
}

.search-button:hover {
    background: #0077B6;
}



    </style>
</head>
<body>
<div class="wrap">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col">
                    <p class="mb-0 phone"><span class="fa fa-phone"></span> <a href="#">+00 1234 567</a></p>
                </div>
                <div class="col d-flex justify-content-end">
                    <div class="social-media">
                        <p class="mb-0 d-flex">
                            <a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-facebook"><i class="sr-only">Facebook</i></span></a>
                            <a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-twitter"><i class="sr-only">Twitter</i></span></a>
                            <a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-instagram"><i class="sr-only">Instagram</i></span></a>
                            <a href="#" class="d-flex align-items-center justify-content-center"><span class="fa fa-dribbble"><i class="sr-only">Dribbble</i></span></a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container">
        <a class="navbar-brand" href="home.php">JobForce <span>Find your Career</span></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="fa fa-bars"></span> Menu
        </button>
        <div class="collapse navbar-collapse" id="ftco-nav">
            <ul class="navbar-nav m-auto">
                <li class="nav-item <?= ($current_page == 'home.php') ? 'active' : ''; ?>"><a href="home.php" class="nav-link">Home</a></li>
                <li class="nav-item <?= ($current_page == 'jobsearch.php') ? 'active' : ''; ?>"><a href="jobsearch.php" class="nav-link">Search Jobs</a></li>
                <li class="nav-item dropdown <?= ($current_page == 'allorganization.php' || $current_page == 'uOrganizationList.php') ? 'active' : ''; ?>">
                    <a class="nav-link dropdown-toggle" href="#" id="dropdown04" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Organization</a>
                    <div class="dropdown-menu" aria-labelledby="dropdown04">
                        <a class="dropdown-item" href="allorganization.php">Top Hiring</a>
                        <a class="dropdown-item" href="uOrganizationList.php">My Companies</a>
                    </div>
                </li>
                <li class="nav-item <?= ($current_page == 'course_dashboard.php') ? 'active' : ''; ?>"><a href="course_dashboard.php" class="nav-link">Courses</a></li>
                <li class="nav-item <?= ($current_page == 'about_us.php') ? 'active' : ''; ?>"><a href="about_us.php" class="nav-link">About Us</a></li>
                <li class="nav-item <?= ($current_page == 'contact.php') ? 'active' : ''; ?>"><a href="contact.php" class="nav-link">Contact</a></li>
                <li class="nav-item dropdown <?= ($current_page == 'userprofile2.php' || $current_page == 'logout.php') ? 'active' : ''; ?>">
                    <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i></a>
                    <div class="dropdown-menu" aria-labelledby="profileDropdown">
                        <a class="dropdown-item" href="userprofile2.php">Profile</a>
                        <a class="dropdown-item" href="logout.php">LogOut</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
<div class="search-container">
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
    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/popper.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>
