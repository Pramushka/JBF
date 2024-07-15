<?php
session_start();
include '../includes/dbconn.php';

// Fetch data for counters
$learningCoursesCount = $conn->query("SELECT COUNT(*) as count FROM learning_courses WHERE IsDeleted = 0")->fetch_assoc()['count'];
$helpdeskInquiriesCount = $conn->query("SELECT COUNT(*) as count FROM helpdeskinquiries")->fetch_assoc()['count'];
$jobPostsCount = $conn->query("SELECT COUNT(*) as count FROM jobpost WHERE IsDeleted = 0")->fetch_assoc()['count'];
$organizationsCount = $conn->query("SELECT COUNT(*) as count FROM organization")->fetch_assoc()['count'];

// Fetch data for charts
$industryQuery = $conn->query("SELECT Industry, COUNT(*) as count FROM learning_courses WHERE IsDeleted = 0 GROUP BY Industry");
$industryData = [];
while ($row = $industryQuery->fetch_assoc()) {
    $industryData[] = $row;
}

$inquiriesStatusQuery = $conn->query("SELECT status, COUNT(*) as count FROM helpdeskinquiries GROUP BY status");
$inquiriesStatusData = [];
while ($row = $inquiriesStatusQuery->fetch_assoc()) {
    $inquiriesStatusData[] = $row;
}

$jobCategoriesQuery = $conn->query("SELECT job_category, COUNT(*) as count FROM jobpost WHERE IsDeleted = 0 GROUP BY job_category");
$jobCategoriesData = [];
while ($row = $jobCategoriesQuery->fetch_assoc()) {
    $jobCategoriesData[] = $row;
}

$skillsQuery = $conn->query("SELECT Skill, COUNT(*) as count FROM learning_courses WHERE IsDeleted = 0 GROUP BY Skill");
$skillsData = [];
while ($row = $skillsQuery->fetch_assoc()) {
    $skillsData[] = $row;
}

$inquiriesPriorityQuery = $conn->query("SELECT priority, COUNT(*) as count FROM helpdeskinquiries GROUP BY priority");
$inquiriesPriorityData = [];
while ($row = $inquiriesPriorityQuery->fetch_assoc()) {
    $inquiriesPriorityData[] = $row;
}

$jobIndustryQuery = $conn->query("SELECT Industry, COUNT(*) as count FROM jobpost WHERE IsDeleted = 0 GROUP BY Industry");
$jobIndustryData = [];
while ($row = $jobIndustryQuery->fetch_assoc()) {
    $jobIndustryData[] = $row;
}

$organizationIndustryQuery = $conn->query("SELECT Org_Industry, COUNT(*) as count FROM organization GROUP BY Org_Industry");
$organizationIndustryData = [];
while ($row = $organizationIndustryQuery->fetch_assoc()) {
    $organizationIndustryData[] = $row;
}

$coursesMonthlyQuery = $conn->query("SELECT DATE_FORMAT(CreatedOn, '%Y-%m') as month, COUNT(*) as count FROM learning_courses WHERE IsDeleted = 0 GROUP BY month");
$coursesMonthlyData = [];
while ($row = $coursesMonthlyQuery->fetch_assoc()) {
    $coursesMonthlyData[] = $row;
}

$inquiriesMonthlyQuery = $conn->query("SELECT DATE_FORMAT(CreatedOn, '%Y-%m') as month, COUNT(*) as count FROM helpdeskinquiries GROUP BY month");
$inquiriesMonthlyData = [];
while ($row = $inquiriesMonthlyQuery->fetch_assoc()) {
    $inquiriesMonthlyData[] = $row;
}

$jobPostsMonthlyQuery = $conn->query("SELECT DATE_FORMAT(CreatedOn, '%Y-%m') as month, COUNT(*) as count FROM jobpost WHERE IsDeleted = 0 GROUP BY month");
$jobPostsMonthlyData = [];
while ($row = $jobPostsMonthlyQuery->fetch_assoc()) {
    $jobPostsMonthlyData[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
        .chart canvas{
            width: 100%;
            height: 400px;
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
                <div class="stat-item"><?= $learningCoursesCount ?> Learning Courses</div>
                <div class="stat-item"><?= $helpdeskInquiriesCount ?> Helpdesk Inquiries</div>
                <div class="stat-item"><?= $jobPostsCount ?> Job Posts</div>
                <div class="stat-item"><?= $organizationsCount ?> Organizations</div>
            </section>
            <section class="charts">
                <div class="chart">
                    <h2><i class="fas fa-chart-pie"></i> Learning Courses by Industry</h2>
                    <canvas id="industryChart"></canvas>
                </div>
                <div class="chart">
                    <h2><i class="fas fa-chart-bar"></i> Helpdesk Inquiries Status</h2>
                    <canvas id="inquiriesStatusChart"></canvas>
                </div>
                <div class="chart">
                    <h2><i class="fas fa-chart-bar"></i> Job Posts by Category</h2>
                    <canvas id="jobCategoriesChart"></canvas>
                </div>
                <div class="chart">
                    <h2><i class="fas fa-chart-pie"></i> Helpdesk Inquiries by Priority</h2>
                    <canvas id="inquiriesPriorityChart"></canvas>
                </div>
                <div class="chart">
                    <h2><i class="fas fa-chart-bar"></i> Courses by Skill</h2>
                    <canvas id="skillsChart"></canvas>
                </div>
                <div class="chart">
                    <h2><i class="fas fa-chart-bar"></i> Job Posts by Industry</h2>
                    <canvas id="jobIndustryChart"></canvas>
                </div>
                <div class="chart">
                    <h2><i class="fas fa-chart-pie"></i> Organizations by Industry</h2>
                    <canvas id="organizationIndustryChart"></canvas>
                </div>
                <div class="chart">
                    <h2><i class="fas fa-chart-line"></i> Monthly Learning Courses Creation</h2>
                    <canvas id="coursesMonthlyChart"></canvas>
                </div>
                <div class="chart">
                    <h2><i class="fas fa-chart-line"></i> Monthly Helpdesk Inquiries</h2>
                    <canvas id="inquiriesMonthlyChart"></canvas>
                </div>
                <div class="chart">
                    <h2><i class="fas fa-chart-line"></i> Monthly Job Posts</h2>
                    <canvas id="jobPostsMonthlyChart"></canvas>
                </div>
            </section>
        </main>
    </div>
    <script>
        // Learning Courses by Industry Chart
        const industryData = <?= json_encode($industryData) ?>;
        const industryLabels = industryData.map(data => data.Industry);
        const industryCounts = industryData.map(data => data.count);

        const industryChart = new Chart(document.getElementById('industryChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: industryLabels,
                datasets: [{
                    label: 'Learning Courses by Industry',
                    data: industryCounts,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
                }]
            }
        });

        // Helpdesk Inquiries Status Chart
        const inquiriesStatusData = <?= json_encode($inquiriesStatusData) ?>;
        const inquiriesStatusLabels = inquiriesStatusData.map(data => data.status);
        const inquiriesStatusCounts = inquiriesStatusData.map(data => data.count);

        const inquiriesStatusChart = new Chart(document.getElementById('inquiriesStatusChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: inquiriesStatusLabels,
                datasets: [{
                    label: 'Helpdesk Inquiries Status',
                    data: inquiriesStatusCounts,
                    backgroundColor: ['#FF6384', '#36A2EB']
                }]
            }
        });

        // Job Posts by Category Chart
        const jobCategoriesData = <?= json_encode($jobCategoriesData) ?>;
        const jobCategoriesLabels = jobCategoriesData.map(data => data.job_category);
        const jobCategoriesCounts = jobCategoriesData.map(data => data.count);

        const jobCategoriesChart = new Chart(document.getElementById('jobCategoriesChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: jobCategoriesLabels,
                datasets: [{
                    label: 'Job Posts by Category',
                    data: jobCategoriesCounts,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
                }]
            }
        });

        // Helpdesk Inquiries by Priority Chart
        const inquiriesPriorityData = <?= json_encode($inquiriesPriorityData) ?>;
        const inquiriesPriorityLabels = inquiriesPriorityData.map(data => data.priority);
        const inquiriesPriorityCounts = inquiriesPriorityData.map(data => data.count);

        const inquiriesPriorityChart = new Chart(document.getElementById('inquiriesPriorityChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: inquiriesPriorityLabels,
                datasets: [{
                    label: 'Helpdesk Inquiries by Priority',
                    data: inquiriesPriorityCounts,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
                }]
            }
        });

        // Courses by Skill Chart
        const skillsData = <?= json_encode($skillsData) ?>;
        const skillsLabels = skillsData.map(data => data.Skill);
        const skillsCounts = skillsData.map(data => data.count);

        const skillsChart = new Chart(document.getElementById('skillsChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: skillsLabels,
                datasets: [{
                    label: 'Courses by Skill',
                    data: skillsCounts,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
                }]
            }
        });

        // Job Posts by Industry Chart
        const jobIndustryData = <?= json_encode($jobIndustryData) ?>;
        const jobIndustryLabels = jobIndustryData.map(data => data.Industry);
        const jobIndustryCounts = jobIndustryData.map(data => data.count);

        const jobIndustryChart = new Chart(document.getElementById('jobIndustryChart').getContext('2d'), {
            type: 'bar',
            data: {
                labels: jobIndustryLabels,
                datasets: [{
                    label: 'Job Posts by Industry',
                    data: jobIndustryCounts,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
                }]
            }
        });

        // Organizations by Industry Chart
        const organizationIndustryData = <?= json_encode($organizationIndustryData) ?>;
        const organizationIndustryLabels = organizationIndustryData.map(data => data.Org_Industry);
        const organizationIndustryCounts = organizationIndustryData.map(data => data.count);

        const organizationIndustryChart = new Chart(document.getElementById('organizationIndustryChart').getContext('2d'), {
            type: 'pie',
            data: {
                labels: organizationIndustryLabels,
                datasets: [{
                    label: 'Organizations by Industry',
                    data: organizationIndustryCounts,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
                }]
            }
        });

        // Monthly Learning Courses Creation Chart
        const coursesMonthlyData = <?= json_encode($coursesMonthlyData) ?>;
        const coursesMonthlyLabels = coursesMonthlyData.map(data => data.month);
        const coursesMonthlyCounts = coursesMonthlyData.map(data => data.count);

        const coursesMonthlyChart = new Chart(document.getElementById('coursesMonthlyChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: coursesMonthlyLabels,
                datasets: [{
                    label: 'Monthly Learning Courses Creation',
                    data: coursesMonthlyCounts,
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            }
        });

        // Monthly Helpdesk Inquiries Chart
        const inquiriesMonthlyData = <?= json_encode($inquiriesMonthlyData) ?>;
        const inquiriesMonthlyLabels = inquiriesMonthlyData.map(data => data.month);
        const inquiriesMonthlyCounts = inquiriesMonthlyData.map(data => data.count);

        const inquiriesMonthlyChart = new Chart(document.getElementById('inquiriesMonthlyChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: inquiriesMonthlyLabels,
                datasets: [{
                    label: 'Monthly Helpdesk Inquiries',
                    data: inquiriesMonthlyCounts,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            }
        });

        // Monthly Job Posts Chart
        const jobPostsMonthlyData = <?= json_encode($jobPostsMonthlyData) ?>;
        const jobPostsMonthlyLabels = jobPostsMonthlyData.map(data => data.month);
        const jobPostsMonthlyCounts = jobPostsMonthlyData.map(data => data.count);

        const jobPostsMonthlyChart = new Chart(document.getElementById('jobPostsMonthlyChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: jobPostsMonthlyLabels,
                datasets: [{
                    label: 'Monthly Job Posts',
                    data: jobPostsMonthlyCounts,
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderColor: 'rgba(153, 102, 255, 1)',
                    borderWidth: 1
                }]
            }
        });
    </script>
</body>
</html>
