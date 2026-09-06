<?php
// Tell the browser: "This response is JSON, not HTML"
header('Content-Type: application/json');

// Allow requests from any origin (needed for fetch() to work)
header('Access-Control-Allow-Origin: *');

// Connect to database
require 'config.php';

// What action is being requested?
// api.php?action=read → $action = "read"
// api.php?action=create → $action = "create"
$action = $_GET['action'] ?? 'read';

switch ($action) {

    // ============ READ ============
    // Returns ALL projects as JSON
    case 'read':
        $result = $conn->query("SELECT * FROM projects ORDER BY created_at DESC");
        $projects = [];
        while ($row = $result->fetch_assoc()) {
            $projects[] = $row;
        }
        echo json_encode($projects);
        break;

    default:
        echo json_encode(['error' => 'Unknown action']);
}
?>