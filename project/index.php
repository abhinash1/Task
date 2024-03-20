<?php
    // Include the connection file
    include 'connection.php';

    // Fetch data from projects table using prepared statement
    $stmt = $conn->prepare("SELECT * FROM projects");
    $stmt->execute();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manage Products</title>
<link rel="stylesheet" href="css/styles.css">
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

body{ font-family: "Poppins", sans-serif;font-size:13px;}
.search-container {
    position: relative;
    margin-bottom: 20px; /* Adjust as needed */
}

.search-input {
    width: 50%;
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 16px;
    outline: none;
}

.search-button {
    position: absolute;
	left:950%;
    
    top: 50%;
    transform: translateY(-50%);
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 5px;
    padding: 8px;
    cursor: pointer;
}

.search-button:hover {
    background-color: #0056b3;
}
.delete_btn{background:#C60000;color:#fff;border:none;}
.delete_btn:hover{background:#C60000;}
.edit_btn{background:#0000A8;color:#fff;border:none;}
.edit_btn:hover{background:#0000A8;}
.edit_btn a{text-decoration:none;color:#fff;}
.edit_btn:hover a{color:#fff;}
.search-input{float:right;height:30px;font-size:12px;}
.view_btn{background:#008000;color:#fff;border:none;}
.view_btn:hover{background:#008000;}
</style>
</head>
<body>
<div class="container">
  <h2>Manage Products</h2>
  <div class="button-container search-container"> <!-- Container for button -->
    <a href="addProject.php"><button onclick="addProject()" class="edit_btn">Add Project</button></a> <!-- Add Project button -->
  
    <input type="text" id="searchInput" class="search-input" placeholder="Search by Project Code or Project Name" onkeyup="searchProjects()">
    
</div>

  <table id="productTable">
    <thead>
      <tr>
        <th>Project Code</th>
        <th>Project Name</th>
        <th>Task</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
      <tr>
        <td><?php echo $row["project_code"]; ?></td>
        <td><?php echo $row["project_name"]; ?></td>
        <td class='task-name'><button class="view_btn" onclick='openModal("<?php echo $row["project_code"]; ?>")'>View Task</button></td>
        <td><button class="edit_btn"> <a href="editProject.php?project_code=<?php echo base64_encode($row["project_code"]); ?>">Edit Project</a></button>
          <button onclick='deleteProject("<?php echo $row["project_code"]; ?>", this.parentElement.parentElement)' class="delete_btn">Delete</button></td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>

<!-- Modal -->
<div id="taskModal" class="modal">
  <div class="modal-content"> <span class="close" onclick="closeModal()">&times;</span>
    <h2>Task Details</h2>
    <div id="taskDetails"> 
      <!-- Task details will be loaded here --> 
    </div>
  </div>
</div>
<script>
 // Function to open modal and populate task details
 async function openModal(projectCode) {
	 try {
		 const response = await fetch(`get_tasks.php?project_code=${projectCode}`);
		 
		 if (!response.ok) {
			 throw new Error('Failed to fetch task details');
		 }
		 
		 const responseData = await response.text();
         document.getElementById("taskDetails").innerHTML = responseData;
         document.getElementById('taskModal').style.display = 'block'; // Display modal
	 } catch (error) {
		 console.error('Error:', error);
	 }
 }

// Function to close modal
function closeModal() {
    document.getElementById('taskModal').style.display = 'none'; // Hide modal
}

// Function to search projects
function searchProjects() {
    const input = document.getElementById("searchInput");
    const filter = input.value.toUpperCase();
    const table = document.getElementById("productTable");
    const rows = table.getElementsByTagName("tr");
    for (let i = 0; i < rows.length; i++) {
        let tdProjectCode = rows[i].getElementsByTagName("td")[0];
        let tdProjectName = rows[i].getElementsByTagName("td")[1];
        if (tdProjectCode || tdProjectName) {
            let txtValueProjectCode = tdProjectCode.textContent || tdProjectCode.innerText;
            let txtValueProjectName = tdProjectName.textContent || tdProjectName.innerText;
            if (txtValueProjectCode.toUpperCase().indexOf(filter) > -1 || txtValueProjectName.toUpperCase().indexOf(filter) > -1) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    }
}


// Function to delete project using AJAX
function deleteProject(projectCode, row) {
    if (confirm('Are you sure you want to delete this project?')) {
        // AJAX request to delete_project.php
        fetch(`delete_task.php?delete_project=true&project_code=${projectCode}`, {
            method: 'GET'
        })
        .then(response => response.json())
        .then(data => {
            console.log(data); // Check response in console
            if (data.success) {
                // Remove the deleted row from the table
                row.remove();
                // Reload the page after deletion
                location.reload();
            } else {
                console.error('Failed to delete project:', data.message);
            }
        })
        .catch(error => {
            console.error('Error deleting project:', error);
        });
    }
}




</script>
</body>
</html>
