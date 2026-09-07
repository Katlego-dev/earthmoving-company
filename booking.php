<?php

require_once "db.php";

//Retrieve all customers from the database
$sql = "SELECT customer_code, name, city FROM customers ORDER BY customer_code";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Earthmoving Booking</title>
</head>
<body>
    
    <h1>Earthmoving Booking Form</h1>

    <form action="process_booking.php" method="POST">

        <label for="customer_code">Customer:</label>

        <select name="customer_code" id="customer_code" required>

            <option value="">-- Select Customer --</option>

            <?php 

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    ?>

                    <option value="<?php echo htmlspecialchars($row['customer_code']); ?>">
                        <?php
                        echo htmlspecialchars($row['customer_code'])
                        . " - "
                        . htmlspecialchars($row['name']);

                    ?>

                    </option>

                    <?php
                }
            } else {
                echo "<option value=''>No customers found</option>";
            }
            ?>

        </select>

        <br><br>

        <label for="distance">Distance (km):</label>

        <input 
            type="number"
            name="distance"
            id="distance"
            step="0.01"
            required
            >

            <br><br>

            <button type="submit">Calculate Project Cost</button>

</form>
</body>
</html>