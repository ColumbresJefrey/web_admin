<?php
session_start();

include 'config.php';

// Handle appointment deletion
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_query = "DELETE FROM appointments WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        header("Location: appointments.php");
        exit();
    }
}

// Fetch appointments with correct joins
$appointments_query = "SELECT a.id, u.name AS customer_name, s.stylist_name AS stylist_name, 
                              a.appointment_date, a.service, a.status
                       FROM appointments a
                       JOIN users u ON a.customer_id = u.id
                       JOIN stylists s ON a.stylist_id = s.id";

$appointments = $conn->query($appointments_query);
if (!$appointments) {
    die("Error fetching appointments: " . $conn->error);
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard - Appointments</title>
    <link rel="stylesheet" href="appointments.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="dashboard-container">
        <h1>Appointment Management</h1>

        <input type="text" id="searchAppointment" class="search-box" placeholder="Search Appointments...">

        <table>
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Stylist Name</th>
                    <th>Appointment Date</th>
                    <th>Service</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $appointments->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['customer_name']); ?></td>
                        <td><?= htmlspecialchars($row['stylist_name']); ?></td>
                        <td><?= htmlspecialchars($row['appointment_date']); ?></td>
                        <td><?= htmlspecialchars($row['service']); ?></td>
                        <td><?= htmlspecialchars($row['status']); ?></td>
                        <td>
                            <button class="edit-btn" onclick="editAppointment(<?= $row['id']; ?>)">Edit</button>
                            <button class="delete-btn" onclick="deleteAppointment(<?= $row['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="appointments.js"></script>
</body>
</html>
                    