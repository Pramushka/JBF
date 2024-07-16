<?php
include '../includes/dbconn.php';
session_start();

// Create connection

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch data from the contact_inq table
$sql = "SELECT * FROM contact_inq";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiries Admin Page</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css">
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
            background-color: #f9f9fa;
            display: flex;
            min-height: 100vh;
            margin: 0;
        }
        .content {
            flex-grow: 1;
            padding: 20px;
        }
        .padding {
            padding: 2rem;
        }
        .card {
            box-shadow: none;
            width: 80%;
            margin-left: 300px;
            border: 1px solid #d2d2dc;
            background-color: #fff;
        }
        .card .card-title {
            color: #000000;
            margin-bottom: 0.625rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
        }
        .table th,
        .table td {
            font-size: 0.875rem;
        }
        button {
            outline: 0 !important;
        }
        .form-control:focus {
            box-shadow: none !important;
        }
        .badge {
            border-radius: 0;
            font-size: 12px;
            padding: .375rem .5625rem;
            font-weight: normal;
            border: none;
        }
        @media (max-width: 767.98px) {
            .padding {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>

    <div class="content">
        
        <div class="padding">
            
            <div class="card">
                <div class="card-body">
                     <?php include 'sidebar.php'; ?>
                    <h4 class="card-title text-center">Manage Inquiries</h4>
                    <hr>
                    <div class="table-responsive">
                        
                        <table id="faqs" class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Description</th>
                                    <th>Created On</th>
                                   
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . $row['Email'] . "</td>";
                                        echo "<td>" . $row['Description'] . "</td>";
                                        echo "<td>" . $row['CreatedOn'] . "</td>";
                                       
                                        echo '<td class="mt-10"><button class="badge badge-danger" onclick="$(this).closest(\'tr\').remove();"><i class="fa fa-trash"></i> Delete</button></td>';
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center'>No inquiries found</td></tr>";
                                }
                                $conn->close();
                                ?>
                            </tbody>
                        </table>
                    </div>
                 
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script>
        var faqs_row = 0;
        function addfaqs() {
            var html = '<tr id="faqs-row' + faqs_row + '">';
            html += '<td><input type="text" class="form-control" placeholder="Email"></td>';
            html += '<td><input type="text" class="form-control" placeholder="Description"></td>';
            html += '<td><input type="text" class="form-control" placeholder="Created On"></td>';
            html += '<td><input type="text" class="form-control" placeholder="Replied"></td>';
            html += '<td class="mt-10"><button class="badge badge-danger" onclick="$(\'#faqs-row' + faqs_row + '\').remove();"><i class="fa fa-trash"></i> Delete</button></td>';
            html += '</tr>';

            $('#faqs tbody').append(html);
            faqs_row++;
        }
    </script>
</body>
</html>
