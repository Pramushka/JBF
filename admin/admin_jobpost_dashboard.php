<?php
include '../includes/dbconn.php';
session_start();

// Fetch all job posts and their corresponding user details
$sql = "SELECT jobpost.id, jobpost.job_positions, jobpost.job_category, jobpost.Benifits, jobpost.salary, jobpost.CreatedBy, user.username 
        FROM jobpost 
        INNER JOIN user ON jobpost.CreatedBy = user.id 
        WHERE jobpost.IsDeleted = 0";
$result = $conn->query($sql);

if ($result === false) {
    die("Error executing query: " . $conn->error);
}

// Handle deletion of a job post
if (isset($_GET['delete_job_id'])) {
    $delete_job_id = $_GET['delete_job_id'];
    $delete_sql = "UPDATE jobpost SET IsDeleted = 1 WHERE id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $delete_job_id);
    
    if ($delete_stmt->execute()) {
        echo "<script>alert('Job post deleted successfully'); window.location.href='admin_jobpost_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . $delete_stmt->error . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Job Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css">
    <style>
        body { padding-top: 20px; background-color: #f1f5f9; }
        .card { border: 0; border-radius: 0.5rem; box-shadow: 0 2px 4px rgba(0,0,20,.08), 0 1px 2px rgba(0,0,20,.08); }
        .rounded-bottom { border-bottom-left-radius: 0.375rem !important; border-bottom-right-radius: 0.375rem !important; }
        .avatar-xxl { height: 7.5rem; width: 7.5rem; }
        .nav-lt-tab { border-top: 1px solid var(--dashui-border-color); }
        .px-4 { padding-left: 1rem !important; padding-right: 1rem !important; }
        .avatar-sm { height: 2rem; width: 2rem; }
        .nav-lt-tab .nav-item { margin: -0.0625rem 1rem 0; }
        .nav-lt-tab .nav-item .nav-link { border-radius: 0; border-top: 2px solid transparent; color: var(--dashui-gray-600); font-weight: 500; padding: 1rem 0; }
        .pt-20 { padding-top: 8rem !important; }
        .avatar-xxl.avatar-indicators:before { bottom: 5px; height: 16%; right: 17%; width: 16%; }
        .avatar-online:before { background-color: #198754; }
        .avatar-indicators:before { border: 2px solid #FFF; border-radius: 50%; bottom: 0; content: ""; display: table; height: 30%; position: absolute; right: 5%; width: 30%; }
        .mt-n10 { margin-top: -3rem !important; }
        .me-2 { margin-right: 0.5rem !important; }
        .align-items-end { align-items: flex-end !important; }
        .rounded-circle { border-radius: 50% !important; }
        .border-2 { --dashui-border-width: 2px; }
        .border { border: 1px solid #dcdcdc !important; }
        .py-6 { padding-bottom: 1.5rem !important; padding-top: 1.5rem !important; }
        .bg-gray-300 { --dashui-bg-opacity: 1; background-color: #cbd5e1 !important; }
        .mb-6 { margin-bottom: 1.5rem !important; }
        .align-items-center { align-items: center !important; }
        .mb-4 { margin-bottom: 1rem !important; }
        .mb-8 { margin-bottom: 2rem !important; }
        .shadow-none { box-shadow: none !important; }
        .card>.list-group:last-child { border-bottom-left-radius: 0.5rem; border-bottom-right-radius: 0.5rem; border-bottom-width: 0; }
        .card>.list-group:first-child { border-top-left-radius: 0.5rem; border-top-right-radius: 0.5rem; border-top-width: 0; }
        .card>.list-group { border-bottom: inherit; border-top: inherit; }
        .avatar-xl { height: 5rem; width: 5rem; }
        .avatar { display: inline-block; height: 3rem; position: relative; width: 3rem; }
        .mt-n7 { margin-top: -1.75rem !important; }
        .ms-4 { margin-left: 1rem !important; }
        .avatar img { height: 100%; -o-object-fit: cover; object-fit: cover; width: 100%; }
    </style>
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
                                <h2 class="mb-0">Admin Dashboard</h2>
                                <p class="mb-0 d-block">@admin</p>
                            </div>
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
                        echo "<p><strong>Created By:</strong> " . htmlspecialchars($row['username']) . "</p>";
                        echo "<div class='d-flex justify-content-between align-items-center'>";
                        echo "<a href='admin_edit_jobpost.php?job_id=" . htmlspecialchars($row['id']) . "' class='btn btn-outline-primary'>Edit Job</a>";
                        echo "<a href='admin_jobpost_dashboard.php?delete_job_id=" . htmlspecialchars($row['id']) . "' class='btn btn-danger btn-delete' onclick=\"return confirm('Are you sure you want to delete this job post?');\">Delete</a>";
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