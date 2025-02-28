<?php
include 'config.php'; // Ensure database connection is included

// Initialize variables with default values
$userCount = 0;
$stylistCount = 0;
$appointmentCount = 0;
$topStylists = [];
$upcomingAppointments = [];

// Fetch total users
$userQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
if ($userRow = mysqli_fetch_assoc($userQuery)) {
    $userCount = $userRow['total'];
}

// Fetch total stylists
$stylistQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM stylists");
if ($stylistRow = mysqli_fetch_assoc($stylistQuery)) {
    $stylistCount = $stylistRow['total'];
}

// Fetch total appointments
$appointmentQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM appointments");
if ($appointmentRow = mysqli_fetch_assoc($appointmentQuery)) {
    $appointmentCount = $appointmentRow['total'];
}

// Fetch top-rated stylists (Top 3)
$topStylistQuery = mysqli_query($conn, "SELECT stylist_name, rating FROM stylists ORDER BY rating DESC LIMIT 3");
while ($row = mysqli_fetch_assoc($topStylistQuery)) {
    $topStylists[] = $row;
}

// Fetch upcoming appointments
$appointments_query = "SELECT a.id, u.name AS customer_name, s.stylist_name AS stylist_name, 
                              a.appointment_date, a.service, a.status
                       FROM appointments a
                       JOIN users u ON a.customer_id = u.id
                       JOIN stylists s ON a.stylist_id = s.id
                       ORDER BY rating DESC LIMIT 3";
$appointments = $conn->query($appointments_query);
if (!$appointments) {
    die("Error fetching appointments: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <?php include 'sidebar.php'; ?>
    <link rel="stylesheet" href="sidebar.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            display: flex;
        }
        .sidebar {
            width: 250px;
        }
        .dashboard-container {
            flex: 1;
            max-width: calc(100% - 270px);
            margin-left: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
        }
        .notification {
            position: absolute;
            top: 20px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
        }
        .stats {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }
        .stat-box {
            flex: 1;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            min-width: 150px;
            color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .users { background: #007bff; }
        .stylists { background: #28a745; }
        .appointments { background: #dc3545; }
        .stat-box i { font-size: 40px; margin-bottom: 10px; }
        .main-content {
            display: flex;
            gap: 20px;
            margin-top: 30px;
        }
        /* Updated both containers to have the same flex value */
        .chart-container,
        .top-stylist {
            flex: 1;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            min-height: 400px;
        }
        .top-stylist ul {
            list-style: none;
            padding: 0;
        }
        .top-stylist li {
            font-size: 18px;
            margin: 10px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .top-stylist i {
            margin-right: 10px;
        }
        .appointments-section {
            margin-top: 30px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <i class="fas fa-bell notification"></i>
        <h1><i class="fas fa-chart-line"></i> Admin Dashboard</h1>
        <div class="stats">
            <div class="stat-box users"><i class="fas fa-users"></i> Users: <span><?php echo $userCount; ?></span></div>
            <div class="stat-box stylists"><i class="fas fa-user-tie"></i> Stylists: <span><?php echo $stylistCount; ?></span></div>
            <div class="stat-box appointments"><i class="fas fa-calendar-check"></i> Appointments: <span><?php echo $appointmentCount; ?></span></div>
        </div>
        <div class="main-content">
            <div class="chart-container">
                <canvas id="chart"></canvas>
            </div>
            <div class="top-stylist">
                <h2><i class="fas fa-star"></i> Top Rated Stylists</h2>
                <ul>
                    <?php foreach ($topStylists as $stylist): ?>
                        <li><i class="fas fa-user-circle"></i><strong><?php echo htmlspecialchars($stylist['stylist_name']); ?></strong> - <span><?php echo htmlspecialchars($stylist['rating']); ?>★</span></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        <div class="appointments-section">
            <h2>Upcoming Appointments</h2>
            <table>
                <tr>
                    <th>Customer Name</th>
                    <th>Stylist Name</th>
                    <th>Appointment Date</th>
                    <th>Service</th>
                    <th>Status</th>
                </tr>
                <?php while ($row = $appointments->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['stylist_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['appointment_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['service']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let ctx = document.getElementById("chart").getContext("2d");
            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: ["Users", "Stylists", "Appointments"],
                    datasets: [{
                        label: "Count",
                        data: [<?php echo $userCount; ?>, <?php echo $stylistCount; ?>, <?php echo $appointmentCount; ?>],
                        backgroundColor: ["#007bff", "#28a745", "#dc3545"]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });
    </script>
</body>
</html>
