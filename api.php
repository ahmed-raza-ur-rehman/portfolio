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
        // ============ CREATE ============
    // Receives form data via POST, inserts into database
    case 'create':
        // Get data from the POST request
        $title = $conn->real_escape_string($_POST['title']);
        $desc  = $conn->real_escape_string($_POST['description']);
        $tech  = $conn->real_escape_string($_POST['tech']);
        $link  = $conn->real_escape_string($_POST['link']);

        // Insert into database
        $conn->query("INSERT INTO projects (title, description, tech, link) 
                      VALUES ('$title', '$desc', '$tech', '$link')");

        // Return success + the new ID
        echo json_encode([
            'success' => true, 
            'id' => $conn->insert_id,
            'message' => 'Project created!'
        ]);
        break;
        // ============ UPDATE ============
    // Receives form data + project ID, updates that row
    case 'update':
        $id    = (int)$_POST['id'];  // (int) = force it to be a number
        $title = $conn->real_escape_string($_POST['title']);
        $desc  = $conn->real_escape_string($_POST['description']);
        $tech  = $conn->real_escape_string($_POST['tech']);
        $link  = $conn->real_escape_string($_POST['link']);

        $conn->query("UPDATE projects 
                      SET title='$title', description='$desc', tech='$tech', link='$link' 
                      WHERE id=$id");

        echo json_encode([
            'success' => true,
            'message' => 'Project updated!'
        ]);
        break;

    // ============ DELETE ============
    // Receives a project ID, deletes that row
    case 'delete':
        $id = (int)$_POST['id'];
        $conn->query("DELETE FROM projects WHERE id=$id");

        echo json_encode([
            'success' => true,
            'message' => 'Project deleted!'
        ]);
        break;

    default:
        echo json_encode(['error' => 'Unknown action']);
}
?>