<?php
include '../includes/dbconn.php';
// Fetching for the search bar
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
    <link rel="stylesheet" href="../assets/css/text.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="./home.php">JOB FORCE</a>
            </div>
            <form action="jobsearch.php" method="GET" class="search-form">
                <div class="search-field custom-search-field">
                    <i class="fas fa-briefcase"></i>
                    <select class="form-control custom-select custom-select-industry" id="industry_skill_position" name="industry_skill_position">
                        <?php foreach ($allOptions as $option): ?>
                            <option value="<?= $option['ID'] ?>"><?= $option['name'] ?> (<?= $option['type'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="search-field custom-search-field">
                    <i class="fas fa-calendar-alt"></i>
                    <select class="form-control custom-select custom-select-experience" name="experience" id="experience">
                        <option value="0">Fresher (less than a year)</option>
                        <?php for ($i = 1; $i <= 30; $i++): ?>
                            <option value="<?= $i ?>"><?= $i ?> Year<?= $i > 1 ? 's' : '' ?></option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="search-field custom-search-field">
                    <i class="fas fa-map-marker-alt"></i>
                    <input type="text" name="location" class="form-control custom-input-location" placeholder="Enter Location">
                </div>
                <button type="submit" class="search-button custom-search-button">Find Jobs</button>
            </form>
            <ul class="nav-links custom-nav-links">
                <li><a href="#">Home</a></li>
                <li><a href="#">Ads</a></li>
                <li><a href="#">About</a></li>
                <li><a href="#">Blog</a></li>
                <li><a href="#">Contact</a></li>
                <li><a href="#">Log In</a></li>
                <li><a href="#">Register</a></li>
            </ul>
            <div class="menu-toggle">
                <i class="fas fa-bars"></i>
            </div>
        </div>
    </nav>

    <script>
        $(document).ready(function() {
            $('#industry_skill_position').select2({
                placeholder: 'Select Industry/Skill/Position',
                allowClear: true
            });

            $('#experience').select2({
                placeholder: 'Select Experience',
                allowClear: true
            });

            $('.menu-toggle').click(function() {
                $('.custom-nav-links').toggleClass('active');
            });
        });

        document.querySelector('.user-profile-link').addEventListener('click', function(event) {
            event.stopPropagation();
            document.querySelector('.user-profile-link + .dropdown-content').classList.toggle('show');
        });

        window.onclick = function(event) {
            if (!event.target.matches('.user-profile-link') && !event.target.matches('.fas.fa-user')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                for (var i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
</html>
