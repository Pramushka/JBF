<?php
include '../includes/dbconn.php';
session_start();

function fetchOptions($conn, $tableName, $columnName) {
    $sql = "SELECT $columnName FROM $tableName";
    $result = mysqli_query($conn, $sql);
    $options = [];

    while ($row = mysqli_fetch_assoc($result)) {
        $options[] = $row[$columnName];
    }

    return $options;
}

$jobPositions = fetchOptions($conn, 'job_positions', 'position_name');
$jobIndustries = fetchOptions($conn, 'job_industries', 'industry_name');

// Check if user is logged in and get user ID from session
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = mysqli_real_escape_string($conn, $_POST['firstName']);
    $lastName = mysqli_real_escape_string($conn, $_POST['lastName']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $workStatus = mysqli_real_escape_string($conn, $_POST['workStatus']);
    $jobPositions = implode(', ', array_map('mysqli_real_escape_string', array_fill(0, count($_POST['jobPositions']), $conn), $_POST['jobPositions']));
    $jobIndustries = implode(', ', array_map('mysqli_real_escape_string', array_fill(0, count($_POST['jobIndustries']), $conn), $_POST['jobIndustries']));
    $skills = implode(', ', array_map('mysqli_real_escape_string', array_fill(0, count($_POST['skills']), $conn), $_POST['skills']));

    $sql = "UPDATE User SET First_Name=?, Last_Name=?, Phone=?, Address=?, Work_Status=?, Job_Position=?, Job_Industry=?, Skill=? WHERE ID=?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssssssi", $firstName, $lastName, $phone, $address, $workStatus, $jobPositions, $jobIndustries, $skills, $user_id);
        mysqli_stmt_execute($stmt);
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            // Store user's email in session
            $sql = "SELECT Email FROM User WHERE ID = ?";
            $stmt = mysqli_prepare($conn, $sql);
            mysqli_stmt_bind_param($stmt, "i", $user_id);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $user_email);
            mysqli_stmt_fetch($stmt);
            $_SESSION['user_email'] = $user_email;

            header("Location: verification.php");
            exit;
        } else {
            echo "<p>Error: Unable to complete registration. Please try again.</p>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<p>Error preparing statement: " . mysqli_error($conn) . "</p>";
    }
    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Job Force - Registration</title>
    <link rel="stylesheet" href="../assets/css/kp.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="container">
        <form id="registrationForm" method="POST" action="register.php">
            <div id="step1" class="form-step active">
                <h2>Make the most of your professional life</h2>
                <input type="text" name="firstName" placeholder="First name" required>
                <input type="text" name="lastName" placeholder="Last name" required>
                <button type="button" onclick="nextStep(1)">Continue</button>
            </div>
            <div id="step2" class="form-step">
                <h2>Your profile helps you discover new people and opportunities</h2>
                <input type="text" name="phone" placeholder="Phone number" required>
                <input type="text" name="address" placeholder="Address" required>
                <button type="button" onclick="nextStep(2)">Continue</button>
            </div>
            <div id="step3" class="form-step">
                <h2>Help us understand your professional background</h2>
                <div class="input-with-button">
                    <select name="workStatus" required>
                        <option value="">Select Work Status</option>
                        <option value="employed">Employed</option>
                        <option value="unemployed">Unemployed</option>
                        <option value="looking">Looking for a job</option>
                        <option value="student">Student</option>
                    </select>
                </div>
                <div class="input-with-button">
                    <select id="jobPositionSelect">
                        <option value="">Select Job Position</option>
                        <?php foreach ($jobPositions as $position): ?>
                            <option value="<?= htmlspecialchars($position) ?>"><?= htmlspecialchars($position) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" onclick="addJobPosition()">+</button>
                </div>
                <div id="selectedJobPositions"></div>
                <input type="hidden" name="jobPositions[]" id="jobPositionsInput">
                
                <div class="input-with-button">
                    <select id="jobIndustrySelect">
                        <option value="">Select Job Industry</option>
                        <?php foreach ($jobIndustries as $industry): ?>
                            <option value="<?= htmlspecialchars($industry) ?>"><?= htmlspecialchars($industry) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="button" onclick="addJobIndustry()">+</button>
                </div>
                <div id="selectedJobIndustries"></div>
                <input type="hidden" name="jobIndustries[]" id="jobIndustriesInput">
                
                <div class="input-with-button">
                    <input type="text" id="skillInput" placeholder="Skills (up to 5)">
                    <button type="button" onclick="addSkill()">+</button>
                </div>
                <div id="selectedSkills"></div>
                <input type="hidden" name="skills[]" id="skillsInput">
                
                <button type="submit">Submit</button>
            </div>
        </form>
    </div>
    <script src="../assets/js/scripts.js"></script>
</body>
</html>
