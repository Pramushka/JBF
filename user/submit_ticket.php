<?php
include '../includes/dbconn.php';
session_start();

// Ensure user_id is set in session
if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $status = 'pending';

    // Start a transaction
    $conn->begin_transaction();

    try {
        $sql = "INSERT INTO helpdeskinquiries (Description, Status, priority, category) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $description, $status, $priority, $category);
        $stmt->execute();
        $help_id = $stmt->insert_id;

        $sql_link = "INSERT INTO user_helpdeskinquiries (Help_ID, UserID) VALUES (?, ?)";
        $stmt_link = $conn->prepare($sql_link);
        $stmt_link->bind_param("ii", $help_id, $user_id);
        $stmt_link->execute();

        // Commit transaction
        $conn->commit();

        echo "<script>
                alert('New record created successfully');
                window.location.href = 'submit_ticket.php';
              </script>";
    } catch (Exception $e) {
        // Rollback transaction if something goes wrong
        $conn->rollback();
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }

    $stmt->close();
    $stmt_link->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Ticket</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
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
            color: #000;
            overflow-x: hidden;
            height: 100%;
            background-color: #D32F2F;
            background-repeat: no-repeat;
        }
        input, textarea {
            background-color: #F3E5F5;
            padding: 8px 0px 8px 0px !important;
            width: 100%;
            border-radius: 0 !important;
            box-sizing: border-box;
            border: none !important;
            border-bottom: 1px solid #F3E5F5 !important;
            font-size: 18px !important;
            color: #000 !important;
            font-weight: 300;
        }
        input:focus, textarea:focus {
            -moz-box-shadow: none !important;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
            border-bottom: 1px solid #D32F2F !important;
            outline-width: 0;
            font-weight: 400;
        }
        button:focus {
            -moz-box-shadow: none !important;
            -webkit-box-shadow: none !important;
            box-shadow: none !important;
            outline-width: 0;
        }
        .card {
            border-radius: 0;
            border: none;
            position: relative;
        }
        .card1 {
            width: 50%;
        }
        .card2 {
            width: 50%;
            height: 700px;
            background-color: #E8EAF6;
        }
        #image {
            width: 80%;
            height: 300px;
            margin: auto;
        }
        #logo {
            position: absolute;
        }
        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .form-control-placeholder {
            position: absolute;
            top: 0;
            padding: 7px 0 0 0;
            transition: all 300ms;
            opacity: 0.5;
        }
        .form-control:focus + .form-control-placeholder,
        .form-control:valid + .form-control-placeholder {
            font-size: 80%;
            transform: translate3d(0, -100%, 0);
            opacity: 1;
        }
        .btn-gray {
            border-radius: 50px;
            color: #fff;
            background-color: #BDBDBD;
            padding: 8px 40px;
        }
        .btn-gray:hover {
            color: #fff;
            background-color: #D32F2F;
        }
        a {
            color: #000;
        }
        a:hover {
            color: #000;
        }
        #google {
            width: 20px;
            height: 20px;
        }
        .bottom {
            bottom: 0;
            position: absolute;
            width: 100%;
        }
        .sm-text {
            font-size: 15px;
        }
        @media screen and (max-width: 1200px) {
            .card1 {
                width: 100%;
                padding: 10px 30px;
            }
            .bottom {
                position: relative;
            }
            .card2 {
                width: 100%; 
            }
        }
        @media screen and (max-width: 768px) {
            .container {
                padding: 10px !important;
            }
            .card2 {
                height: 400px;
            }
        }
    </style>
</head>

<body>

<?php include 'sidebarhelpdesk.php'; ?>

<div class="container px-4 py-5 mx-auto">
    <div class="card card0">
        <div class="d-flex flex-lg-row flex-column-reverse">
            <div class="card card1">
                <div class="row d-flex px-lg-4 px-3 pt-3">
                    <h6 id="logo"><strong>Submit a New IT Request</strong></h6>
                </div>
                <div class="row justify-content-center my-auto">
                    <div class="col-lg-8 my-5">
                        <h3 class="mb-3">Submit your request here.</h3>
                        <small class="text-muted">Please fill out the form below to submit your IT request.</small>
                        
                        <form action="submit_ticket.php" method="post">
                            <div class="form-group mt-5">
                                <select name="category" id="category" class="form-control" required>
                                    
                                    <option value="Hardware">Hardware</option>
                                    <option value="Software">Software</option>
                                    <option value="Network">Network</option>
                                    <option value="Other">Other</option>
                                </select>
                                <label class="form-control-placeholder" for="category">Category</label>
                            </div>
                            
                            <div class="form-group mt-4">
                                <textarea name="description" id="description" class="form-control" placeholder="How can we help?" required></textarea>
                                
                            </div>
                            <br>
                            <div class="form-group mt-4">
                                <select name="priority" id="priority" class="form-control" required>
                                    <option value="Low">Low</option>
                                    <option value="Medium">Medium</option>
                                    <option value="High">High</option>
                                </select>
                                <label class="form-control-placeholder" for="priority">Priority</label>
                            </div>
                            
                            <div class="row justify-content-center my-4">
                                <button type="submit" class="btn btn-gray">Submit</button>
                            </div>
                        </form>
                        
                       
                        
                       
                    </div>
                </div>
                
            </div>
            <div class="card card2">
                <img id="image" src="https://img.freepik.com/free-vector/organic-flat-customer-support_23-2148881015.jpg?t=st=1721111579~exp=1721115179~hmac=94d609c1e4cfc2acdffc297d288d66c48a6e3b2130b916fb8c7d132ead001ce2&w=740">
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
