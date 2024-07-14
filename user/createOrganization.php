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
    $query = "INSERT INTO organization (UserID, Org_Name, Org_Descript, Org_Email, Org_Register_no, Org_Location, Org_Industry, Verification_Contact) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo "Error preparing statement: " . $conn->error;
        exit;
    }

    $userId = $_SESSION['user_id'];  // Assuming user's ID is stored in session
    // Make sure to check that session variable is set before using it to avoid errors
    if (!isset($userId)) {
        echo "User not logged in";
        exit;
    }

    // Ensure correct types are used: 'i' for integer and 's' for string
    $stmt->bind_param("isssssss", $userId, $orgName, $orgDes, $orgEmail, $orgRegisterNo, $orgLocation, $orgIndustry, $verificationContact);

    if ($stmt->execute()) {
        echo json_encode(["message" => "Organization created successfully."]);
    } else {
        echo json_encode(["error" => "Error creating organization: " . $stmt->error]);
    }
    
  
}
?>
