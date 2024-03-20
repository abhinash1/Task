<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Edit Project</title>
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
        input[type="number"] {
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
        .task-form input[type="number"] {
            margin-bottom: 10px;
            width: calc(70% - 22px); /* Adjusted width */
            display: inline-block; /* Adjusted display */
        }
        .task-form label {
            font-weight: normal;
            display: inline-block; /* Adjusted display */
            width: 30%; /* Adjusted width */
            margin-bottom: 0; /* Adjusted margin */
        }
        .delete-btn {
            background-color: #dc3545;
            color: #fff;
            border: none;
            border-radius: 4px;
            padding: 5px 10px;
            cursor: pointer;
            
        }
		#ErrorMsg{font-size:14px;margin-bottom:10px;color:#F00;}
    </style>
     <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <div class="container">
        <h2>Add/Edit Project</h2>
        <form action="" method="post" name="ProjectFrm">
            <input type="hidden" name="action" value="add">
            <label for="project_code">Project Code:</label>
            <input type="text" id="project_code" name="project_code" required><br>
            <label for="project_name">Project Name:</label>
            <input type="text" id="project_name" name="project_name" required><br>
            <div id="taskForm">
                <div class="task-form">
                    <label for="task_name">Task Name:</label>
                    <input type="text" class="task_name" name="task_names[]" required>
                    <label for="task_hours">Task Hours:</label>
                    <input type="number" class="task_hours" name="task_hours[]" required>
                    <button type="button" class="delete-btn" onclick="deleteTask(this)">Delete</button>
                </div>
            </div>
            <div id="ErrorMsg"></div>
            <button type="button" onclick="addTask()">Add Task</button>
            <button type="button" onClick="add_project_fnc()">Submit</button>
        </form>
    </div>

    <script>
        function addTask() {
            var taskForm = document.createElement('div');
            taskForm.classList.add('task-form');
            taskForm.innerHTML = `
                <label for="task_name">Task Name:</label>
                <input type="text" class="task_name" name="task_names[]" required>
                <label for="task_hours">Task Hours:</label>
                <input type="number" class="task_hours" name="task_hours[]" required>
                <button type="button" class="delete-btn" onclick="deleteTask(this)">Delete</button>
            `;
            document.getElementById('taskForm').appendChild(taskForm);
        }

        function deleteTask(button) {
            const taskForm = button.parentElement;
            taskForm.remove();
        }
		
		
 function add_project_fnc() {
    var form = document.ProjectFrm;
    
    // Check if any required fields are empty
    var isValid = true;
    $(form).find('input[required]').each(function() {
        if ($(this).val() === '') {
            isValid = false;
            return false; // Exit the loop early if any required field is empty
        }
    });

    if (!isValid) {
		document.querySelector("#ErrorMsg").innerHTML='Please fill in all required fields.';
        //alert('Please fill in all required fields.');
        return false;
    }

    // If all required fields are filled, proceed with AJAX submission
    var dataString = $(form).serialize();

    $.ajax({
        type: 'POST',
        url: 'add_edit_action.php',
        data: dataString,
        success: function(data) {
            if (data.trim() === 'Successfully') {
                alert("Successfully");
                window.location.href = "index.php";
            } else {
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
