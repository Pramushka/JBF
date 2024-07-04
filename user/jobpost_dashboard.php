<?php
include '../includes/dbconn.php';
session_start();

// Ensure user_id is set in session
if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

// Fetch the username of the logged-in user
$user_sql = "SELECT username FROM user WHERE id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("s", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result === false || $user_result->num_rows == 0) {
    die("Error fetching user details or user not found.");
}

$user_row = $user_result->fetch_assoc();
$username = $user_row['username'];

// Fetch job posts created by the specific user
$sql = "SELECT id, job_positions, job_category, Benifits, salary FROM jobpost WHERE IsDeleted = 0 AND CreatedBy = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result === false) {
    die("Error executing query: " . $stmt->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Post Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/jobpostdash.css">

</head>
<body>
    <div class="container">
        <div class="row align-items-center">
            <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                <!-- Bg -->
                <div class="pt-20 rounded-top" style="background: url(https://bootdey.com/image/480x480/00FFFF/000000) no-repeat; background-size: cover;"></div>
                <div class="card rounded-bottom smooth-shadow-sm">
                    <div class="d-flex align-items-center justify-content-between pt-4 pb-6 px-4">
                        <div class="d-flex align-items-center">
                            <div class="avatar-xxl avatar-indicators avatar-online me-2 position-relative d-flex justify-content-end align-items-end mt-n10">
                                <img src="https://bootdey.com/img/Content/avatar/avatar8.png" class="avatar-xxl rounded-circle border border-2" alt="Image">
                            </div>
                            <div class="lh-1">
                                <h2 class="mb-0">Organization Post Dashboard</h2>
                                <p class="mb-0 d-block">@<?php echo htmlspecialchars($username); ?></p>
                            </div>
                        </div>
                        <div>
                            <a href="job_post.php" class="btn btn-outline-primary">Create Job Post</a>
                        </div>
                    </div>
                    <ul class="nav nav-lt-tab px-4" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">Job Posts</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="py-6">
            <div class="row">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='col-lg-4 col-12'>";
                        echo "<div class='card mb-5 rounded-3'>";
                        echo "<div>";
                        echo "<img src='https://bootdey.com/image/480x180/191970/ffffff' alt='Image' class='img-fluid rounded-top'>";
                        echo "</div>";
                        echo "<div class='avatar avatar-xl mt-n7 ms-4'>";
                        echo "<img src='https://bootdey.com/img/Content/avatar/avatar1.png' alt='Image' class='rounded-circle border-4 border-white-color-40'>";
                        echo "</div>";
                        echo "<div class='card-body'>";
                        echo "<h4 class='mb-1'>" . htmlspecialchars($row['job_positions']) . "</h4>";
                        echo "<p>" . htmlspecialchars($row['job_category']) . "</p>";
                        echo "<p>" . htmlspecialchars($row['Benifits']) . "</p>";
                        echo "<p>$" . number_format($row['salary']) . "</p>";
                        echo "<div class='d-flex justify-content-between align-items-center'>";
                        echo "<a href='edit_jobpost.php?job_id=" . htmlspecialchars($row['id']) . "' class='btn btn-outline-primary'>View</a>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No job posts found.</p>";
                }
                $conn->close();
                ?>
            </div>
        </div>
    </div>
</body>
</html>
