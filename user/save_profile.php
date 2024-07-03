<?php
session_start();
include '../includes/dbconn.php';

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['biography'])) {
        $biography = $_POST['biography'];
        $stmt = $conn->prepare("UPDATE user SET biography = ? WHERE ID = ?");
        $stmt->bind_param("si", $biography, $user_id);
        $stmt->execute();
    }

    if (isset($_POST['about'])) {
        $about = $_POST['about'];
        $stmt = $conn->prepare("UPDATE user SET about = ? WHERE ID = ?");
        $stmt->bind_param("si", $about, $user_id);
        $stmt->execute();
    }

    if (isset($_POST['name']) || isset($_POST['language']) || isset($_POST['website'])) {
        $name = $_POST['name'];
        $language = $_POST['language'];
        $website = $_POST['website'];
        $stmt = $conn->prepare("UPDATE user SET username = ?, language = ?, website = ? WHERE ID = ?");
        $stmt->bind_param("sssi", $name, $language, $website, $user_id);
        $stmt->execute();
    }

    if (isset($_POST['work_experience']) || isset($_POST['work_experience_date']) || isset($_POST['work_experience_description'])) {
        $work_experience = $_POST['work_experience'];
        $work_experience_date = $_POST['work_experience_date'];
        $work_experience_description = $_POST['work_experience_description'];
        $stmt = $conn->prepare("UPDATE user SET work_experience = ?, work_experience_date = ?, work_experience_description = ? WHERE ID = ?");
        $stmt->bind_param("sssi", $work_experience, $work_experience_date, $work_experience_description, $user_id);
        $stmt->execute();
    }

    if (isset($_POST['education_title']) || isset($_POST['education_date']) || isset($_POST['education_description'])) {
        $education_title = $_POST['education_title'];
        $education_date = $_POST['education_date'];
        $education_description = $_POST['education_description'];
        $stmt = $conn->prepare("UPDATE user SET education_title = ?, education_date = ?, education_description = ? WHERE ID = ?");
        $stmt->bind_param("sssi", $education_title, $education_date, $education_description, $user_id);
        $stmt->execute();
    }

    // Redirect back to profile page
    header("Location: userprofile2.php");
    exit();
}
?>
