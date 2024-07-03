<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="../assets/css/ustyle.css">
</head>
<body>

<div class="profile-container">
    <div class="profile-header">
        <div class="profile-pic"></div>
        <div class="user-details">
            <h2>Sashen Kavinda</h2>
            <div class="buttons">
                <button class="open-to">Open to</button>
                <button class="add-profile-section">Edit Profile</button>
                <button class="more">More</button>
            </div>
        </div>
    </div>
    <div class="details">
        <div class="about">
            <h3>About me -_-</h3>
            <p>Student at Cardiff Metropolitan University</p>
            <p>Gampaha District, Western Province, Sri Lanka</p>
            <p>Fitness Instructor, Information Technology Intern and Network Administrator roles</p>
            <p></p>
            <p>Aspiring scholar Sashen, currently a student, passionate about learning and driven to achieve excellence. Always eager to embrace new challenges and expand knowledge horizons.</p>
        </div>
    </div>
</div>

<div class="exp-view">
    <div class="exp-vv">
        <h3>Experince-_-</h3>
        <p>Labba</p>
        <p>Labba</p>
        <p>Labba</p>
        <p>Labba</p>
        <p>Labba</p>
        <p>Labba</p>
        <p>Labba</p>
        <p>Labba</p>
        <p>Labba</p>
        <p>Labba</p>
    </div>
</div>

<div class="job">
        <h3>Job recommendations for you!</h3>
        <div class="jobs-for-you">
            <div class="jcard">Card</div>
            <div class="jcard">Card</div>
            <div class="jcard">Card</div>
            <div class="jcard">Card</div>
            <div class="jcard">Card</div>
        </div>
    </div>

<div class="organization">
    <h3>Organizattion class</h3>
    <div class="org">
        <div class="ecard">Card</div>
        <div class="ecard">Card</div>
        <div class="ecard">Card</div>
        <div class="ecard">Card</div>
        <div class="ecard">Card</div>
        <div class="ecard">Card</div>
        <div class="ecard">Card</div>
        <div class="ecard">Card</div>
    </div>
</div>

<div class="courses">
    <h3>Courses you learning</h3>
    <div class="cou-for-you">
        <div class="card">Card</div>
        <div class="card">Card</div>
        <div class="card">Card</div>
        <div class="card">Card</div>
        <div class="card">Card</div>
    </div>
</div>

<!-- Modal for editing profile -->
<div id="editProfileModal" class="modal">
    <div class="modal-content card">
        <span class="close-button">&times;</span>
        <form id="editProfileForm" class="card-container">
            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" cols="50"></textarea>

            <label for="skills">Skills:</label>
            <input type="text" id="skills" name="skills">

            <label for="job-position">Job Position:</label>
            <input type="text" id="job-position" name="job-position">

            <label for="experience">Experience:</label>
            <input type="text" id="experience" name="experience">

            <label for="qualifications">Qualifications:</label>
            <input type="text" id="qualifications" name="qualifications">

            <button type="submit">Submit</button>
        </form>
    </div>
</div>

<script src="../assets/js/jobs cards.js"></script>

<script src="../assets/js/umodal.js"></script>
</body>
</html>
