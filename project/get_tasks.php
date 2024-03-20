<?php
    // Include the connection file
    include 'connection.php';

    // Check if project code is provided in the request
    if(isset($_GET['project_code'])) {
        $projectCode = $_GET['project_code'];
        
        // Fetch task details from tasks table based on project code
        $stmt = $conn->prepare("SELECT * FROM tasks WHERE project_code = ?");
        $stmt->execute([$projectCode]);

        // Output data of each row
        echo "<table>";
        echo "<tr><th>Task Name</th><th>Task Hours</th></tr>";
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>".$row["task_name"]."</td>";
            echo "<td>".$row["task_hours"]." Hour</td>";
            echo "</tr>";
        }
        echo "</table>";

        // Close connection
        $conn = null;
    }
?>
