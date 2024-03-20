<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Even/Odd Checker</title>
</head>
<body>
    <h2>Even/Odd Number Checker</h2>
    <form method="post">
        <label for="number">Enter a number:</label>
        <input type="number" id="number" name="number" required>
        <button type="submit" name="submit">Check</button>
    </form>

    <?php
    // Check if form is submitted
    if(isset($_POST['submit'])) {
        // Validate input
        if(isset($_POST['number'])) {
            $number = $_POST['number'];
            // Check if number is numeric
            if(is_numeric($number)) {
                // Check if number is even or odd
                if($number % 2 == 0) {
                    echo "<p>$number is even.</p>";
                } else {
                    echo "<p>$number is odd.</p>";
                }
            } else {
                echo "<p>Please enter a valid number.</p>";
            }
        }
    }
    ?>
</body>
</html>