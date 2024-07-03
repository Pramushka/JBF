<?php
session_start();

// Ensure user_id is set in session
if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

include '../includes/dbconn.php';

$user_id = $_SESSION['user_id'];

// Fetch user details
$user_sql = "SELECT username, Job_Position, biography, about, language, website, Address AS location, Skill, work_experience, work_experience_date, work_experience_description FROM user WHERE ID = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("i", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();

if ($user_result === false || $user_result->num_rows == 0) {
    die("Error fetching user details or user not found.");
}

$user_row = $user_result->fetch_assoc();
$username = $user_row['username'];
$job_position = $user_row['Job_Position'];
$biography = $user_row['biography'] ?? '';
$about = $user_row['about'] ?? '';
$language = $user_row['language'] ?? '';
$website = $user_row['website'] ?? '';
$location = $user_row['location'] ?? '';
$skills = explode(',', $user_row['Skill']);
$work_experience = $user_row['work_experience'] ?? '';
$work_experience_date = $user_row['work_experience_date'] ?? '';
$work_experience_description = $user_row['work_experience_description'] ?? '';


$job_sql = "SELECT jobpost.id, jobpost.job_positions, jobpost.job_category, jobpost.Benifits, jobpost.salary, jobpost.CreatedBy, user.username 
            FROM jobpost 
            INNER JOIN user ON jobpost.CreatedBy = user.id 
            WHERE jobpost.IsDeleted = 0";
$job_result = $conn->query($job_sql);

if ($job_result === false) {
    die("Error executing query: " . $conn->error);
}

// Calculate progress
$sections = [
    'biography' => $biography,
    'about' => $about,
    'language' => $language,
    'website' => $website,
    'skills' => $skills,
    'work_experience' => $work_experience
];
$completed_sections = array_filter($sections, fn ($section) => !empty($section));
$progress = (count($completed_sections) / count($sections)) * 100;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>User Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/userprofile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.2.96/css/materialdesignicons.min.css" integrity="sha512-LX0YV/MWBEn2dwXCYgQHrpa9HJkwB+S+bnBpifSOTO1No27TqNMKYoAn6ff2FBh03THAzAiiCwQ+aPX+/Qt/Ow==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .alert-empty {
            color: red;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-body pb-0">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <div class="text-center border-end">
                                    <img src="https://bootdey.com/img/Content/avatar/avatar1.png" class="img-fluid avatar-xxl rounded-circle" alt="">
                                    <h4 class="text-primary font-size-20 mt-3 mb-2"><?php echo htmlspecialchars($username); ?></h4>
                                    <h5 class="text-muted font-size-13 mb-0"><?php echo htmlspecialchars($job_position); ?></h5><br>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="ms-3">
                                    <div class="d-flex justify-content-between">
                                        <h4 class="card-title mb-2">Biography</h4>
                                        <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editBiographyModal">
                                            <i class="mdi mdi-pencil"></i>
                                        </button>
                                    </div>
                                    <p class="mb-0 text-muted"><?php echo htmlspecialchars($biography); ?></p>
                                    <?php if (empty($biography)) : ?>
                                        <p class="alert-empty">Please fill in your biography</p>
                                    <?php endif; ?>
                                </div>
                                <div class="card mt-4">
                                    <div class="card-body">
                                        <h4 class="card-title mb-4">Profile Completion</h4>
                                        <div class="progress">
                                            <div class="progress-bar" role="progressbar" style="width: <?php echo $progress; ?>%;" aria-valuenow="<?php echo $progress; ?>" aria-valuemin="0" aria-valuemax="100"><?php echo round($progress); ?>%</div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="card">
                    <div class="tab-content p-4">
                        <div class="tab-pane active show" id="projects-tab" role="tabpanel">
                        <div class="d-flex align-items-center">
                                <div class="flex-1">
                                    <h4 class="card-title mb-4">Available Jobs !</h4>
                                </div>
                            </div>
                            <div class="row" id="all-projects">
                                <?php
                                if ($job_result->num_rows > 0) {
                                    while($row = $job_result->fetch_assoc()) {
                                        echo "<div class='col-md-6' id='job-item-" . $row['id'] . "'>";
                                        echo "<div class='card mb-5 rounded-3'>";
                                        echo "<div>";
                                        echo "<img src='https://bootdey.com/image/480x180/191970/ffffff' alt='Image' class='img-fluid rounded-top'>";
                                        echo "</div>";
                                        echo "<div class='card-body'>";
                                        echo "<div class='d-flex mb-3'>";
                                        echo "<div class='flex-grow-1 align-items-start'>";
                                        echo "<div>";
                                        echo "<h6 class='mb-0 text-muted'>";
                                        echo "<i class='mdi mdi-circle-medium text-danger fs-3 align-middle'></i>";
                                        echo "<span class='team-date'>" . htmlspecialchars($row['CreatedBy']) . "</span>";
                                        echo "</h6>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "<div class='dropdown ms-2'>";
                                        echo "<a href='#' class='dropdown-toggle font-size-16 text-muted' data-bs-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>";
                                        echo "<i class='mdi mdi-dots-horizontal'></i>";
                                        echo "</a>";
                                        echo "<div class='dropdown-menu dropdown-menu-end'>";
                                        echo "<a class='dropdown-item' href='javascript: void(0);' data-bs-toggle='modal' data-bs-target='.bs-example-new-project' onclick=\"editProjects('job-item-" . $row['id'] . "')\">Edit</a>";
                                        echo "<a class='dropdown-item' href='javascript: void(0);'>Share</a>";
                                        echo "<div class='dropdown-divider'></div>";
                                        echo "<a class='dropdown-item delete-item' onclick=\"deleteProjects('job-item-" . $row['id'] . "')\" data-id='job-item-" . $row['id'] . "' href='javascript: void(0);'>Delete</a>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "<div class='mb-4'>";
                                        echo "<h5 class='mb-1 font-size-17 team-title'>" . htmlspecialchars($row['job_positions']) . "</h5>";
                                        echo "<p class='text-muted mb-0 team-description'>" . htmlspecialchars($row['job_category']) . "</p>";
                                        echo "<p class='text-muted mb-0 team-description'>" . htmlspecialchars($row['Benifits']) . "</p>";
                                        echo "<p class='text-muted mb-0 team-description'>" . htmlspecialchars($row['salary']) . "</p>";
                                        echo "</div>";
                                        echo "<div class='d-flex'>";
                                        echo "<div class='avatar-group float-start flex-grow-1 task-assigne'>";
                                        echo "<div class='avatar-group-item'>";
                                        echo "<a href='javascript: void(0);' class='d-inline-block' data-bs-toggle='tooltip' data-bs-placement='top' aria-label='" . htmlspecialchars($row['username']) . "' data-bs-original-title='" . htmlspecialchars($row['username']) . "'>";
                                        echo "<img src='https://bootdey.com/img/Content/avatar/avatar1.png' alt='' class='rounded-circle avatar-sm'>";
                                        echo "</a>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "<div class='align-self-end'>";
                                        echo "<span class='badge badge-soft-danger p-2 team-status'>Pending</span>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</div>";
                                        echo "</div>";
                                    }
                                } else {
                                    echo "<p>No job posts found.</p>";
                                }
                                $conn->close();
                                ?>
                            </div>
                        </div>
                       
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-body">
                        <div class="pb-2 d-flex justify-content-between">
                            <h4 class="card-title mb-3">About</h4>
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editAboutModal">
                                <i class="mdi mdi-pencil"></i>
                            </button>
                        </div>
                        <p><?php echo htmlspecialchars($about); ?></p>
                        <?php if (empty($about)) : ?>
                            <p class="alert-empty">Please fill in your about section</p>
                        <?php endif; ?>
                        <div class="pt-2">
                            <h4 class="card-title mb-4">My Skill</h4>
                            <div class="d-flex gap-2 flex-wrap">
                                <?php foreach ($skills as $skill) : ?>
                                    <span class="badge badge-soft-secondary p-2"><?php echo htmlspecialchars($skill); ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mb-4">Personal Details</h4>
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editPersonalDetailsModal">
                                <i class="mdi mdi-pencil"></i>
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0">
                                <tbody>
                                    <tr>
                                        <th scope="row">Name</th>
                                        <td><?php echo htmlspecialchars($username); ?></td>
                                    </tr>

                                    <tr>
                                        <th scope="row">Language</th>
                                        <td><?php echo htmlspecialchars($language); ?></td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Website</th>
                                        <td><a href="<?php echo htmlspecialchars($website); ?>"><?php echo htmlspecialchars($website); ?></a></td>
                                    </tr>
                                    <?php if (empty($language) || empty($website)) : ?>
                                        <tr>
                                            <td colspan="2" class="alert-empty">Please fill in all personal details</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title mb-4">Work Experience</h4>
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#editWorkExperienceModal">
                                <i class="mdi mdi-pencil"></i>
                            </button>
                        </div>
                        <ul class="list-unstyled work-activity mb-0" id="work-experience-list">
                            <li class="work-item" data-date="<?php echo htmlspecialchars($work_experience_date); ?>">
                                <h6 class="lh-base mb-0"><?php echo htmlspecialchars($work_experience); ?></h6><br>
                                <p class="font-size-13 mb-2"><?php echo htmlspecialchars($work_experience_description); ?></p>
                            </li>
                            <?php if (empty($work_experience) || empty($work_experience_date) || empty($work_experience_description)) : ?>
                                <li class="alert-empty">Please fill in your work experience</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    <!-- Edit Biography Modal -->
    <div class="modal fade" id="editBiographyModal" tabindex="-1" aria-labelledby="editBiographyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBiographyModalLabel">Edit Biography</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editBiographyForm" method="POST" action="save_profile.php">
                        <div class="mb-3">
                            <label for="biography" class="form-label">Biography</label>
                            <textarea class="form-control" id="biography" name="biography" rows="3"><?php echo htmlspecialchars($biography); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit About Modal -->
    <div class="modal fade" id="editAboutModal" tabindex="-1" aria-labelledby="editAboutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAboutModalLabel">Edit About</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editAboutForm" method="POST" action="save_profile.php">
                        <div class="mb-3">
                            <label for="about" class="form-label">About</label>
                            <textarea class="form-control" id="about" name="about" rows="3"><?php echo htmlspecialchars($about); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Personal Details Modal -->
    <div class="modal fade" id="editPersonalDetailsModal" tabindex="-1" aria-labelledby="editPersonalDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPersonalDetailsModalLabel">Edit Personal Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editPersonalDetailsForm" method="POST" action="save_profile.php">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($username); ?>">
                        </div>

                        <div class="mb-3">
                            <label for="language" class="form-label">Language</label>
                            <input type="text" class="form-control" id="language" name="language" value="<?php echo htmlspecialchars($language); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="website" class="form-label">Website</label>
                            <input type="text" class="form-control" id="website" name="website" value="<?php echo htmlspecialchars($website); ?>">
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Work Experience Modal -->
    <div class="modal fade" id="editWorkExperienceModal" tabindex="-1" aria-labelledby="editWorkExperienceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editWorkExperienceModalLabel">Edit Work Experience</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editWorkExperienceForm" method="POST" action="save_profile.php">
                        <div class="mb-3">
                            <label for="work_experience" class="form-label">Work Experience</label>
                            <input type="text" class="form-control" id="work_experience" name="work_experience" value="<?php echo htmlspecialchars($work_experience); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="work_experience_date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="work_experience_date" name="work_experience_date" value="<?php echo htmlspecialchars($work_experience_date); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="work_experience_description" class="form-label">Description</label>
                            <textarea class="form-control" id="work_experience_description" name="work_experience_description" rows="3"><?php echo htmlspecialchars($work_experience_description); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress indicator -->


    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>