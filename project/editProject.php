<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Project</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        form {
            margin-top: 20px;
            padding: 20px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        label {
            display: block;
            margin-bottom: 10px;
            color: #333;
            font-weight: bold;
        }
        input[type="text"],
        input[type="number"] { /* Adjusted */
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            padding: 10px 20px;
            margin-right: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #0056b3;
        }
        .task-form {
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #f0f0f0;
            margin-bottom: 10px;
        }
        .task-form input[type="text"],
        .task-form input[type="number"] { /* Adjusted */
            margin-bottom: 10px;
            width: calc(100% - 22px);
        }
        .task-form label {
            font-weight: normal;
        }
        .delete-btn {
            background-color: #dc3545;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            cursor: pointer;
            
        }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="container">
        <h2>Edit Project</h2>
        <?php
            include 'connection.php';
            if(isset($_GET['project_code'])) {
                $project_code = base64_decode($_GET['project_code']);
                $stmt = $conn->prepare("SELECT * FROM projects WHERE project_code = :project_code");
                $stmt->bindParam(':project_code', $project_code);
                $stmt->execute();
                $project = $stmt->fetch(PDO::FETCH_ASSOC);
            }
        ?>
        <form id="editProjectForm" action="" method="post" name="ProjectFrm">
            <input type="hidden" name="project_code" value="<?php echo $project['project_code']; ?>">
            <input type="hidden" name="action" value="edit">
            <label for="project_name">Project Name:</label>
            <input type="text" id="project_name" name="project_name" value="<?php echo $project['project_name']; ?>" required><br>
            <label for="new_project_code">New Project Code:</label>
            <input type="text" id="new_project_code" name="new_project_code" value="<?php echo $project['project_code']; ?>" required><br>
            <div id="taskForm">
                <?php
                    $stmt = $conn->prepare("SELECT * FROM tasks WHERE project_code = :project_code");
                    $stmt->bindParam(':project_code', $project_code);
                    $stmt->execute();
                    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach($tasks as $task) {
                        echo '<div class="task-form">';
                        echo '<label for="task_name">Task Name:</label>';
                        echo '<input type="text" class="task_name" name="task_names[]" value="'.$task['task_name'].'" required><br>';
                        echo '<label for="task_hours">Task Hours:</label>';
                        echo '<input type="number" class="task_hours" name="task_hours[]" value="'.$task['task_hours'].'" required><br>';
                        echo '<input type="hidden" name="task_ids[]" value="'.$task['task_id'].'">'; // Hidden input for task IDs
                        echo '<button type="button" class="delete-btn" onclick="deleteTask(this)">Delete</button>'; // Delete button
                        echo '</div>';
                    }
                ?>
            </div>
            <div id="ErrorMsg"></div>
            <button type="button" onclick="addTask()">Add Task</button>
            <button type="button" onClick="edit_project_fnc()">Submit</button>
        </form>
    </div>

    <script>
        function addTask() {
            var taskForm = document.createElement('div');
            taskForm.classList.add('task-form');
            taskForm.innerHTML = `
                <label for="task_name">Task Name:</label>
                <input type="text" class="task_name" name="task_names[]" required><br>
                <label for="task_hours">Task Hours:</label>
                <input type="number" class="task_hours" name="task_hours[]" required><br>
                <button type="button" class="delete-btn" onclick="deleteTask(this)">Delete</button>
            `;
            document.getElementById('taskForm').appendChild(taskForm);
        }

   async function deleteTask(button) {
    const taskForm = button.parentElement;
    const taskIDInput = taskForm.querySelector('input[name="task_ids[]"]');
    if (taskIDInput.value !== '') {
        // If task ID exists, ask for confirmation
        if (confirm('Are you sure you want to delete this task?')) {
            const taskID = taskIDInput.value;
            try {
                const response = await fetch(`delete_task.php?task_id=${taskID}`, {
                    method: 'GET'
                });
                if (response.ok) {
                    // Remove task from DOM
                    taskForm.remove();
                } else {
                    console.error('Failed to delete task from database');
                }
            } catch (error) {
                console.error('Error deleting task:', error);
            }
        }
    } else {
        // If task ID doesn't exist, simply remove task from DOM
        taskForm.remove();
    }
}



function edit_project_fnc(){
	var form = document.ProjectFrm;
	var dataString = $(form).serialize();
	
	$.ajax({
		type:'POST',
		url:'add_edit_action.php',
		data: dataString,
		
		success: function(data){
			html= data.trim();
			
			if(html=='Successfully'){
				alert("Successfully");
				 window.location.href = "index.php";
			}else{
				$('#ErrorMsg').html(data);
				$('#hidden_value').val(data);
			}
		}
	});
	return false;
}
    </script>
</body>
</html>
