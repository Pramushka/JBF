<?php
session_start();
include '../includes/dbconn.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");  // Redirect to login page if not logged in
    exit;
}

// Fetching industries for the dropdown
$industryQuery = "SELECT id, industry_name FROM job_industries";
$industryResult = $conn->query($industryQuery);
$industries = [];

while ($industry = $industryResult->fetch_assoc()) {
    $industries[] = $industry;
}

$user_id = $_SESSION['user_id'];

// Fetch organizations for the logged-in user
$sql = "SELECT * FROM organization WHERE UserID = ? ";  // Assuming there's an IsDeleted column to check logical deletion
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();

$organizations = [];
while ($row = $result->fetch_assoc()) {
    $organizations[] = $row;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Organizations</title>
    <link rel="stylesheet" href="../assets/css/organization.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
    

</head>
<body>

<?php include 'navbarsearch.php'; ?>

<div class="container">
    <br><br><h1>My Organizations</h1><br>
    <div class="swiper-container">
        <div class="swiper-wrapper">
            <?php foreach ($organizations as $org): ?>
                <div class="swiper-slide">
                    <div class="organization-details">
                        <div class="organization-name">
                            <a href="single_organization.php?id=<?= $org['ID'] ?>"><?= htmlspecialchars($org['Org_Name']) ?></a>
                        </div>
                        <div class="divider"></div>
                        <div class="details">
                            <p>Email: <?= htmlspecialchars($org['Org_Email']) ?></p>
                            <p>Location: <?= htmlspecialchars($org['Org_Location']) ?></p>
                            <p>Industry: <?= htmlspecialchars($org['Org_Industry']) ?></p>
                            <p>Registration No: <?= htmlspecialchars($org['Org_Register_no']) ?></p>
                            <p>Contact: <?= htmlspecialchars($org['Verification_Contact']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <!-- Add Pagination -->
        <div class="swiper-pagination"></div>
        <!-- Add Navigation Buttons -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
</div>
<br>
<div class="create-button">
    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createOrgModal">
        Create New Organization
    </button>
</div>

<!-- Modal -->
<div class="modal fade" id="createOrgModal" tabindex="-1" aria-labelledby="createOrgModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createOrgModalLabel">New Organization</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="createOrgForm">
                    <div class="mb-3">
                        <label for="orgName" class="form-label">Organization Name:</label>
                        <input type="text" class="form-control" id="orgName" name="orgName" required>
                    </div>
                    <div class="mb-3">
                        <label for="orgDes" class="form-label">About Organization:</label>
                        <textarea class="form-control" id="orgDes" name="orgDes" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="orgEmail" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="orgEmail" name="orgEmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="orgRegisterNo" class="form-label">Registration Number:</label>
                        <input type="text" class="form-control" id="orgRegisterNo" name="orgRegisterNo" required>
                    </div>
                    <div class="mb-3">
                        <label for="orgLocation" class="form-label">Location:</label>
                        <input type="text" class="form-control" id="orgLocation" name="orgLocation">
                    </div>
                    <div class="mb-3">
                        <label for="orgIndustry" class="form-label">Industry:</label>
                        <select class="form-control" id="orgIndustry" name="orgIndustry">
                            <?php foreach ($industries as $industry): ?>
                                <option value="<?= $industry['industry_name'] ?>"><?= htmlspecialchars($industry['industry_name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="verificationContact" class="form-label">Verification Contact:</label>
                        <input type="text" class="form-control" id="verificationContact" name="verificationContact">
                    </div>
                    <button type="button" class="btn btn-primary" onclick="submitOrgForm()">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Swiper JS -->
<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>

<!-- Initialize Swiper -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var swiper = new Swiper('.swiper-container', {
            slidesPerView: 'auto',
            spaceBetween: 20,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
        });
    });

    function submitOrgForm() {
        var formData = new FormData(document.getElementById('createOrgForm'));
        fetch('createOrganization.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert(data); // Show response from the PHP script
            bootstrap.Modal.getInstance(document.getElementById('createOrgModal')).hide(); // Hide modal using Bootstrap's JS
            location.reload(); // Reload the page to show the new organization
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>

<?php include 'footer.php'; ?>
</body>
</html>
