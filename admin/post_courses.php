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

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Learning Course</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        h2 {
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
        }
        input, textarea, select {
            margin-bottom: 10px;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px;
            background: #5cb85c;
            border: none;
            color: #fff;
            cursor: pointer;
            border-radius: 4px;
        }
        button:hover {
            background: #4cae4c;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Add New Learning Course</h2>
    <form action="post_courses.php" method="POST">
        <label for="Course_Name">Course Name:</label>
        <input type="text" id="Course_Name" name="Course_Name" required>

        <label for="Skill">Skill:</label>
        <input type="text" id="Skill" name="Skill" required>

        <label for="Industry">Industry:</label>
        <select id="Industry" name="Industry" required>
            <option value="">Select Industry</option>
            <?php
            if ($industries_result->num_rows > 0) {
                while($row = $industries_result->fetch_assoc()) {
                    echo "<option value='".$row['industry_name']."'>".$row['industry_name']."</option>";
                }
            }
            ?>
        </select>

        <label for="Description">Description:</label>
        <textarea id="Description" name="Description" rows="4" required></textarea>

        <label for="Price">Price:</label>
        <input type="number" id="Price" name="Price" step="0.01" required>

        <button type="submit">Submit</button>
    </form>
</div>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect POST data
    $Course_Name = $_POST['Course_Name'];
    $Skill = $_POST['Skill'];
    $Industry = $_POST['Industry'];
    $Description = $_POST['Description'];
    $Price = $_POST['Price'];

    // Insert data into learning_courses table
    $insert_sql = "INSERT INTO learning_courses (Course_Name, Skill, Industry, Description, Price, CreatedOn) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($insert_sql);
    $stmt->bind_param("ssssd", $Course_Name, $Skill, $Industry, $Description, $Price);

    if ($stmt->execute()) {
        echo "<p style='text-align:center;color:green;'>New course added successfully!</p>";
    } else {
        echo "<p style='text-align:center;color:red;'>Error: " . $stmt->error . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>

</body>
</html>
