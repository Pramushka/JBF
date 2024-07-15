<?php
include '../includes/dbconn.php';
//FOR THE SEACH BAR OPERATIONS
// Fetching for this Serch bar
// Get the name of the current script to dynamically set active class
$current_page = basename($_SERVER['SCRIPT_NAME']);

?>


<!doctype html>
<html lang="en">
<head>
    <title>JobForce - Find Your Career</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,100,300,700" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/text.css">
</head>
<body>
    <div class="wrap">
        <div class="container">
            <div class="row justify-content-between">
                <div class="col">
                    <p class="mb-0 phone"><span class="fa fa-phone"></span> <a href="#">+94 1125 555 555</a></p>
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
                       
                    </div>
                </li>
                <li class="nav-item <?= ($current_page == 'course_dashboard.php') ? 'active' : ''; ?>"><a href="course_dashboard.php" class="nav-link">Courses</a></li>
                <li class="nav-item <?= ($current_page == 'about_us.php') ? 'active' : ''; ?>"><a href="about_us.php" class="nav-link">About Us</a></li>
                <li class="nav-item <?= ($current_page == 'contact.php') ? 'active' : ''; ?>"><a href="contact.php" class="nav-link">Contact</a></li>
                <li class="nav-item dropdown <?= ($current_page == 'userprofile2.php' || $current_page == 'logout.php') ? 'active' : ''; ?>">
                    <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-user"></i></a>
                    <div class="dropdown-menu" aria-labelledby="profileDropdown">
                        <a class="dropdown-item" href="userprofile2.php">Profile</a>
                        <a class="dropdown-item" href="user_courses.php">My courses</a>
                        <a class="dropdown-item" href="uOrganizationList.php">My Companies</a>
                        <a class="dropdown-item" href="logout.php">LogOut</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <script src="../assets/js/jquery.min.js"></script>
    <script src="../assets/js/popper.js"></script>
    <script src="../assets/js/bootstrap.min.js"></script>
    <script src="../assets/js/main.js"></script>
</body>
</html>

