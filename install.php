<?php
// Start error reporting
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Define the config file path
$config_path = __DIR__ . '/config.php';

echo "<h3>🚀 Setting up the Database...</h3>";

// Create Users Table 
$usersTable = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15),
    password VARCHAR(255) NOT NULL,  
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($usersTable) === TRUE) {
    echo "✅ Users table created successfully.<br>";
} else {
    echo "❌ Error creating users table: " . $conn->error . "<br>";
}

// Create Stylists Table with Ratings Column
$stylistsTable = "CREATE TABLE IF NOT EXISTS stylists (
    id INT AUTO_INCREMENT PRIMARY KEY,
    stylist_name VARCHAR(100) NOT NULL,
    expertise VARCHAR(100),
    phone VARCHAR(15),
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,  
    rating DECIMAL(3,2) DEFAULT 0.00,  -- New rating column
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
if ($conn->query($stylistsTable) === TRUE) {
    echo "✅ Stylists table created successfully.<br>";
} else {
    echo "❌ Error creating stylists table: " . $conn->error . "<br>";
}

// Create Appointments Table (Using Foreign Keys)
$appointmentsTable = "CREATE TABLE IF NOT EXISTS appointments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT NOT NULL,
    stylist_id INT NOT NULL,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    service VARCHAR(255) NOT NULL,  -- Added service column
    status ENUM('Pending', 'Completed', 'Cancelled') DEFAULT 'Pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (customer_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (stylist_id) REFERENCES stylists(id) ON DELETE CASCADE
)";
if ($conn->query($appointmentsTable) === TRUE) {
    echo "✅ Appointments table created successfully.<br>";
} else {
    echo "❌ Error creating appointments table: " . $conn->error . "<br>";
}

// Create Admin Table
$adminTable = "CREATE TABLE IF NOT EXISTS admin (s
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL  
)";
if ($conn->query($adminTable) === TRUE) {
    echo "✅ Admin table created successfully.<br>";
} else {
    echo "❌ Error creating admin table: " . $conn->error . "<br>";
}

// Close the database connection
$conn->close();

echo "<br>🎉 Database setup complete!";
?>
