<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* General styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            background-color: #f8f9fa;
        }

        .container {
            display: flex;
            width: 100%;
        }

        .main-content {
            flex-grow: 1;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header {
            padding: 20px 0;
            text-align: center;
            border-bottom: 1px solid #e0e0e0;
        }

        header h1 {
            margin: 0;
            font-size: 28px;
            color: #333333;
        }

        /* Stats section */
        .stats {
            display: flex;
            justify-content: space-around;
            margin: 30px 0;
        }

        .stat-item {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            min-width: 160px;
        }

        .stat-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }

        /* Charts section */
        .charts {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            margin-top: 30px;
        }

        .chart {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            flex: 1 1 calc(50% - 30px);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            min-width: 300px;
        }

        .chart:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
        }

        .chart h2 {
            margin-top: 0;
            font-size: 20px;
            color: #333333;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .chart h2 i {
            font-size: 24px;
            margin-right: 10px;
        }
        .chart img{
            width: 300px;
            height:200px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .charts {
                flex-direction: column;
            }

            .chart {
                flex: 1 1 100%;
                margin-bottom: 30px;
            }
        }
    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="container">
        <main class="main-content">
            <header>
                <h1>Hi, Welcome back <i class="far fa-handshake"></i></h1>
            </header>
            <section class="stats">
                <div class="stat-item">714k Weekly Sales</div>
                <div class="stat-item">1.35m New Users</div>
                <div class="stat-item">1.72m Item Orders</div>
                <div class="stat-item">234 Bug Reports</div>
            </section>
            <section class="charts">
                <div class="chart">
                    <h2><i class="fas fa-chart-line"></i> Website Visits</h2>
                    <img src="../assets/img/logo/wvc.png" alt="Website Visits Chart">
                </div>
                <div class="chart">
                    <h2><i class="fas fa-users"></i> Current Visits</h2>
                    <img src="../assets/img/logo/cvc.jpg" alt="Current Visits Chart">
                </div>
                <div class="chart">
                    <h2><i class="fas fa-exchange-alt"></i> Conversion Rates</h2>
                    <img src="../assets/img/logo/cr.jpg" alt="Conversion Rates Chart">
                </div>
                <div class="chart">
                    <h2><i class="fas fa-book"></i> Current Subject</h2>
                    <img src="../assets/img/logo/wvc.png" alt="Current Subject Chart">
                </div>
            </section>
        </main>
    </div>
</body>
</html>
