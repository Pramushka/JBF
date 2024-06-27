<?php
include '../includes/dbconn.php'; // Include the database connection file

// Fetch courses
$courses_sql = "SELECT Course_Name, Skill, Industry, Description, Price FROM learning_courses";
$courses_result = $conn->query($courses_sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g==" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/ti-icons@0.1.2/css/themify-icons.css">
    <link rel="stylesheet" href="../assets/css/courses.css">
</head>

<body>

    <div class="popular_courses">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5">
                    <div class="main_title">
                        <h2 class="mb-3">Our Popular Courses</h2>
                        <p>
                            Get access to videos in over 90% of courses, Specializations, and Professional Certificates taught by top instructors from leading universities and companies.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="owl-carousel active_course owl-loaded owl-drag">
                        <div class="owl-stage-outer">
                            <div class="owl-stage">
                                <?php
                                if ($courses_result->num_rows > 0) {
                                    while ($course = $courses_result->fetch_assoc()) {
                                        echo '<div class="owl-item" style="width: 350px; margin-right: 30px;">
                                            <div class="single_course">
                                                <div class="course_head">
                                                    <img class="img-fluid" src="https://www.bootdey.com/image/350x280/FFB6C1/000000" alt="" />
                                                </div>
                                                <div class="course_content">
                                                    <span class="price">$' . htmlspecialchars($course['Price']) . '</span>
                                                    <span class="tag mb-4 d-inline-block">' . htmlspecialchars($course['Industry']) . '</span>
                                                    <h4 class="mb-3">
                                                        <a href="#">' . htmlspecialchars($course['Course_Name']) . '</a>
                                                    </h4>
                                                    <p>' . htmlspecialchars($course['Description']) . '</p>
                                                    <p><strong>Skill:</strong> ' . htmlspecialchars($course['Skill']) . '</p>
                                                    <div class="course_meta d-flex justify-content-lg-between align-items-lg-center flex-lg-row flex-column mt-4">
                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous"></script>


</body>

</html>