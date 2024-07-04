<?php
session_start();
include '../includes/dbconn.php';

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Handle biography update
    if (isset($_POST['biography'])) {
        $biography = $_POST['biography'];
        $stmt = $conn->prepare("UPDATE user SET biography = ? WHERE ID = ?");
        $stmt->bind_param("si", $biography, $user_id);
        $stmt->execute();
    }

    // Handle about update
    if (isset($_POST['about'])) {
        $about = $_POST['about'];
        $stmt = $conn->prepare("UPDATE user SET about = ? WHERE ID = ?");
        $stmt->bind_param("si", $about, $user_id);
        $stmt->execute();
    }

    // Handle personal details update
    if (isset($_POST['name']) || isset($_POST['language']) || isset($_POST['website']) || isset($_POST['work_status'])) {
        $name = $_POST['name'];
        $language = $_POST['language'];
        $website = $_POST['website'];
        $work_status = $_POST['work_status'];
        $stmt = $conn->prepare("UPDATE user SET username = ?, language = ?, website = ?, Work_Status = ? WHERE ID = ?");
        $stmt->bind_param("ssssi", $name, $language, $website, $work_status, $user_id);
        $stmt->execute();
    }

    // Handle multiple work experience update
    if (isset($_POST['work_experience'])) {
        // First, delete existing work experience entries for the user
        $stmt = $conn->prepare("DELETE FROM user_work_experience WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Then, insert the new work experiences
        $work_experiences = $_POST['work_experience'];
        $work_experience_dates = $_POST['work_experience_date'];
        $work_experience_descriptions = $_POST['work_experience_description'];

        for ($i = 0; $i < count($work_experiences); $i++) {
            $work_experience = $work_experiences[$i];
            $work_experience_date = $work_experience_dates[$i];
            $work_experience_description = $work_experience_descriptions[$i];

            $stmt = $conn->prepare("INSERT INTO user_work_experience (user_id, work_experience, work_experience_date, work_experience_description) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $user_id, $work_experience, $work_experience_date, $work_experience_description);
            $stmt->execute();
        }
    }

    // Handle multiple education update
    if (isset($_POST['education_title'])) {
        // First, delete existing education entries for the user
        $stmt = $conn->prepare("DELETE FROM user_education WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Then, insert the new educations
        $education_titles = $_POST['education_title'];
        $education_dates = $_POST['education_date'];
        $education_descriptions = $_POST['education_description'];

        for ($i = 0; $i < count($education_titles); $i++) {
            $education_title = $education_titles[$i];
            $education_date = $education_dates[$i];
            $education_description = $education_descriptions[$i];

            $stmt = $conn->prepare("INSERT INTO user_education (user_id, education_title, education_date, education_description) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $user_id, $education_title, $education_date, $education_description);
            $stmt->execute();
        }
    }

    // Handle multiple projects update
    if (isset($_POST['project_title'])) {
        // First, delete existing project entries for the user
        $stmt = $conn->prepare("DELETE FROM user_projects WHERE user_id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();

        // Then, insert the new projects
        $project_titles = $_POST['project_title'];
        $project_dates = $_POST['project_date'];
        $project_descriptions = $_POST['project_description'];

        for ($i = 0; $i < count($project_titles); $i++) {
            $project_title = $project_titles[$i];
            $project_date = $project_dates[$i];
            $project_description = $project_descriptions[$i];

            $stmt = $conn->prepare("INSERT INTO user_projects (user_id, project_title, project_date, project_description) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $user_id, $project_title, $project_date, $project_description);
            $stmt->execute();
        }
    }

  
    // Redirect back to profile page
    header("Location: userprofile2.php");
    exit();
}
?>
