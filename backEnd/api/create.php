<?php
// Check Request Method
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Allow: POST');
    http_response_code(405);
    echo json_encode('Method Not Allowed');
    return;
}

// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');

include_once '../db/Database.php';
include_once '../models/Bookmark.php';

// Instantiate a Database object & connect
$database = new Database();
$dbConnection = $database->connect();

// Instantiate Bookmark object
$bookmark = new Bookmark($dbConnection);

// Get the HTTP POST request JSON body
$data = json_decode(file_get_contents("php://input"), true);

// Check if required parameters are present in the JSON body
if (!$data || !isset($data['URL']) || !isset($data['title'])) {
    http_response_code(422);
    echo json_encode(
        array('message' => 'Error: Missing required parameters URL and/or title in the JSON body.')
    );
    return;
}

$bookmark->setUrl($data['URL']);
$bookmark->setTitle($data['title']);

// Create Bookmark
if ($bookmark->create()) {
    echo json_encode(
        array('message' => 'A bookmark was created')
    );
} else {
    echo json_encode(
        array('message' => 'Error: A bookmark was not created')
    );
}
