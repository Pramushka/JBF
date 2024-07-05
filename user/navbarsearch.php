<?php
include '../includes/dbconn.php';
//FOR THE SEACH BAR OPERATIONS
// Fetching for this Serch bar
$industryResult = $conn->query("SELECT ID, industry_name as name, 'industry' as type FROM job_industries");
$positionResult = $conn->query("SELECT ID, position_name as name, 'position' as type FROM job_positions");

$allOptions = [];

while ($row = $industryResult->fetch_assoc()) {
    $allOptions[] = $row;
}
while ($row = $positionResult->fetch_assoc()) {
    $allOptions[] = $row;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar with Search Bar</title>
    <link rel="stylesheet" href="../assets/css/navbarsearch.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
</head>
<body>
    
<div class="navi">
    <nav>
        <a href="./home.php"><img src="../assets/img/twitter.png" class="logo"></a>
        <ul>
        <div class="search-container">
         <form action="jobsearch.php" method="GET" class="search-form">
        <div class="search-bar">
            <!-- Industry/Skill Position with Icon -->
            <div class="search-field">
                <i class="fas fa-briefcase"></i>
                <select class="form-control" id="industry_skill_position" name="industry_skill_position">
                    <?php foreach ($allOptions as $option): ?>
                        <option value="<?= $option['ID'] ?>"><?= $option['name'] ?> (<?= $option['type'] ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <!-- Experience with Icon -->
            <div class="search-field">
                <i class="fas fa-calendar-alt"></i>
                <select class="form-control" name="experience" id="experience">
                    <option value="0">Fresher (less than a year)</option>
                    <?php for ($i = 1; $i <= 30; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?> Year<?= $i > 1 ? 's' : '' ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <!-- Location with Icon -->
            <div class="search-field">
                <i class="fas fa-map-marker-alt"></i>
                <input type="text" name="location" placeholder="Enter Location">
            </div>
            <!-- Submit Button -->
            <button class="search-button">Find Jobs</button>
        </div>
        </form>
        </div>
           <li><a href="./jobsearch.php">Search jobs</a></li>
            <li class="dropdown">
                <a href="#">Organizations</a>
                <div class="dropdown-content">
                    <a href="./allorganization.php">Top Hiring</a>
                    <a href="./uOrganizationList.php">My Companies</a>
                </div>
            </li>
            <li><a href="./course_dashboard.php">Learning courses</a></li>
            <li><a href="#">About us</a></li>
            <li><a href="./contact.php">Contact us</a></li>
            <li class="dropdown">
            <a href="#" class="user-profile-link"><i class="fas fa-user"></i></a>
                <div class="dropdown-content">
                    <a href="./userprofile2.php">User Profile</a>
                    <a href="#">Log Out</a>
                </div>
            </li>
        </ul>
    </nav>
</div>


<script>
    // JavaScript to toggle dropdown for the user icon
    document.querySelector('.dropbtn').addEventListener('click', function(event) {
        event.stopPropagation();
        document.querySelector('.dropbtn + .dropdown-content').classList.toggle('show');
    });

    // Close the dropdown if the user clicks outside of it
    window.onclick = function(event) {
        if (!event.target.matches('.dropbtn') && !event.target.matches('.fas.fa-user')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.classList.contains('show')) {
                    openDropdown.classList.remove('show');
                }
            }
        }
    }

    $(document).ready(function() {
    $('#industry_skill_position').select2({
        placeholder: 'Select Industry/Skill/Position',
        allowClear: true
    });

    $('#experience').select2({
        placeholder: 'Select Experience',
        allowClear: true
    });
});
</script>

</body>
</html>
