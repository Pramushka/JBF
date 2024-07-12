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
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/3.6.95/css/materialdesignicons.css" rel="stylesheet">
    <style>
        body {
            background-color: #f9f9fa;
        }

        .padding {
            padding: 3rem !important;
        }

        .user-card-full {
            overflow: hidden;
        }

        .card {
            border-radius: 5px;
            -webkit-box-shadow: 0 1px 20px 0 rgba(69,90,100,0.08);
            box-shadow: 0 1px 20px 0 rgba(69,90,100,0.08);
            border: none;
            margin-bottom: 30px;
        }

        .m-r-0 {
            margin-right: 0px;
        }

        .m-l-0 {
            margin-left: 0px;
        }

        .user-card-full .user-profile {
            border-radius: 5px 0 0 5px;
        }

        .bg-c-lite-green {
            background: linear-gradient(to right, #ee5a6f, #f29263);
        }

        .user-profile {
            padding: 20px 0;
        }

        .card-block {
            padding: 1.25rem;
        }

        .m-b-25 {
            margin-bottom: 25px;
        }

        .img-radius {
            border-radius: 5px;
        }

        h6 {
            font-size: 14px;
        }

        .card .card-block p {
            line-height: 25px;
        }

        @media only screen and (min-width: 1400px){
            p {
                font-size: 14px;
            }
        }

        .b-b-default {
            border-bottom: 1px solid #e0e0e0;
        }

        .f-w-600 {
            font-weight: 600;
        }

        .m-b-20 {
            margin-bottom: 20px;
        }

        .p-b-5 {
            padding-bottom: 5px !important;
        }

        .m-b-10 {
            margin-bottom: 10px;
        }

        .text-muted {
            color: #919aa3 !important;
        }

        .m-t-40 {
            margin-top: 20px;
        }

        .user-card-full .social-link li {
            display: inline-block;
        }

        .user-card-full .social-link li a {
            font-size: 20px;
            margin: 0 10px 0 0;
            -webkit-transition: all 0.3s ease-in-out;
            transition: all 0.3s ease-in-out;
        }
    </style>
</head>
<body>

<?php include 'navbarsearch.php'; ?>

<div class="page-content page-container" id="page-content">
    
    <div class="padding">
        <div class="row container d-flex justify-content-center">
            <?php foreach ($organizations as $org): ?>
                <div class="col-xl-6 col-md-12">
                    <div class="card user-card-full">
                        <div class="row m-l-0 m-r-0">
                            <div class="col-sm-4 bg-c-lite-green user-profile">
                                <div class="card-block text-center text-white">
                                    <div class="m-b-25">
                                        <img src="https://img.icons8.com/bubbles/100/000000/user.png" class="img-radius" alt="User-Profile-Image">
                                    </div>
                                    <h6 class="f-w-600"><?= htmlspecialchars($org['Org_Name']) ?></h6>
                                    <p><?= htmlspecialchars($org['Org_Industry']) ?: 'Not Specified' ?></p>
                                    <a href="single_organization.php?id=<?= $org['ID'] ?>" class="btn btn-light">View Details</a>
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="card-block">
                                    <h6 class="m-b-20 p-b-5 b-b-default f-w-600">Information</h6>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="m-b-10 f-w-600">Email</p>
                                            <h6 class="text-muted f-w-400"><?= htmlspecialchars($org['Org_Email']) ?></h6>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="m-b-10 f-w-600">Phone</p>
                                            <h6 class="text-muted f-w-400"><?= htmlspecialchars($org['Verification_Contact']) ?: 'Not Specified' ?></h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <p class="m-b-10 f-w-600">Location</p>
                                            <h6 class="text-muted f-w-400"><?= htmlspecialchars($org['Org_Location']) ?: 'Not Specified' ?></h6>
                                        </div>
                                        <div class="col-sm-6">
                                            <p class="m-b-10 f-w-600">Registration No</p>
                                            <h6 class="text-muted f-w-400"><?= htmlspecialchars($org['Org_Register_no']) ?></h6>
                                        </div>
                                    </div>
                                    <div class="row">
                                       
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<div class="create-button text-center">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createOrgModal">
        Create New Organization
    </button>
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
        .then(response => response.text())
        .then(data => {
            alert(data); // Show response from the PHP script
            $('#createOrgModal').modal('hide'); // Hide modal using Bootstrap's JS
            location.reload(); // Reload the page to show the new organization
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
</script>
<!-- Include Bootstrap Bundle with Popper -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<!-- Initialize Swiper -->

</body>
<?php include 'footer.php'; ?>
</html>
