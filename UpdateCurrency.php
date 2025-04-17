<?php
session_start();

$data = json_decode(file_get_contents('php://input'), true);
if (isset($data['currency'])) {
    $_SESSION['preferred_currency'] = $data['currency'];
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error']);
}
?>