<?php
session_start();
include '../vendor/autoload.php'; // Ensure this path is correct for your setup
include '../includes/dbconn.php';

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$user_sql = "SELECT username, First_Name, Last_Name, Email, Phone, Address, Work_Status, biography, about, language, website, Skill FROM user WHERE ID = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result === false || $user_result->num_rows == 0) {
    die("Error fetching user details or user not found.");
}

$user_row = $user_result->fetch_assoc();
$username = $user_row['username'];
$first_name = $user_row['First_Name'];
$last_name = $user_row['Last_Name'];
$email = $user_row['Email'];
$phone = $user_row['Phone'];
$address = $user_row['Address'];
$work_status = $user_row['Work_Status'];
$biography = $user_row['biography'] ?? '';
$about = $user_row['about'] ?? '';
$language = $user_row['language'] ?? '';
$website = $user_row['website'] ?? '';
$skills = explode(',', $user_row['Skill']);

// Fetch multiple work experiences
$work_experience_sql = "SELECT work_experience, work_experience_date, work_experience_description FROM user_work_experience WHERE user_id = ?";
$work_experience_stmt = $conn->prepare($work_experience_sql);
$work_experience_stmt->bind_param("i", $user_id);
$work_experience_stmt->execute();
$work_experience_result = $work_experience_stmt->get_result();

$work_experiences = [];
while ($row = $work_experience_result->fetch_assoc()) {
    $work_experiences[] = $row;
}

// Fetch multiple educations
$education_sql = "SELECT education_title, education_date, education_description FROM user_education WHERE user_id = ?";
$education_stmt = $conn->prepare($education_sql);
$education_stmt->bind_param("i", $user_id);
$education_stmt->execute();
$education_result = $education_stmt->get_result();

$educations = [];
while ($row = $education_result->fetch_assoc()) {
    $educations[] = $row;
}

// Fetch multiple projects
$project_sql = "SELECT project_title, project_date, project_description FROM user_projects WHERE user_id = ?";
$project_stmt = $conn->prepare($project_sql);
$project_stmt->bind_param("i", $user_id);
$project_stmt->execute();
$project_result = $project_stmt->get_result();

$projects = [];
while ($row = $project_result->fetch_assoc()) {
    $projects[] = $row;
}

// HTML template for the CV
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo htmlspecialchars($first_name . ' ' . $last_name); ?>'s CV</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.1/css/all.min.css" integrity="sha256-2XFplPlrFClt0bIdPgpz8H7ojnk10H69xRqd9+uTShA=" crossorigin="anonymous" />
<link rel="stylesheet" href="../assets/css/cv.css">
<style type="text/css">
@media print {
    body {
        margin: 0;
        padding: 0;
        width: 210mm;
        height: 297mm;
    }
    .container {
        width: 100%;
        height: 100%;
    }
}
</style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="row align-items-center">
                
                <div class="col-lg-5">
                    <div class="resume-base bg-primary user-dashboard-info-box p-4">
                        <div class="profile">
                            <div class="jobster-user-info">
                                <div class="profile-avatar">
                                    <img class="img-fluid " src="https://bootdey.com/img/Content/avatar/avatar6.png" alt="">
                                </div>
                                <div class="profile-avatar-info mt-3">
                                    <h5 class="text-white"><?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="about-candidate border-top">
                            <div class="candidate-info">
                                <h6 class="text-white">Name:</h6>
                                <p class="text-white"><?php echo htmlspecialchars($first_name . ' ' . $last_name); ?></p>
                            </div>
                            <div class="candidate-info">
                                <h6 class="text-white">Email:</h6>
                                <p class="text-white"><?php echo htmlspecialchars($email); ?></p>
                            </div>
                            <div class="candidate-info">
                                <h6 class="text-white">Phone:</h6>
                                <p class="text-white"><?php echo htmlspecialchars($phone); ?></p>
                            </div>
                            <div class="candidate-info">
                                <h6 class="text-white">Address:</h6>
                                <p class="text-white"><?php echo htmlspecialchars($address); ?></p>
                            </div>
                            <div class="candidate-info">
                                <h6 class="text-white">Work Status:</h6>
                                <p class="text-white"><?php echo htmlspecialchars($work_status); ?></p>
                            </div>
                        </div>
                        <div class="mt-0">
                            <h5 class="text-white">Professional Skill:</h5>
                            <?php foreach ($skills as $skill): ?>
                            <div class="progress bg-dark">
                                <div class="progress-bar bg-white" role="progressbar" style="width:70%" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">
                                    <div class="progress-bar-title text-white"><?php echo htmlspecialchars($skill); ?></div>
                                    <span class="progress-bar-number text-white">70%</span>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="resume-experience">
                        <div class="timeline-box">
                            <h5 class="resume-experience-title">Education:</h5>
                            <div class="jobster-candidate-timeline">
                                <?php foreach ($educations as $education): ?>
                                <div class="jobster-timeline-item">
                                    <div class="jobster-timeline-cricle">
                                        <i class="far fa-circle"></i>
                                    </div>
                                    <div class="jobster-timeline-info">
                                        <div class="dashboard-timeline-info">
                                            <span class="jobster-timeline-time"><?php echo htmlspecialchars($education['education_date']); ?></span>
                                            <h6 class="mb-2"><?php echo htmlspecialchars($education['education_title']); ?></h6>
                                            <span>- <?php echo htmlspecialchars($education['education_description']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="timeline-box mt-4">
                            <h5 class="resume-experience-title">Work &amp; Experience:</h5>
                            <div class="jobster-candidate-timeline">
                                <?php foreach ($work_experiences as $experience): ?>
                                <div class="jobster-timeline-item">
                                    <div class="jobster-timeline-cricle">
                                        <i class="far fa-circle"></i>
                                    </div>
                                    <div class="jobster-timeline-info">
                                        <div class="dashboard-timeline-info">
                                            <span class="jobster-timeline-time"><?php echo htmlspecialchars($experience['work_experience_date']); ?></span>
                                            <h6 class="mb-2"><?php echo htmlspecialchars($experience['work_experience']); ?></h6>
                                            <span>- <?php echo htmlspecialchars($experience['work_experience_description']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="timeline-box mt-4">
                            <h5 class="resume-experience-title">Projects:</h5>
                            <div class="jobster-candidate-timeline">
                                <?php foreach ($projects as $project): ?>
                                <div class="jobster-timeline-item">
                                    <div class="jobster-timeline-cricle">
                                        <i class="far fa-circle"></i>
                                    </div>
                                    <div class="jobster-timeline-info">
                                        <div class="dashboard-timeline-info">
                                            <span class="jobster-timeline-time"><?php echo htmlspecialchars($project['project_date']); ?></span>
                                            <h6 class="mb-2"><?php echo htmlspecialchars($project['project_title']); ?></h6>
                                            <span>- <?php echo htmlspecialchars($project['project_description']); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <!-- Add Awards section if needed -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
$html = ob_get_clean();

if (isset($_GET['download'])) {
    $mpdf = new \Mpdf\Mpdf(['format' => 'A4']);
    $mpdf->WriteHTML($html);
    $mpdf->Output('cv.pdf', 'D');
    exit();
}
echo $html;
?>
