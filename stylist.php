<?php
session_start();

include 'config.php';

// Fetch stylists from the database
$query = "SELECT id, stylist_name , expertise, phone, email FROM stylists";
$stylists = $conn->query($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_query = "DELETE FROM stylists WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        header("Location: stylist.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard - Stylists</title>
    <link rel="stylesheet" href="stylist.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    <?php include 'sidebar.php'; ?>
    <div class="dashboard-container">
        <h1>Stylist Management</h1>

        <input type="text" id="searchStylist" class="search-box" placeholder="Search Stylists...">

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Expertise</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $stylists->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['stylist_name']; ?></td>
                        <td><?= $row['expertise']; ?></td>
                        <td><?= $row['phone']; ?></td>
                        <td><?= $row['email']; ?></td>
                        <td>
                            <button class="edit-btn" onclick="editStylist(<?= $row['id']; ?>)">Edit</button>
                            <button class="delete-btn" onclick="deleteStylist(<?= $row['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="stylist.js"></script>
</body>
</html>
