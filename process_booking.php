<?php

    require_once "db.php";

    //Check that the form was submitted
    if ($_SERVER["REQUEST_METHOD"] !== "POST") {
        die("Invalid request.");
    }

    //Get the submitted values
    $customer_code = $_POST["customer_code"] ?? "";
    $distance = $_POST["distance"] ?? "";

   //Validate customer code
   if (empty($customer_code)) {
    die("Error: Please select a customer");
   }

   //Validate distance
   if (empty($distance) || !is_numeric($distance) || $distance <= 0) {
    die("Error: Please enter a valid roadwork distance");

   }

   //Convert distance to a number
   $distance = (float) $distance;

   //Prepare a secure SQL statement
   $sql = "SELECT customer_code, name, city
           FROM customers
           WHERE customer_code = ?";

           $stmt = $conn->prepare($sql);

           if(!$stmt) {
            die("Error preparing database query.");
           }

    //Bind the customer code to the query
    $stmt->bind_param("s", $customer_code);

    //Execute the query
    if (!$stmt->execute()) {
        die("Error retrieving customer information.");
    }


    //Get the result
    $result = $stmt->get_result();

    //Check if customer exists
    if ($result->num_rows === 0) {
        die("Error: Customer not found.");
    }

    //Retrieve customer information
    $customer = $result->fetch_assoc();

    //Display customer information
    echo "<h1>Customer Information</h1>";

    echo "Customer Code: " . htmlspecialchars($customer["customer_code"]) . "<br>";
    echo "Customer Name: " . htmlspecialchars($customer["name"]) . "<br>";
    echo "City: " . htmlspecialchars($customer["city"]) . "<br>";
    echo "Distance: " . htmlspecialchars($distance) . " km<br>";

    $stmt->close();
    $conn->close();
    ?>