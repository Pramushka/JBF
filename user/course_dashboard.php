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

    <div class="container">
        <h1>Explore courses</h1>
        <nav>
            <ul>
                <li><a href="#all-courses" class="tab-link active">All courses</a></li>
                <li><a href="#business" class="tab-link">Business</a></li>
                <li><a href="#technology" class="tab-link">Technology</a></li>
                <li><a href="#creative" class="tab-link">Creative</a></li>
            </ul>
        </nav>
        <div id="all-courses" class="tab-content active">
            <h2>Trending Courses</h2>
            <div class="course-list">
                <div class="course" data-course-id="1">
                    <a href="course1.html">
                        <img src="../assets/img/Our_Popular_Courses/Career_Advice_from_Some_of _the_Biggest_Names_in_Business.jpg" alt="Course 1">
                        <h3>Career Advice from Some of the Biggest Names in...</h3>
                        <p>2,484,258 viewers</p>
                    </a>
                </div>
                <div class="course" data-course-id="2">
                    <a href="course2.html">
                        <img src="../assets/img/Our_Popular_Courses/LOOKUP_Function_in_Excel.png" alt="Course 2">
                        <h3>Excel: Lookup Functions in Depth</h3>
                        <p>1,710,993 viewers</p>
                    </a>
                </div>
                <div class="course" data-course-id="3">
                    <a href="course3.html">
                        <img src="../assets/img/Our_Popular_Courses/What_Is_Generative_AI.png" alt="Course 3">
                        <h3>What Is Generative AI?</h3>
                        <p>1,244,802 viewers</p>
                    </a>
                </div>
                <div class="course" data-course-id="4">
                    <a href="course4.html">
                        <img src="../assets/img/Our_Popular_Courses/Expert_Tips_for_Answering_Common_Interview_Questions_2.jpg" alt="Course 4">
                        <h3>Expert Tips for Answering Common Interview...</h3>
                        <p>2,366,150 viewers</p>
                    </a>
                </div>
                <div class="course" data-course-id="5">
                    <a href="course5.html">
                        <img src="../assets/img/Our_Popular_Courses/Electronics_Foundations_Basic_Circuits.jpg" alt="Course 5">
                        <h3>Electronics Foundations: Basic Circuits</h3>
                        <p>812,231 viewers</p>
                    </a>
                </div>
            </div>
        </div>
        <div id="business" class="tab-content">
            <h2>Trending Business Courses</h2>
            <div class="course-list">
                <div class="course" data-course-id="6">
                    <a href="course6.html">
                        <img src="../assets/img/Our_Popular_Courses/Career_Advice_from_Some_of _the_Biggest_Names_in_Business.jpg" alt="Course 1">
                        <h3>Career Advice from Some of the Biggest Names in...</h3>
                        <p>2,484,258 viewers</p>
                    </a>
                </div>
                <div class="course" data-course-id="7">
                    <a href="course7.html">
                        <img src="../assets/img/Our_Popular_Courses/Expert_Tips_for_Answering_Common_Interview_Questions.jpg" alt="Course 2">
                        <h3>Expert Tips for Answering Common Interview...</h3>
                        <p>2,366,150 viewers</p>
                    </a>
                </div>
                <div class="course" data-course-id="8">
                    <a href="course8.html">
                        <img src="../assets/img/Our_Popular_Courses/Ken_Blanchard_on_Servant_Leadership.jpg" alt="Course 3">
                        <h3>Ken Blanchard on Servant Leadership</h3>
                        <p>2,045,573 viewers</p>
                    </a>
                </div>
                <div class="course" data-course-id="9">
                    <a href="course9.html">
                        <img src="../assets/img/Our_Popular_Courses/Excel_Tips_Weekly.png" alt="Course 4">
                        <h3>Excel Tips Weekly</h3>
                        <p>1,862,544 viewers</p>
                    </a>
                </div>
                <div class="course" data-course-id="10">
                    <a href="course10.html">
                        <img src="../assets/img/Our_Popular_Courses/LOOKUP_Function_in_Excel.png" alt="Course 5">
                        <h3>Excel: Lookup Functions in Depth</h3>
                        <p>1,710,993 viewers</p>
                    </a>
                </div>
            </div>
        </div>
        <div id="technology" class="tab-content">
            <h2>Trending Technology Courses</h2>
            <div class="course-list">
                <div class="course" data-course-id="11">
                    <a href="course11.html">
                        <img src="../assets/img/Our_Popular_Courses/Introduction_to_Python.jpg" alt="Course 1">
                        <h3>Introduction to Python</h3>
                        <p>1,234,567 viewers</p>
                    </a>
                </div>
                <div class="course" data-course-id="12">
                    <a href="course12.html">
                        <img src="../assets/img/Our_Popular_Courses/Advanced_Java_Programming.jpg" alt="Course 2">
                        <h3>Advanced Java Programming</h3>
                        <p>987,654 viewers</p>
                    </a>
                </div>
                <div class="course" data-course-id="13">
                    <a href="course13.html">
                        <img src="../assets/img/Our_Popular_Courses/Machine_Learning_Basics.png" alt="Course 3">
                        <h3>Machine Learning Basics</h3>
                        <p>1,111,222 viewers</p>
                    </a>
                </div>
                <div class="course" data-course-id="14">
                    <a href="course14.html">
                        <img src="../assets/img/Our_Popular_Courses/Web_Development.jpg" alt="Course 4">
                        <h3>Web Development with HTML, CSS, and JavaScript</h3>
                        <p>1,333,444 viewers</p>
                    </a>
                </div>
                <div class="course" data-course-id="15">
                    <a href="course15.html">
                        <img src="../assets/img/Our_Popular_Courses/Data_Science_with_R.jpg" alt="Course 5">
                        <h3>Data Science with R</h3>
                        <p>999,888 viewers</p>
                    </a>
                </div>
            </div>
        </div>
        <div id="creative" class="tab-content">
            <h2>Trending Creative Courses</h2>
            <div class="course-list">
                <div class="course" data-course-id="16">
                    <a href="course16.html">
                        <img src="../assets/img/Our_Popular_Courses/Graphic_Design_Fundamentals.png" alt="Course 1">
                        <h3>Graphic Design Fundamentals</h3>
                        <p>567,890 viewers</p>
                    </a>
                </div>
                <div class="course" data-course-id="17">
                    <a href="course17.html">
                        <img src="../assets/img/Our_Popular_Courses/Photography_Essentials.jpg" alt="Course 2">
                        <h3>Photography Essentials</h3>
                        <p>432,109 viewers</p>
                    </a>
                </div>
                <div class="course" data-course-id="18">
                    <a href="course18.html">
                        <img src="../assets/img/Our_Popular_Courses/Introduction_to_Video_Editing_2.png" alt="Course 3">
                        <h3>Introduction to Video Editing</h3>
                        <p>654,321 viewers</p>
                    </a>
                </div>
                <div class="course" data-course-id="19">
                    <a href="course19.html">
                        <img src="../assets/img/Our_Popular_Courses/Creative_Writing_Techniques.jpg" alt="Course 4">
                        <h3>Creative Writing Techniques</h3>
                        <p>876,543 viewers</p>
                    </a>
                </div>
                <div class="course" data-course-id="20">
                    <a href="course20.html">
                        <img src="../assets/img/Our_Popular_Courses/Music_Production_Basics.jpg" alt="Course 5">
                        <h3>Music Production Basics</h3>
                        <p>765,432 viewers</p>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabLinks = document.querySelectorAll('.tab-link');
            const tabContents = document.querySelectorAll('.tab-content');

            tabLinks.forEach(link => {
                link.addEventListener('click', function(event) {
                    event.preventDefault();

                    // Remove active class from all links
                    tabLinks.forEach(link => link.classList.remove('active'));

                    // Hide all tab contents
                    tabContents.forEach(content => content.classList.remove('active'));

                    // Add active class to the clicked link
                    this.classList.add('active');

                    // Show the corresponding tab content
                    const tabId = this.getAttribute('href');
                    document.querySelector(tabId).classList.add('active');
                });
            });

            // Handle click on each course to navigate to a new page
            const courses = document.querySelectorAll('.course');
            courses.forEach(course => {
                course.addEventListener('click', function() {
                    // Get the course ID or any other identifier
                    const courseId = this.getAttribute('data-course-id');

                    // Navigate to the respective course page ---------------------------- Methanata courses pages tika danna thiyenne
                    switch (courseId) {
                        case '1':
                            window.location.href = 'course1.html';
                            break;
                        case '2':
                            window.location.href = 'course2.html';
                            break;
                        case '3':
                            window.location.href = 'course3.html';
                            break;
                        case '4':
                            window.location.href = 'course4.html';
                            break;
                        case '5':
                            window.location.href = 'course5.html';
                            break;
                        case '6':
                            window.location.href = 'course6.html';
                            break;
                        case '7':
                            window.location.href = 'course7.html';
                            break;
                        case '8':
                            window.location.href = 'course8.html';
                            break;
                        case '9':
                            window.location.href = 'course9.html';
                            break;
                        case '10':
                            window.location.href = 'course10.html';
                            break;
                        case '11':
                            window.location.href = 'course11.html';
                            break;
                        case '12':
                            window.location.href = 'course12.html';
                            break;
                        case '13':
                            window.location.href = 'course13.html';
                            break;
                        case '14':
                            window.location.href = 'course14.html';
                            break;
                        case '15':
                            window.location.href = 'course15.html';
                            break;
                        case '16':
                            window.location.href = 'course16.html';
                            break;
                        case '17':
                            window.location.href = 'course17.html';
                            break;
                        case '18':
                            window.location.href = 'course18.html';
                            break;
                        case '19':
                            window.location.href = 'course19.html';
                            break;
                        case '20':
                            window.location.href = 'course20.html';
                            break;
                        default:
                            break;
                    }
                });
            });
        });
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js" integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw==" crossorigin="anonymous"></script>


<?php include 'footer.php'; ?>

</body>

</html>