<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Navbar</title>
    <link rel="stylesheet" href="../assets/css/navbar.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm" crossorigin="anonymous" />

</head>
<body>
    
<div class="navi">

    <nav>
        <a href="./home.php"><img src="../assets/img/twitter.png" class="logo"></a>
        <ul>
            <li><a href="./jobsearch.php">Search jobs</a></li>
            <li class="dropdown">
                <a href="#">Organizations</a>
                <div class="dropdown-content">
                    <a href="./allorganization.php">Top Hiring</a>
                    <a href="./uOrganizationList.php">My Companies</a>
                </div>
            </li>
            <li><a href="./course_dashboard.php">Learning courses</a></li>
            <li><a href="./about_us.php">About us</a></li>
            <li><a href="contact.php">Contact us</a></li>
            <li class="dropdown">
            <a href="#" class="user-profile-link"><i class="fas fa-user"></i></a>
                <div class="dropdown-content">
                    <a href="./userprofile2.php">User Profile</a>
                    <a href="./logout.php">Log Out</a>
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
</script>

</body>
</html>
