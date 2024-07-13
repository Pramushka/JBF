<?php

include '../includes/dbconn.php'; // Include the database connection file
session_start();
$user_id = $_SESSION['user_id']; // Assuming you store the user's ID in the session

// Fetch industries
$industries_sql = "SELECT id, industry_name FROM job_industries";
$industries_result = $conn->query($industries_sql);

// Fetch courses based on industry if selected
$industry_filter = isset($_GET['industry']) ? $_GET['industry'] : '';
$courses_sql = "SELECT id, Course_Name, Skill, Industry, Description, Price FROM learning_courses";
if ($industry_filter) {
    $courses_sql .= " WHERE Industry = '$industry_filter'";
}
$courses_result = $conn->query($courses_sql);


// Enrollment handling
if (isset($_POST['enroll'])) {
    $course_id = $_POST['course_id'];

    // Check if already enrolled
    $check_sql = "SELECT * FROM user_enrollments WHERE user_id = $user_id AND course_id = $course_id";
    $result = $conn->query($check_sql);
    if ($result->num_rows > 0) {
        echo "<script>alert('You are already enrolled in this course.');</script>";
    } else {
        $enroll_sql = "INSERT INTO user_enrollments (user_id, course_id) VALUES ($user_id, $course_id)";
        if ($conn->query($enroll_sql) === TRUE) {
            echo "<script>alert('Enrollment successful!');</script>";
        } else {
            echo "<script>alert('Error enrolling in course: " . $conn->error . "');</script>";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            list-style: none;
            font-family: 'Montserrat', sans-serif;
        }

        p {
            margin: 0;
        }

        .topnav {
            display: flex;
            flex-wrap: wrap;
        }

        .topnav a {
            display: block;
            color: #8d8b8b;
            font-weight: 800;
            font-size: 14px;
            text-transform: uppercase;
            padding: 14px 15px;
            text-decoration: none;
            border-bottom: 3px solid transparent;
            margin-right: 15px;
        }

        .topnav a:hover {
            color: black;
            border-bottom: 3px solid red;
        }

        .topnav .active {
            color: black;
            border-bottom: 3px solid red;
        }

        .category .job {
            height: 280px;
            border: 1px solid transparent;
            padding: 30px 19px 25px 19px;
            border-radius: 5px;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
            cursor: pointer;
        }

        .category .job:hover {
            border: 1px solid #0d6efd;
        }

        .category .job span {
            padding: 6px 20px;
            font-weight: 400;
            border-radius: 26px;
            display: inline-block;
        }

        .category .job .colors1 {
            font-weight: 800;
            color: #F27E42;
            background: #f27e4242;
        }

        .category .job .colors2 {
            font-weight: 800;
            color: #4294F2;
            background: rgba(66, 148, 255, 0.26);
        }

        .category .job .colors3 {
            font-weight: 800;
            color: #2EB98D;
            background: rgba(46, 185, 141, 0.03);
        }

        .category .job .colors4 {
            font-weight: 800;
            color: #6A42F2;
            background: rgba(106, 66, 242, 0.07);
        }

        .category .job .colors5 {
            font-weight: 800;
            color: #F162BC;
            background: rgba(241, 98, 188, 0.07);
        }

        .category .job .colors2 {
            font-weight: 800;
            color: #4294F2;
            background: rgba(66, 148, 255, 0.26);
        }

        a {
            text-decoration: none;
            font-size: 20px;
            font-weight: 600;
            color: #071112;
            text-transform: capitalize;
            margin-bottom: 17px;
            display: block;
        }

        .place {
            display: flex;
            align-items: center;
            font-size: 12px;
            padding-left: 0px;
            color: #76787A;
        }

        .left {
            font-weight: 800;
        }

        .category .job span.time {
            font-weight: 900;
        }

        .btn.btn-primary {
            width: 150px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mt-30 {
            margin-top: 30px;
        }

        .mb-30 {
            margin-bottom: 30px;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 24px;
            color: black;
        }

        .navbar-brand:hover {
            color: red;
        }

        .navbar-nav {
            align-items: center;
        }

        .navbar-nav .nav-item {
            margin-right: 15px;
        }

        @import url('https://fonts.googleapis.com/css?family=Open+Sans&display=swap');

        body {
            background-color: #eeeeee;
            font-family: 'Open Sans', serif;
            font-size: 14px;
        }

        .container-fluid {
            margin-top: 50px;
        }

        .footer-copyright {
            margin-top: 13px;
        }

        a {
            text-decoration: none !important;
            color: #777a7c;
        }

        .description {
            font-size: 12px;
        }

        .fa-facebook-f {
            color: #3b5999;
        }

        .fa-instagram {
            color: #e4405f;
        }

        .fa-youtube {
            color: #cd201f;
        }

        .fa-twitter {
            color: #55acee;
        }

        .logo-footer {
            height: 30px;
        }

        .footer-copyright p {
            margin-top: 10px;
        }

        .footer-top .row {
            justify-content: center;
            text-align: center;
        }

        .footer-top .col-md-4,
        .footer-top .col-sm-3 {
            text-align: left;
        }
    </style>
</head>

<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Courses</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav topnav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link <?php echo $industry_filter == '' ? 'active' : ''; ?>" href="course_dashboard.php">All<span>categories</span></a>
                        </li>
                        <?php
                        if ($industries_result->num_rows > 0) {
                            while ($industry = $industries_result->fetch_assoc()) {
                                echo '<li class="nav-item">
                                    <a class="nav-link ' . ($industry_filter == $industry['industry_name'] ? 'active' : '') . '" href="course_dashboard.php?industry=' . urlencode($industry['industry_name']) . '">' . htmlspecialchars($industry['industry_name']) . '</a>
                                  </li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="row">
            <?php
            if ($courses_result->num_rows > 0) {
                while ($course = $courses_result->fetch_assoc()) {
                    echo '<div class="col-lg-4 col-md-6 col-sm-6">
                        <div class="category mb-30">
                            <div class="job" data-bs-toggle="modal" data-bs-target="#courseModal" data-id="' . $course['id'] . '" data-name="' . htmlspecialchars($course['Course_Name']) . '" data-skill="' . htmlspecialchars($course['Skill']) . '" data-industry="' . htmlspecialchars($course['Industry']) . '" data-description="' . htmlspecialchars($course['Description']) . '" data-price="' . htmlspecialchars($course['Price']) . '">
                                <span class="colors1 mb-4">' . htmlspecialchars($course['Industry']) . '</span>
                                <h5><a href="#">' . htmlspecialchars($course['Course_Name']) . '</a></h5>
                                <ul class="place">
                                    <li>
                                        <p><i class="fas fa-dollar-sign pe-2"></i> $' . htmlspecialchars($course['Price']) . '</p>
                                    </li>
                                </ul>

                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="left">
                                    </div>
                                    <span class="skill">' . htmlspecialchars($course['Skill']) . '</span>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            }
            ?>
            <div class="col-12 d-flex align-items-center justify-content-center">
                
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="courseModal" tabindex="-1" aria-labelledby="courseModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="courseModalLabel">Course Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h5 id="modalCourseName"></h5>
                    <p id="modalCourseIndustry"></p>
                    <p id="modalCourseSkill"></p>
                    <p id="modalCourseDescription"></p>
                    <p id="modalCoursePrice"></p>
                    <form method="POST" action="course_dashboard.php">
                        <input type="hidden" name="course_id" id="modalCourseId">
                        <button type="submit" name="enroll" class="btn btn-primary">Enroll</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <footer class="section-footer border-top">
            <div class="container-fluid">
                <section class="footer-top padding-y">
                    <div class="row">
                        <aside class="col-md-4">
                            <article class="mr-3">
                                <img src="../assets/img/logo/jobforce01.jpg" class="logo-footer" alt="Jobforce Logo">
                                <p class="mt-3 description">Welcome to Jobforce, the leading job searching network with a community of thousands of members in over 50 countries and territories worldwide.</p>
                                </p>
                                <div>
                                    <a class="btn btn-icon btn-light" title="Facebook" target="_blank" href="#" data-abc="true"><i class="fab fa-facebook-f"></i></a>
                                    <a class="btn btn-icon btn-light" title="Instagram" target="_blank" href="#" data-abc="true"><i class="fab fa-instagram"></i></a>
                                    <a class="btn btn-icon btn-light" title="Youtube" target="_blank" href="#" data-abc="true"><i class="fab fa-youtube"></i></a>
                                    <a class="btn btn-icon btn-light" title="Twitter" target="_blank" href="#" data-abc="true"><i class="fab fa-twitter"></i></a>
                                </div>
                            </article>
                        </aside>
                        <aside class="col-sm-3 col-md-2">
                            <h6 class="title">About</h6>
                            <ul class="list-unstyled">
                                <li><a href="../user/about_us.php" data-abc="true">About us</a></li>
                                <li><a href="../user/sitemap.php" data-abc="true">Sitemap</a></li>
                            </ul>
                        </aside>
                        <aside class="col-sm-3 col-md-2">
                            <h6 class="title">Help center</h6>
                            <ul class="list-unstyled">
                                <li><a href="../user/contact.php" data-abc="true">Help center</a></li>
                            </ul>
                        </aside>
                        <aside class="col-sm-3 col-md-2">
                            <h6 class="title">Privacy policy</h6>
                            <ul class="list-unstyled">
                                <li><a href="../user/privacy_policy.php" data-abc="true">Privacy policy</a></li>
                                <li><a href="../user/terms_and_conditions.php" data-abc="true">Terms & conditions</a></li>
                            </ul>
                        </aside>
                    </div>
                </section>
                <section class="footer-copyright border-top">
                    <p class="text-center text-muted">&copy; 2024 Jobforce (Sri Lanka) Ltd. All rights reserved.</p>
                    <p target="_blank" class="text-center text-muted">
                        
                    </p>
                </section>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        var courseModal = document.getElementById('courseModal')
        courseModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget
            var courseId = button.getAttribute('data-id')
            var courseName = button.getAttribute('data-name')
            var courseSkill = button.getAttribute('data-skill')
            var courseIndustry = button.getAttribute('data-industry')
            var courseDescription = button.getAttribute('data-description')
            var coursePrice = button.getAttribute('data-price')

            var modalCourseId = courseModal.querySelector('#modalCourseId')
            var modalCourseName = courseModal.querySelector('#modalCourseName')
            var modalCourseSkill = courseModal.querySelector('#modalCourseSkill')
            var modalCourseIndustry = courseModal.querySelector('#modalCourseIndustry')
            var modalCourseDescription = courseModal.querySelector('#modalCourseDescription')
            var modalCoursePrice = courseModal.querySelector('#modalCoursePrice')

            modalCourseId.value = courseId
            modalCourseName.textContent = courseName
            modalCourseSkill.textContent = 'Skill: ' + courseSkill
            modalCourseIndustry.textContent = 'Industry: ' + courseIndustry
            modalCourseDescription.textContent = courseDescription
            modalCoursePrice.textContent = 'Price: $' + coursePrice
        })
    </script>

</body>

</html>
