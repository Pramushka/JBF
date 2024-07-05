<?php
include '../includes/dbconn.php';
session_start();

// Ensure user_id is set in session
if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];
$org_id = $_GET['org_id'] ?? ($_POST['org_id'] ?? '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $job_positions = $_POST['job_title'];
    $Post_Description = $_POST['job_description'];
    $recruitment = $_POST['recruiter'];
    $Benifits = $_POST['benefits'];
    $job_category = $_POST['job_type'];
    $location = $_POST['location'];
    $education = $_POST['education'];
    $salary = $_POST['salary_from'];
    $IsDeleted = 0;
    $CreatedOn = date('Y-m-d H:i:s');
    $CreatedBy = $user_id;

    $conn->begin_transaction();

    $sql = "INSERT INTO jobpost (job_positions, Post_Description, recruitment, Benifits, job_category, location, education, salary, IsDeleted, CreatedOn, CreatedBy) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssisis", $job_positions, $Post_Description, $recruitment, $Benifits, $job_category, $location, $education, $salary, $IsDeleted, $CreatedOn, $CreatedBy);

    if ($stmt->execute()) {
        $last_insert_id = $stmt->insert_id;  // Move this line here after execute()
    
        $sqlOrgPost = "INSERT INTO org_post (JobPostID, OrgID) VALUES (?, ?)";
        $stmtOrgPost = $conn->prepare($sqlOrgPost);
        $stmtOrgPost->bind_param("ii", $last_insert_id, $org_id);
    
        if ($stmtOrgPost->execute()) {
            $conn->commit();
            echo "<script>alert('New job post created successfully and linked to the organization');</script>";
        } else {
            $conn->rollback();
            echo "<script>alert('Error linking job post to organization: " . $stmtOrgPost->error . "');</script>";
        }
        $stmtOrgPost->close();
    } else {
        echo "<script>alert('Error creating job post: " . $stmt->error . "');</script>";
        $conn->rollback();
    }
    $stmt->close();
}    

$industries_sql = "SELECT id, industry_name FROM job_industries";
$industries_result = $conn->query($industries_sql);
$positions_sql = "SELECT id, position_name FROM job_positions";
$positions_result = $conn->query($positions_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Post</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/jobpost.css">
    <script>
        function showSuccessMessage() {
            alert("New job post created successfully");
        }
    </script>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f2ef; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; padding: 20px; box-sizing: border-box; overflow-y: auto;">
    <div class="job-post-container">
        <h2 class="job-post-title">Add Vacancy</h2>
        <form action="job_post.php" method="POST" enctype="multipart/form-data" class="job-post-form" onsubmit="showSuccessMessage()">
            <div class="job-post-section">
                <div class="job-post-group">
                    <label for="job_title">Job Title </label>
                    <select id="job_title" name="job_title" required class="job-post-select">
                        <?php
                        if ($positions_result->num_rows > 0) {
                            while($row = $positions_result->fetch_assoc()) {
                                echo "<option value='" . $row["position_name"] . "'>" . $row["position_name"] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
                <input type="hidden" name="org_id" value="<?= htmlspecialchars($org_id) ?>">

                <div class="job-post-group">
                    <label for="recruiter">Recruiter </label>
                    <input type="text" id="recruiter" name="recruiter" required class="job-post-input">
                </div>
            </div>
            <div class="job-post-section">
                <div class="job-post-group">
                    <label for="job_type">Job Type </label>
                    <select id="job_type" name="job_type" required class="job-post-select">
                        <option value="Full-Time">Full-Time</option>
                        <option value="Part-Time">Part-Time</option>
                    </select>
                </div>
            </div>
            <div class="job-post-group">
                <label for="benefits">Benefits</label>
                <input type="text" id="benefits" name="benefits" required class="job-post-input">
            </div>
            <div class="job-post-section">
                <div class="job-post-group">
                    <label for="location">Location </label>
                    <input type="text" id="location" name="location" required class="job-post-input">
                </div>
                <div class="job-post-group">
                    <label for="sectors">Sectors </label>
                    <select id="sectors" name="sectors" required class="job-post-select">
                        <?php
                        if ($industries_result->num_rows > 0) {
                            while($row = $industries_result->fetch_assoc()) {
                                echo "<option value='" . $row["industry_name"] . "'>" . $row["industry_name"] . "</option>";
                            }
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="job-post-group">
                <label for="job_description">Job Description</label>
                <textarea id="job_description" name="job_description" required class="job-post-textarea"></textarea>
            </div>
            <div class="job-post-section">
                <div class="job-post-group">
                    <label for="experience_level">Experience Level</label>
                    <input type="text" id="experience_level" name="experience_level" required class="job-post-input">
                </div>
                <div class="job-post-group">
                    <label for="salary_from">Salary</label>
                    <input type="text" id="salary_from" name="salary_from" placeholder="Enter salary" required class="job-post-input">
                </div>
            </div>
            <div class="job-post-group">
                <label for="education">Education Level</label>
                <select id="education" name="education" required class="job-post-select">
                    <option value="Ordinary Level">Ordinary Level</option>
                    <option value="Advanced Level">Advanced Level</option>
                    <option value="HND">HND</option>
                    <option value="Bachelors">Bachelors</option>
                    <option value="Masters">Masters</option>
                    <option value="PhD">PhD</option>
                </select>
            </div>
            <div class="d-flex justify-content-between mt-3">
            <a href="jobpost_dashboard.php?org_id=<?= htmlspecialchars($org_id) ?>" class="btn btn-secondary">Back to Dashboard</a>
            <button type="submit" class="btn btn-primary">Save Vacancy</button>
            </div>
        </form>
    </div>
</body>
</html>
