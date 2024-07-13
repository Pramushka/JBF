<?php
include '../includes/dbconn.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("You must log in to view your courses.");
}

$user_id = $_SESSION['user_id'];

// Fetch enrolled courses with their content aggregated
$enrolled_courses_sql = "SELECT lc.ID, lc.Course_Name, lc.Skill, lc.Industry, lc.Description, lc.Price, 
    GROUP_CONCAT(cont.Content SEPARATOR '|||') AS Contents,
    GROUP_CONCAT(cont.Content_File_path SEPARATOR '|||') AS FilePaths
FROM learning_courses lc 
JOIN user_enrollments ue ON lc.ID = ue.course_id 
LEFT JOIN learning_content cont ON lc.ID = cont.Course_ID AND cont.IsDeleted = 0
WHERE ue.user_id = $user_id
GROUP BY lc.ID";
$enrolled_courses_result = $conn->query($enrolled_courses_sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Courses</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
    <style>
        
        body {
            background-color: #B3E5FC;
        }

        .card {
            border: none;
            border-radius: 20px;
            transition: all 0.5s;
            cursor: pointer;
        }

        .card:hover {
            transform: scale(1.1);
        }

        .heading {
            font-size: 32px;
            font-weight: bold;
            text-align: center;
        }

        .square {
            background-color: #fedcdd;
            height: 30px;
            width: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .email {
            font-size: 14px;
            margin-left: 25px;
            font-weight: bold;
        }

        .dummytext {
            font-size: 12px;
            font-weight: normal;
            color: #848590;
        }

        .icons i {
            color: #FA222A;
            margin-left: 25px;
        }

        .icons span {
            font-size: 13px;
            font-weight: normal;
            color: #848590;
        }

        .square1 {
            background-color: #cfe3fe;
            height: 30px;
            width: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icons1 i {
            color: #497eea;
            margin-left: 25px;
        }

        .icons1 span {
            font-size: 13px;
            font-weight: normal;
            color: #848590;
        }

        .square2 {
            background-color: #ffefc5;
            height: 30px;
            width: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icons2 i {
            color: #ffc227;
            margin-left: 25px;
        }

        .icons2 span {
            font-size: 13px;
            font-weight: normal;
            color: #848590;
        }

        .square5 {
            background-color: #41cfff;
            height: 30px;
            width: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icons5 i {
            color: #41cfff;
            margin-left: 25px;
        }

        .icons5 span {
            font-size: 13px;
            font-weight: normal;
            color: #848590;
        }

        .square4 {
            background-color: #eae6fd;
            height: 30px;
            width: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icons4 i {
            color: #6a35ff;
            margin-left: 25px;
        }

        .icons4 span {
            font-size: 13px;
            font-weight: normal;
            color: #848590;
        }

        .square3 {
            background-color: #fedfce;
            height: 30px;
            width: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icons3 i {
            color: #ff8339;
            margin-left: 25px;
        }

        .icons3 span {
            font-size: 13px;
            font-weight: normal;
            color: #848590;
        }

       

        .center-title {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
    
</head>
<body>
    <?php include 'navbarsearch.php'; ?>

    <div class="container mt-3 mb-3">
        <h2 class="text-center mb-4">Enrolled Courses</h2>
        <div class="row mt-1 g-4">
            <?php
            if ($enrolled_courses_result->num_rows > 0) {
                while ($course = $enrolled_courses_result->fetch_assoc()) {
                    $defaultImage = '../assets/img/others/image2.png'; // Path to your default course image
                    echo '<div class="col-md-4">
                        <div class="card p-3" data-bs-toggle="modal" data-bs-target="#courseModal" data-course=\'' . json_encode($course) . '\'>
                            <div class="d-flex p-1 px-4 align-items-center"> 
                                <img src="' . $defaultImage . '" height="200" width="100%" style="border-radius: 20px;" />
                            </div>
                            <div class="email mt-1"> 
                                <span>' . htmlspecialchars($course['Course_Name']) . '</span>
                                <div class="dummytext mt-1">
                                    <span>' . htmlspecialchars($course['Description']) . '</span><br>
                                    <span>Industry: ' . htmlspecialchars($course['Industry']) . '</span><br>
                                    <span>Skill: ' . htmlspecialchars($course['Skill']) . '</span><br>
                                    <span>Price: $' . number_format($course['Price'], 2) . '</span>
                                </div>
                            </div>
                        </div>
                    </div>';
                }
            } else {
                echo '<p class="text-center">No courses enrolled.</p>';
            }
            ?>
        </div>
    </div>

<!-- Modal for Course Details -->
<div class="modal fade" id="courseModal" tabindex="-1" aria-labelledby="courseModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="courseModalLabel">Course Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h4 id="modalCourseName"></h4>
                <p><strong>Industry:</strong> <span id="modalCourseIndustry"></span></p>
                <p><strong>Description:</strong> <span id="modalCourseDescription"></span></p>
                <p><strong>Skill Level:</strong> <span id="modalCourseSkill"></span></p>
                <p><strong>Price:</strong> $<span id="modalCoursePrice"></span></p>
                <hr>
                <h5>Contents:</h5>
                <div id="modalCourseContents"></div> <!-- Placeholder for multiple contents -->
            </div>
        </div>
    </div>
</div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
$('#courseModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget); // Button that triggered the modal
    var course = button.data('course'); // Extract info from data-* attributes

    var modal = $(this);
    modal.find('#modalCourseName').text(course.Course_Name);
    modal.find('#modalCourseIndustry').text(course.Industry || 'Not Specified');
    modal.find('#modalCourseDescription').text(course.Description);
    modal.find('#modalCourseSkill').text(course.Skill);
    modal.find('#modalCoursePrice').text(parseFloat(course.Price).toFixed(2));

    // Handle multiple contents
    var contents = course.Contents.split('|||');
    var filePaths = course.FilePaths.split('|||');
    var contentHtml = '';
    contents.forEach((content, index) => {
        var filePath = filePaths[index] || '#';
        contentHtml += `<p>${content} <a href="${filePath}" target="_blank">View File</a></p>`;
    });
    modal.find('#modalCourseContents').html(contentHtml);
});


    </script>
</body>
<?php include 'footer.php'; ?>
</html>
