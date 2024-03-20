<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sum of Digits</title>
</head>
<body>
    <h2>Enter a positive integer to find the sum of its digits:</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="number">Enter a number:</label>
        <input type="number" id="number" name="number" required>
        <button type="submit">Calculate Sum</button>
    </form>
    <br>

    <?php
    function sum_of_digits($n) {
        // Initialize sum
        $digit_sum = 0;
        
        // Iterate through each digit of the number
        while ($n > 0) {
            // Get the last digit of the number
            $digit = $n % 10;
            
            // Add the digit to the sum
            $digit_sum += $digit;
            
            // Remove the last digit from the number
            $n = (int)($n / 10);
        }
        
        return $digit_sum;
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $num = $_POST["number"];
        if ($num < 0) {
            echo "<p>Please enter a positive integer.</p>";
        } else {
            echo "<p>Sum of digits: " . sum_of_digits($num) . "</p>";
        }
    }
    ?>
</body>
</html>
