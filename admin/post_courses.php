<?php
include '../includes/dbconn.php';
session_start();

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch industries
$industries_sql = "SELECT id, industry_name FROM job_industries";
$industries_result = $conn->query($industries_sql);



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Course_Name = $_POST['Course_Name'];
    $Skill = $_POST['Skill'];
    $Industry = $_POST['Industry'];
    $Description = $_POST['Description'];
    $Price = $_POST['Price'];

    // Insert course
    $insert_sql = "INSERT INTO learning_courses (Course_Name, Skill, Industry, Description, Price, CreatedOn) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("ssssd", $Course_Name, $Skill, $Industry, $Description, $Price);
    if ($stmt->execute()) {
        $courseId = $stmt->insert_id;  // Get the ID of the newly created course

        // Define the directory path for this specific course using the courseId
        $targetDirectory = "../courses/" . $courseId . "/";
        if (!file_exists($targetDirectory)) {
            mkdir($targetDirectory, 0777, true); // Make directory if it does not exist with full permissions
        }

        // Handle multiple content uploads
        if (!empty($_FILES['contentFile']['name'][0])) {
            for ($i = 0; $i < count($_FILES['contentFile']['name']); $i++) {
                $contentName = $_POST['contentName'][$i];
                $fileName = basename($_FILES['contentFile']['name'][$i]);
                $filePath = $targetDirectory . $fileName;
                if (move_uploaded_file($_FILES['contentFile']['tmp_name'][$i], $filePath)) {
                    // Insert content
                    $insert_content_sql = "INSERT INTO learning_content (Course_ID, Content, Content_File_path, CreatedBy, CreatedOn) VALUES (?, ?, ?, ?, NOW())";
                    $content_stmt = $conn->prepare($insert_content_sql);
                    $content_stmt->bind_param("issi", $courseId, $contentName, $filePath, $user_id);
                    $content_stmt->execute();
                } else {
                    echo "Failed to move file: " . htmlspecialchars($_FILES['contentFile']['name'][$i]);
                }
            }
        }
       
    } else {
        echo "Error adding course: " . $stmt->error;
    }
    $stmt->close();
}
$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Learning Course</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
            
                 @import url('https://fonts.googleapis.com/css?family=Montserrat:400,700&display=swap');

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    list-style: none;
    font-family: 'Montserrat', sans-serif;
}

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 50px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            background-color: #fff;
            border-radius: 8px;
            margin-top: 20px;
        }
        form {
            margin-top: 20px;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        input, textarea, select {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: #fff;
            cursor: pointer;
            border-radius: 4px;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php include 'sidebar.php'; ?>
<div class="container">
    <h2>Add New Learning Course</h2>
    <form action="post_courses.php" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="Course_Name">Course Name:</label>
            <input type="text" class="form-control" id="Course_Name" name="Course_Name" required>
        </div>

        <div class="form-group">
            <label for="Skill">Skill:</label>
            <input type="text" class="form-control" id="Skill" name="Skill" required>
        </div>

        <div class="form-group">
            <label for="Industry">Industry:</label>
            <select class="form-control" id="Industry" name="Industry" required>
                <option value="">Select Industry</option>
                <?php
                if ($industries_result->num_rows > 0) {
                    while($row = $industries_result->fetch_assoc()) {
                        echo "<option value='".$row['industry_name']."'>".$row['industry_name']."</option>";
                    }
                }
                ?>
            </select>
        </div>

        <div class="form-group">
            <label for="Description">Description:</label>
            <textarea class="form-control" id="Description" name="Description" rows="4" required></textarea>
        </div>

        <div class="form-group">
            <label for="Price">Price:</label>
            <input type="number" class="form-control" id="Price" name="Price" step="0.01" required>
        </div>
        <div id="content-fields">
            <h4>Course Content</h4>
            <div class="form-group">
                <label for="contentName1">Content Name:</label>
                <input type="text" class="form-control" id="contentName1" name="contentName[]" required>
                <label for="contentFile1">Content File:</label>
                <input type="file" class="form-control-file" id="contentFile1" name="contentFile[]" required>
            </div>
        </div>
        <button type="button" onclick="addContent()">Add More Content</button>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
var contentCount = 1;
function addContent() {
    contentCount++;
    var html = '<div class="form-group">' +
               '<label for="contentName' + contentCount + '">Content Name:</label>' +
               '<input type="text" class="form-control" id="contentName' + contentCount + '" name="contentName[]" required>' +
               '<label for="contentFile' + contentCount + '">Content File:</label>' +
               '<input type="file" class="form-control-file" id="contentFile' + contentCount + '" name="contentFile[]" required>' +
               '</div>';
    $('#content-fields').append(html);
}
</script>
</body>
</html>
