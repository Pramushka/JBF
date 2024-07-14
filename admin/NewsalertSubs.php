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
        body {
            background-color: #f9f9fa;
        }
        .flex {
            -webkit-box-flex: 1;
            -ms-flex: 1 1 auto;
            flex: 1 1 auto;
        }
        @media (max-width:991.98px) {
            .padding {
                padding: 1.5rem;
            }
        }
        @media (max-width:767.98px) {
            .padding {
                padding: 1rem;
            }
        }
        .padding {
            padding: 5rem;
        }
        .card {
            box-shadow: none;
            -webkit-box-shadow: none;
            -moz-box-shadow: none;
            -ms-box-shadow: none;
        }
        .pl-3,
        .px-3 {
            padding-left: 1rem !important;
        }
        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 1px solid #d2d2dc;
            border-radius: 0;
        }
        .card .card-title {
            color: #000000;
            margin-bottom: 0.625rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .card .card-description {
            margin-bottom: .875rem;
            font-weight: 400;
            color: #76838f;
        }
        p {
            font-size: 0.875rem;
            margin-bottom: .5rem;
            line-height: 1.5rem;
        } 
        .table-responsive {
            display: block;
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            -ms-overflow-style: -ms-autohiding-scrollbar;
        }
        .table,
        .jsgrid .jsgrid-table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 1rem;
            background-color: transparent;
        }
        .table thead th,
        .jsgrid .jsgrid-table thead th {
            border-top: 0;
            border-bottom-width: 1px;
            font-weight: 500;
            font-size: .875rem;
            text-transform: uppercase;
        }
        .table td,
        .jsgrid .jsgrid-table td {
            font-size: 0.875rem;
            padding: .475rem 0.4375rem;
        }
        .mt-10 {
            padding: 0.875rem 0.3375rem !important;
        }  
        button {
            outline: 0 !important;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0rem rgba(0,123,255,.25) !important;
        }
        .badge {
            border-radius: 0;
            font-size: 12px;
            line-height: 1;
            padding: .375rem .5625rem;
            font-weight: normal;
            border: none;
        }
    </style>
</head>
<body>
<div class="page-content page-container" id="page-content">
    <div class="padding">
        <div class="row container d-flex justify-content-center">
            <div class="col-lg-8 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title text-center">Manage Inquiries</h4>
                        <hr>
                        <div class="table-responsive">
                            <table id="faqs" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>Description</th>
                                        <th>Created On</th>
                                        <th>Replied</th>
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
                                            echo "<td>" . ($row['Repyed'] ? 'Yes' : 'No') . "</td>";
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
                        <div class="text-center"><button onclick="addfaqs();" class="badge badge-success"><i class="fa fa-plus"></i> ADD NEW</button></div>
                    </div>
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
