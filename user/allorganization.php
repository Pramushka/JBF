<?php
session_start();
include '../includes/dbconn.php';

// Fetch all organizations
$sql = "SELECT * FROM organization";
$stmt = $conn->prepare($sql);
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
    <title>All Organizations</title>
    <link rel="stylesheet" href="../assets/css/allorganization.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            background-color: #B3E5FC;
        }

        .card {
            border: none;
            border-radius: 20px;
            transition: all 0.5s;
            cursor: pointer;
        }

        .card:hover {
            transform: scale(1.1);
        }

        .heading {
            font-size: 32px;
            font-weight: bold;
            text-align: center;
        }

        .square {
            background-color: #fedcdd;
            height: 30px;
            width: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .email {
            font-size: 14px;
            margin-left: 25px;
            font-weight: bold;
        }

        .dummytext {
            font-size: 12px;
            font-weight: normal;
            color: #848590;
        }

        .icons i {
            color: #FA222A;
            margin-left: 25px;
        }

        .icons span {
            font-size: 13px;
            font-weight: normal;
            color: #848590;
        }

        .square1 {
            background-color: #cfe3fe;
            height: 30px;
            width: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icons1 i {
            color: #497eea;
            margin-left: 25px;
        }

        .icons1 span {
            font-size: 13px;
            font-weight: normal;
            color: #848590;
        }

        .square2 {
            background-color: #ffefc5;
            height: 30px;
            width: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icons2 i {
            color: #ffc227;
            margin-left: 25px;
        }

        .icons2 span {
            font-size: 13px;
            font-weight: normal;
            color: #848590;
        }

        .square5 {
            background-color: #41cfff;
            height: 30px;
            width: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icons5 i {
            color: #41cfff;
            margin-left: 25px;
        }

        .icons5 span {
            font-size: 13px;
            font-weight: normal;
            color: #848590;
        }

        .square4 {
            background-color: #eae6fd;
            height: 30px;
            width: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icons4 i {
            color: #6a35ff;
            margin-left: 25px;
        }

        .icons4 span {
            font-size: 13px;
            font-weight: normal;
            color: #848590;
        }

        .square3 {
            background-color: #fedfce;
            height: 30px;
            width: 30px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icons3 i {
            color: #ff8339;
            margin-left: 25px;
        }

        .icons3 span {
            font-size: 13px;
            font-weight: normal;
            color: #848590;
        }

       

        .center-title {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<?php include 'navbarsearch.php'; ?>

<div class="container mt-3 mb-3">
    <div class="heading mt-2"> <span>All Organizations</span> </div>
    <div class="row mt-1 g-4">
        <?php foreach ($organizations as $org): ?>
            <div class="col-md-4">
    <div class="card p-2">
        <a href="single_organization.php?id=<?= $org['ID'] ?>" class="text-decoration-none text-dark">
            <div class="d-flex p-1 px-4 align-items-center">
                <span class="square">
                    <img src="https://i.imgur.com/6YiLBAv.png" height="20" width="20" />
                </span>
            </div>
            <div class="email mt-1">
                <span><?= htmlspecialchars($org['Org_Name']) ?></span>
                <div class="dummytext mt-1">
                    <span>Email: <?= htmlspecialchars($org['Org_Email']) ?></span><br>
                    <span>Location: <?= htmlspecialchars($org['Org_Location']) ?: 'Not Specified' ?></span><br>
                    <span>Industry: <?= htmlspecialchars($org['Org_Industry']) ?: 'Not Specified' ?></span><br>
                    <span>Registration No: <?= htmlspecialchars($org['Org_Register_no']) ?></span><br>
                    <span>Contact: <?= htmlspecialchars($org['Verification_Contact']) ?: 'Not Specified' ?></span>
                </div>
            </div>
        </a>
    </div>
</div>

        <?php endforeach; ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="organizationModal" tabindex="-1" aria-labelledby="organizationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="organizationModalLabel">Organization Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h2 id="modalOrgName"></h2>
                <p>Email: <span id="modalOrgEmail"></span></p>
                <p>Location: <span id="modalOrgLocation"></span></p>
                <p>Industry: <span id="modalOrgIndustry"></span></p>
                <p>Registration No: <span id="modalOrgRegisterNo"></span></p>
                <p>Contact: <span id="modalOrgContact"></span></p>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
    // jQuery document ready function
    $(document).ready(function() {
        $('#organizationModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal
            var org = button.data('org'); // Extract info from data-* attributes
            
            // Update the modal's content
            var modal = $(this);
            modal.find('#modalOrgName').text(org.Org_Name);
            modal.find('#modalOrgEmail').text(org.Org_Email);
            modal.find('#modalOrgLocation').text(org.Org_Location || 'Not Specified');
            modal.find('#modalOrgIndustry').text(org.Org_Industry || 'Not Specified');
            modal.find('#modalOrgRegisterNo').text(org.Org_Register_no);
            modal.find('#modalOrgContact').text(org.Verification_Contact || 'Not Specified');
        });
    });
</script>
</body>
</html>
