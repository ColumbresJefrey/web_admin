<?php

include 'config.php';

// Fetch users from the database
$query = "SELECT id, name, phone, email FROM users";
$users = $conn->query($query);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];
    $delete_query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        header("Location: users.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Dashboard - Users</title>
    <link rel="stylesheet" href="users.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include 'sidebar.php'; ?>
    <div class="dashboard-container">
        <h1>Users Management</h1>

        <input type="text" id="searchUser" class="search-box" placeholder="Search Users...">

        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>  
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $users->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id']; ?></td>
                        <td><?= $row['name']; ?></td>
                        <td><?= $row['phone']; ?></td>
                        <td><?= $row['email']; ?></td>
                        <td>
                            <button class="edit-btn" onclick="editUser(<?= $row['id']; ?>)">Edit</button>
                            <button class="delete-btn" onclick="deleteUser(<?= $row['id']; ?>)">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="users.js"></script>
</body>
</html>
