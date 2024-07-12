<?php
session_start();
include '../includes/dbconn.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'You must be logged in to perform this action.']);
    exit;
}

// Validate input and prepare for database update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $orgId = $_POST['orgId'] ?? null;
    $orgName = $_POST['orgName'] ?? '';
    $orgDes = $_POST['orgDes'] ?? '';
    $orgEmail = $_POST['orgEmail'] ?? '';
    $orgRegisterNo = $_POST['orgRegisterNo'] ?? '';
    $orgLocation = $_POST['orgLocation'] ?? '';
    $orgIndustry = $_POST['orgIndustry'] ?? '';
    $verificationContact = $_POST['verificationContact'] ?? '';

    // Validation example
    if (empty($orgName) || empty($orgEmail)) {
        echo json_encode(['error' => 'Organization name and email are required.']);
        exit;
    }

    // Prepare SQL query to update organization details
    $sql = "UPDATE organization SET 
            Org_Name = ?, 
            Org_Des = ?, 
            Org_Email = ?, 
            Org_RegisterNo = ?, 
            Org_Location = ?, 
            Org_Industry = ?, 
            Verification_Contact = ? 
            WHERE ID = ?";

    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param("sssssssi", $orgName, $orgDes, $orgEmail, $orgRegisterNo, $orgLocation, $orgIndustry, $verificationContact, $orgId);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            echo json_encode(['success' => 'Organization updated successfully.']);
        } else {
            echo json_encode(['error' => 'No changes were made to the organization.']);
        }
        $stmt->close();
    } else {
        echo json_encode(['error' => 'Failed to prepare the statement. Error: ' . $conn->error]);
    }
} else {
    echo json_encode(['error' => 'Invalid request method.']);
}

$conn->close();
?>
