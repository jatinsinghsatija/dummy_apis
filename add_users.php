<?php
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["status" => "error", "message" => "Only POST method is allowed"]);
    exit;
}

$file = 'users.json';

// Load current users
$users = [];
if (file_exists($file)) {
    $users = json_decode(file_get_contents($file), true);
}

// Get POST data
$first_name = $_POST['first_name'] ?? '';
$middle_name = $_POST['middle_name'] ?? '';
$last_name = $_POST['last_name'] ?? '';
$age = $_POST['age'] ?? '';
$company_name = $_POST['company_name'] ?? '';

if ($first_name && $last_name && $company_name) {
    // Auto-increment ID
    $id = count($users) > 0 ? end($users)['id'] + 1 : 1;

    $newUser = [
        'id' => $id,
        'first_name' => $first_name,
        'middle_name' => $middle_name,
        'last_name' => $last_name,
        'age' => intval($age),
        'company_name' => $company_name
    ];

    $users[] = $newUser;

    // Save to users.json
    if (file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT))) {
        echo json_encode([
            "status" => "success",
            "message" => "User added successfully",
            "user" => $newUser
        ]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to save data"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Missing required fields"]);
}
?>
