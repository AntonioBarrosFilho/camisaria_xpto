<?php

$HostName = "localhost";
$DatabaseName = "camisaria_xpto";
$User = "root";
$Password = "";

function returnMissingParameters() {
    $output['error'] = true;
    $output['message'] = 'missing parameters.';
    http_response_code(400);
    echo json_encode($output);
    die;
}

function successResponse() {
    $output['error'] = false;
    $output['message'] = 'success.';
    http_response_code(201);
    echo json_encode($output);
    die;
}

try {
    $pdo = new PDO("mysql:host=$HostName;dbname=$DatabaseName;charset=utf8", $User, $Password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $data = json_decode(file_get_contents("php://input"), true);
} catch (Exception $e) {
    error_log($e->getMessage()); // Loga o erro
    echo json_encode(['error' => true, 'message' => 'Database connection error']);
    die;
}

?>
