<?php
include 'connection.php';

// Function to delete project and its tasks
function deleteProject($projectCode) {
    global $conn;
    // Delete tasks associated with the project
    $stmtDeleteTasks = $conn->prepare("DELETE FROM tasks WHERE project_code = :project_code");
    $stmtDeleteTasks->bindParam(':project_code', $projectCode);
    $stmtDeleteTasks->execute();
    // Delete project
    $stmtDeleteProject = $conn->prepare("DELETE FROM projects WHERE project_code = :project_code");
    $stmtDeleteProject->bindParam(':project_code', $projectCode);
    $stmtDeleteProject->execute();
    return true;
}

// Check if delete request is made
if (isset($_GET['delete_project']) && isset($_GET['project_code'])) {
    $projectCode = $_GET['project_code'];
    if(deleteProject($projectCode)) {
        echo json_encode(array('success' => true));
        exit();
    } else {
        echo json_encode(array('success' => false, 'message' => 'Failed to delete project.'));
        exit();
    }
} elseif ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['task_id'])) {
    // Handle task deletion request
    $task_id = $_GET['task_id'];

    // Prepare and execute the query to delete task
    $stmt = $conn->prepare("DELETE FROM tasks WHERE task_id = :task_id");
    $stmt->bindParam(':task_id', $task_id);
    $stmt->execute();

    // Return a success message or any necessary response if required
    echo "Task deleted successfully.";
    exit();
} else {
    echo json_encode(array('success' => false, 'message' => 'Invalid request.'));
    exit();
}
?>
