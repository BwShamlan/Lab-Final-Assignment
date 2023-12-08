<?php
// Check HTTP request method
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    header('Allow: GET');
    http_response_code(405);
    echo json_encode(array('message' => 'Method not allowed'));
    return;
}

// Set HTTP response headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET');

include_once '../db/Database.php';
include_once '../models/Bookmark.php';

// Instantiate a Database object & connect
$database = new Database();
$dbConnection = $database->connect();

// Instantiate Bookmark object
$bookmark = new Bookmark($dbConnection);

// Get the HTTP GET request query parameters
$searchTerm = isset($_GET['searchTerm']) ? $_GET['searchTerm'] : null;

// Check if a search term is provided
if ($searchTerm !== null) {
    // Use the searchByTitle method with a search term
    $result = $bookmark->searchByTitle($searchTerm);
} else {
    // Get all bookmarks if no search term is provided
    $result = $bookmark->searchByTitle();
}

if (!empty($result)) {
    echo json_encode($result);
} else {
    http_response_code(404);
    echo json_encode(array('message' => 'No bookmark items were found'));
}
