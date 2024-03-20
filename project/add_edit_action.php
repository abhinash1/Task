<?php
// Include the connection file
include 'connection.php';
error_reporting(0);
/*echo"<pre>";
print_r($_REQUEST);die;*/
// Check if form is submitted for project addition or update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['action']) && $_POST['action'] == 'edit') {
        // Prepare and execute the query to update project details
        $project_name     = $_POST['project_name'];
        $project_code     = $_POST['project_code'];
        $new_project_code = $_POST['new_project_code'];
		
		if ($project_code !== $new_project_code) {
			$stmt_check = $conn->prepare("SELECT COUNT(*) FROM projects WHERE project_code = :new_project_code");
            $stmt_check->bindParam(':new_project_code', $new_project_code);
            $stmt_check->execute();
            $count = $stmt_check->fetchColumn(); 
		}
		
		if ($count > 0) {
			//Project code already exists, so display an error
            echo "Error: Project code already exists!";
		} else {
		
        
        $stmt = $conn->prepare("UPDATE projects SET project_name = :project_name, project_code = :new_project_code WHERE project_code = :project_code");
        $stmt->bindParam(':project_name', $project_name);
        $stmt->bindParam(':new_project_code', $new_project_code);
        $stmt->bindParam(':project_code', $project_code);
        $stmt->execute();

        // Update tasks if they are provided in the form
        if (isset($_POST['task_names']) && isset($_POST['task_hours'])) {
            $task_names = $_POST['task_names'];
            $task_hours = $_POST['task_hours'];
            $task_ids = $_POST['task_ids'];

            // Loop through each task
            for ($i = 0; $i < count($task_names); $i++) {
                $task_name = $task_names[$i];
                $task_hour = $task_hours[$i];
                $task_id = $task_ids[$i];

                if(empty($task_id)) {
                    // Prepare and execute the query to insert new task
                    $stmt = $conn->prepare("INSERT INTO tasks (project_code, task_name, task_hours) VALUES (:project_code, :task_name, :task_hours)");
                    $stmt->bindParam(':project_code', $new_project_code);
                    $stmt->bindParam(':task_name', $task_name);
                    $stmt->bindParam(':task_hours', $task_hour);
                    $stmt->execute();
                } else {
                    // Prepare and execute the query to update existing task
                    $stmt = $conn->prepare("UPDATE tasks SET project_code = :project_code,task_name = :task_name, task_hours = :task_hours WHERE task_id = :task_id");
                    $stmt->bindParam(':project_code', $new_project_code);
					$stmt->bindParam(':task_name', $task_name);
                    $stmt->bindParam(':task_hours', $task_hour);
                    $stmt->bindParam(':task_id', $task_id);
                    $stmt->execute();
                }
            }
        }

        // Redirect to index.php after data update
        //header("location: index.php");
        //exit();
		echo "Successfully";
		}
    } elseif(isset($_POST['action']) && $_POST['action'] == 'add') {
    // Prepare and execute the query to add project details
    $project_name = $_POST['project_name'];
    $project_code = $_POST['project_code'];
    
    // Check if the project code already exists
    $stmt_check = $conn->prepare("SELECT COUNT(*) FROM projects WHERE project_code = :project_code");
    $stmt_check->bindParam(':project_code', $project_code);
    $stmt_check->execute();
    $count = $stmt_check->fetchColumn();

    if ($count > 0) {
        // Project code already exists, so display an error
        echo "Error: Project code already exists!";
    } else {
        // Project code doesn't exist, so insert the project details
        $stmt_insert_project = $conn->prepare("INSERT INTO projects (project_name, project_code) VALUES (:project_name, :project_code)");
        $stmt_insert_project->bindParam(':project_name', $project_name);
        $stmt_insert_project->bindParam(':project_code', $project_code);
        $stmt_insert_project->execute();

        // Get the last inserted project code
        $last_project_code = $project_code;

        // Insert new tasks if they are provided in the form
        if (isset($_POST['task_names']) && isset($_POST['task_hours'])) {
            $task_names = $_POST['task_names'];
            $task_hours = $_POST['task_hours'];
            
            // Loop through each task
            foreach ($task_names as $key => $task_name) {
                $task_hour = $task_hours[$key];
                
                // Prepare and execute the query to add each task
                $stmt_insert_task = $conn->prepare("INSERT INTO tasks (project_code, task_name, task_hours) VALUES (:project_code, :task_name, :task_hours)");
                $stmt_insert_task->bindParam(':project_code', $last_project_code);
                $stmt_insert_task->bindParam(':task_name', $task_name);
                $stmt_insert_task->bindParam(':task_hours', $task_hour);
                $stmt_insert_task->execute();
            }
        }

        // Data insertion successful
        echo "Successfully";
    }
}



}




?>
