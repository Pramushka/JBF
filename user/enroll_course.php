<?php
session_start();
include '../includes/dbconn.php';

if (!isset($_SESSION['user_id'])) {
    echo "You must log in to enroll.";
    exit;
}

$user_id = $_SESSION['user_id'];
$course_id = $_POST['course_id'] ?? 'No course ID received'; // Debugging the received course_id

echo "Attempting to enroll in course ID: " . $course_id; // Debugging output

// Check if already enrolled
$check_sql = "SELECT * FROM user_enrollments WHERE user_id = $user_id AND course_id = $course_id";
$result = $conn->query($check_sql);
if ($result->num_rows > 0) {
    echo "You are already enrolled in this course.";
} else {
    $enroll_sql = "INSERT INTO user_enrollments (user_id, course_id) VALUES ($user_id, $course_id)";
    if ($conn->query($enroll_sql) === TRUE) {
        echo "Enrollment successful!";
    } else {
        echo "Error enrolling in course: " . $conn->error;
    }
}
?>
