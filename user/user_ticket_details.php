<?php
include '../includes/dbconn.php';

$ticket_id = isset($_GET['id']) ? $_GET['id'] : 0;

$sql = "SELECT * FROM helpdeskinquiries WHERE ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$ticket_result = $stmt->get_result();
$ticket = $ticket_result->fetch_assoc();

$reply_sql = "SELECT * FROM helpdeskreply WHERE TicketID = ?";
$reply_stmt = $conn->prepare($reply_sql);
$reply_stmt->bind_param("i", $ticket_id);
$reply_stmt->execute();
$reply_result = $reply_stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Details</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css">
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
            background-color: lightpink;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .logo {
            border: 1px solid #f6f6f6;
        }
        .logo img {
            width: 60px;
            height: 60px;
        }
        .card {
            display: block;
            padding: 3vh 2vh 7vh 5vh;
            border: none;
            border-radius: 15px;
            margin-top: 5%;
            margin-bottom: 5%;
            max-width: 500px;
            background: #ffffff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            margin-bottom: 5vh;
            margin-right: 2vh;
            float: right;
            margin-left: auto;
        }
        .far {
            color: rgba(15, 198, 239, 0.97)!important;
            font-size: 16px!important;
        }
        p.heading {
            font-weight: bold;
            font-size: 25px;
        }
        p.text-muted {
            font-size: 17px;
            font-weight: bold;
            color: #a1a7ae!important;
        }
        .btn-sm {
            border-radius: 8px;
        }
        .fas.fa-users {
            color: rgba(15, 198, 239, 0.97)!important;
        }
        .mutual span {
            font-size: 14px;
            color: #adb5bd;
            font-weight: bold;
        }
        .btn-primary.btn-lg {
            border-radius: 30px;
            width: 90%;
            border: none;
            background: #8c02e3;
        }
        .btn-dark.btn-lg {
            border-radius: 30px;
            width: 90%;
            border: none;
            background: #dee2e6;
        }
        .btn-dark span {
            font-size: 14px;
            text-align: center;
            color: #0000008c;
            font-weight: bold;
        }
        .btn-primary span {
            font-size: 14px;
            text-align: center;
            color: #fff;
            font-weight: bold;
        }
    </style>
</head>
<body>

<?php include 'sidebarhelpdesk.php'; ?>

<div class="container">
    <div class="card mx-auto">
        <div class="row">
            <div class="header right"><i class="fas fa-ellipsis-h"></i></div>
        </div>
        <div class="card-title">
            <p class="heading"><?php echo $ticket['category']; ?>&nbsp;<i class="far fa-compass"></i></p>
        </div>
        <p class="text-muted"><?php echo $ticket['Description']; ?></p>
        <h4>Replies</h4>
        <?php while ($reply = $reply_result->fetch_assoc()) { ?>
            <div class="reply">
                <p><?php echo $reply['Reply_Description']; ?></p>
                <p><small><?php echo $reply['CreatedOn']; ?></small></p>
            </div>
        <?php } ?>
        <div class="row btnsubmit mt-4">
            
           
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
</body>
</html>
