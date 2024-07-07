<?php
include '../includes/dbconn.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gather POST data
    $orgName = $_POST['orgName'];
    $orgDes = $_POST['orgDes'];
    $orgEmail = $_POST['orgEmail'];
    $orgRegisterNo = $_POST['orgRegisterNo'];
    $orgLocation = $_POST['orgLocation'];
    $orgIndustry = $_POST['orgIndustry'];
    $verificationContact = $_POST['verificationContact'];

    // Assuming all necessary validations and sanitation are done before inserting
    $query = "INSERT INTO organization (UserID, Org_Name, Org_descript, Org_Email, Org_Register_no, Org_Location, Org_Industry, Verification_Contact) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $userId = $_SESSION['user_id'];  // Assuming user's ID is stored in session
    $stmt->bind_param("issssss", $userId, $orgName, $orgDes, $orgEmail, $orgRegisterNo, $orgLocation, $orgIndustry, $verificationContact);

    if ($stmt->execute()) {
        echo "Organization created successfully.";
    } else {
        echo "Error creating organization: " . $stmt->error;
    }
    $stmt->close();
    exit;
}
?>

