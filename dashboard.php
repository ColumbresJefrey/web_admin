<?php
include 'config.php'; // Ensure database connection is included

// Initialize variables with default values
$userCount = 0;
$stylistCount = 0;
$appointmentCount = 0;
$topStylistName = "N/A";
$topStylistRating = "N/A";
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

// Fetch top-rated stylist
$topStylistQuery = mysqli_query($conn, "SELECT stylist_name, rating FROM stylists ORDER BY rating DESC LIMIT 1");
if ($topStylistRow = mysqli_fetch_assoc($topStylistQuery)) {
    $topStylistName = $topStylistRow['stylist_name'];
    $topStylistRating = $topStylistRow['rating'];
}

// Fetch upcoming appointments
$appointmentListQuery = mysqli_query($conn, "
    SELECT users.name, appointments.appointment_date, appointments.appointment_time 
    FROM appointments 
    JOIN users ON appointments.customer_id = users.id 
    WHERE appointment_date >= CURDATE() 
    ORDER BY appointment_date ASC 
    LIMIT 5
");

while ($row = mysqli_fetch_assoc($appointmentListQuery)) {
    $upcomingAppointments[] = $row;
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
 body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }

        h1 {
            color: #333;
        }

        .dashboard-container {
            padding: 40px;
            width: 100%;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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

        .users {
            background: linear-gradient(135deg, #007bff, #0056b3);
        }

        .stylists {
            background: linear-gradient(135deg, #28a745, #1e7e34);
        }

        .appointments {
            background: linear-gradient(135deg, #dc3545, #c82333);
        }

        .stat-box i {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .users i {
            color: #003c80;
        }

        .stylists i {
            color: #145a32;
        }

        .appointments i {
            color: #7b1818;
        }

.chart-container {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    display: flex;
    justify-content: center;
    align-items: center;
    max-width: 600px;
    height: 400px;
    margin: auto;
}

.info-section {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-top: 30px;
}

.info-box {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    border: 1px solid #ddd;
}

.info-box h2 {
    margin-bottom: 15px;
    color: #333;
    font-size: 20px; /* Larger font size for headings */
}

.info-box p, .info-box ul {
    font-size: 16px;
    color: #555;
}

.info-box ul {
    padding-left: 20px; /* Indent list items for better readability */
}

.info-box i {
    margin-right: 8px; /* Space between icon and text */
}

ul {
    list-style: none;
    padding: 0;
}

li {
    margin: 10px 0;
    font-size: 16px;
    color: #34495e;
}

/* Responsive Fix */
@media (max-width: 768px) {
    .stats, .info-section { flex-direction: column; }
    .dashboard-container { width: 100%; padding: 20px; }
}


    </style>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <link rel="stylesheet" href="sidebar.css">

    <div class="dashboard-container">
        <h1><i class="fas fa-chart-line"></i> Admin Dashboard</h1>

        <div class="stats">
            <div class="stat-box users"><i class="fas fa-users"></i> Total Users: <span><?php echo $userCount; ?></span></div>
            <div class="stat-box stylists"><i class="fas fa-user-tie"></i> Total Stylists: <span><?php echo $stylistCount; ?></span></div>
            <div class="stat-box appointments"><i class="fas fa-calendar-check"></i> Total Appointments: <span><?php echo $appointmentCount; ?></span></div>
        </div>

        <div class="chart-container">
            <canvas id="chart"></canvas>
        </div>

        <div class="info-section">
    <div class="info-box top-stylist">
        <h2><i class="fas fa-star"></i> Top Rated Stylist</h2>
        <p><i class="fas fa-user"></i> Name: <strong><?php echo $topStylistName; ?></strong></p>
        <p><i class="fas fa-star-half-alt"></i> Rating: <strong><?php echo $topStylistRating; ?></strong></p>
    </div>

    <div class="info-box upcoming-appointments">
        <h2><i class="fas fa-calendar-alt"></i> Upcoming Appointments</h2>
        <ul>
            <?php foreach ($upcomingAppointments as $appointment): ?>
                <li><i class="fas fa-clock"></i> <?php echo $appointment['appointment_date'] . ' at ' . $appointment['appointment_time'] . ' - ' . $appointment['name']; ?></li>
            <?php endforeach; ?>
        </ul>
        </div>
      </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let ctx = document.getElementById("chart").getContext("2d");
            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: ["Users", "Stylists", "Appointments"],
                    datasets: [{
                        label: "Count",
                        data: [<?php echo $userCount; ?>, <?php echo $stylistCount; ?>, <?php echo $appointmentCount; ?>],
                        backgroundColor: ["#007bff", "#28a745", "#dc3545"],
                        borderColor: ["#0056b3", "#1e7e34", "#c82333"],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        });
    </script>
</body>
</html>