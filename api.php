<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

// Include database configuration
require 'config.php';

$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'] ?? '', '/'));
$resource = array_shift($request);
$id = is_numeric($request[0] ?? null) ? (int) array_shift($request) : null;

$conn = new mysqli($servername, $username, $password, $database);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed: " . $conn->connect_error]));
}

switch ($resource) {
    case 'users':
        if ($method == 'GET') {
            $stmt = $id ? $conn->prepare("SELECT * FROM users WHERE id = ?") : $conn->prepare("SELECT * FROM users");
            if ($id) $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        } elseif ($method == 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            $stmt = $conn->prepare("INSERT INTO users (customer_name, email, phone, password) VALUES (?, ?, ?, ?)");
            $hashed_password = password_hash($data['password'], PASSWORD_BCRYPT);
            $stmt->bind_param("ssss", $data['customer_name'], $data['email'], $data['phone'], $hashed_password);
            echo json_encode(["status" => $stmt->execute() ? "success" : "error", "message" => $stmt->error]);
        }
        break;
    
    case 'stylists':
        if ($method == 'GET') {
            $stmt = $id ? $conn->prepare("SELECT * FROM stylists WHERE id = ?") : $conn->prepare("SELECT * FROM stylists");
            if ($id) $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        }
        break;
    
    case 'appointments':
        if ($method == 'GET') {
            $stmt = $id ? $conn->prepare("SELECT * FROM appointments WHERE id = ?") : $conn->prepare("SELECT * FROM appointments");
            if ($id) $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            echo json_encode($result->fetch_all(MYSQLI_ASSOC));
        } elseif ($method == 'POST') {
            $data = json_decode(file_get_contents("php://input"), true);
            $stmt = $conn->prepare("INSERT INTO appointments (customer_id, stylist_id, appointment_date, appointment_time, service, status) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("iissss", $data['customer_id'], $data['stylist_id'], $data['appointment_date'], $data['appointment_time'], $data['service'], $data['status']);
            echo json_encode(["status" => $stmt->execute() ? "success" : "error", "message" => $stmt->error]);
        } elseif ($method == 'DELETE' && $id) {
            $stmt = $conn->prepare("DELETE FROM appointments WHERE id = ?");
            $stmt->bind_param("i", $id);
            echo json_encode(["status" => $stmt->execute() ? "success" : "error", "message" => $stmt->error]);
        }
        break;
    
    default:
        echo json_encode(["status" => "error", "message" => "Invalid endpoint"]);
} 

$conn->close();
?>
