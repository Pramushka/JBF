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
$sql = "SELECT * FROM organization WHERE UserID = ?";
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style> 
               @import url('https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    list-style: none;
    font-family: 'Montserrat', sans-serif;
}

    </style>
</head>
<body class="antialiased bg-gray-200 font-sans">
    <?php include 'navbar.php'; ?>

    <div class="hero-section text-center py-8">
        <h1 class="text-4xl font-bold">Welcome to My Organizations</h1>
        <p class="text-lg text-gray-700">Manage your organizations efficiently and effortlessly.</p>
        <button type="button" class="mt-4 px-4 py-2 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-500" data-toggle="modal" data-target="#createOrgModal">
            Create New Organization
        </button>
    </div>

    <div class="container mx-auto py-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <?php foreach ($organizations as $org): ?>
                <div class="bg-white shadow-xl rounded-lg overflow-hidden">
                    <div class="bg-cover bg-bottom h-56 md:h-64" style="background-image: url('https://img.freepik.com/free-vector/city-skyline-concept-illustration_114360-8923.jpg?t=st=1721039417~exp=1721043017~hmac=c04800151151709210e91284c350a49120de840ece82f4f3a600a4b74537a4cb&w=740')">
                    </div>
                    <div>
                        <div class="p-4 md:p-5">
                            <p class="font-bold text-xl md:text-2xl"><?= htmlspecialchars($org['Org_Name']) ?></p>
                            <p class="text-gray-700 md:text-lg">Manage your organization details efficiently and effortlessly.</p>
                        </div>
                        <div class="p-4 md:p-5 bg-gray-100">
                            <div class="sm:flex sm:justify-between sm:items-center">
                                <div>
                                    <div class="text-lg text-gray-700"><span class="text-gray-900 font-bold">Email:</span> <?= htmlspecialchars($org['Org_Email']) ?></div>
                                    <div class="text-lg text-gray-700"><span class="text-gray-900 font-bold">Phone:</span> <?= htmlspecialchars($org['Verification_Contact']) ?: 'Not Specified' ?></div>
                                    <div class="text-lg text-gray-700"><span class="text-gray-900 font-bold">Industry:</span> <?= htmlspecialchars($org['Org_Industry']) ?: 'Not Specified' ?></div>
                                    <div class="text-lg text-gray-700"><span class="text-gray-900 font-bold">Location:</span> <?= htmlspecialchars($org['Org_Location']) ?: 'Not Specified' ?></div>
                                </div>
                                <a href="single_organization.php?id=<?= $org['ID'] ?>" class="mt-3 sm:mt-0 py-2 px-4 bg-indigo-700 hover:bg-indigo-600 font-bold text-white text-sm rounded-lg shadow-md">View all details</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="createOrgModal" tabindex="-1" aria-labelledby="createOrgModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createOrgModalLabel">New Organization</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <script>
    function submitOrgForm() {
        var formData = new FormData(document.getElementById('createOrgForm'));
        fetch('createOrganization.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            console.log(data); // Log the response for debugging
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Organization created successfully.'
            }).then(() => {
                $('#createOrgModal').modal('hide'); // Hide modal using Bootstrap's JS
                location.reload(); // Reload the page to show the new organization
            });
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: 'Organization created successfully.'
            }).then(() => {
                $('#createOrgModal').modal('hide'); // Hide modal using Bootstrap's JS
                location.reload(); // Reload the page to show the new organization
            });
        });
    }
</script>

    <?php include 'footer.php'; ?>
</body>
</html>
