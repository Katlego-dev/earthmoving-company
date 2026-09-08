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

    //---------------------
    // COST CALCULATIONS
    //---------------------

    //Distance charge: R45 per kilometre
    $distance_charge = $distance * 45;

    //Labour cost: 5% of the distance charge
    $labour_cost = $distance_charge * 0.05;

    //Work time: 4.5 hours per kilometre
    $work_hours = $distance * 4.5;

    //Hourly fee: R1250 per hour
    $hourly_cost = $work_hours * 1250;

    //Total cost before VAT
    $subtotal = $distance_charge + $labour_cost + $hourly_cost;

    //VAT: 15%
    $vat = $subtotal * 0.15;

    //Final project cost
    $total_cost = $subtotal + $vat;

    //Display Project cost breakdown

    echo "<h1>Project Cost Breakdown</h1>";

    echo "<h3>Customer Details</h3>";

    echo "<p><strong>Customer Code:</strong> "
          . htmlspecialchars($customer["customer_code"])
          . "</p>";
    
    echo "<p><strong>Customer Name:</strong> "
          . htmlspecialchars($customer["name"])
          . "</p>";

    echo "<p><strong>City:</strong> "
          . htmlspecialchars($customer["city"])
          . "</p>";

    echo "<p><strong>Distance:</strong> "
          . number_format($distance, 2)
          . "</p>";

    echo "<h3>Cost Details</h3>";

    echo "<p><strong>Distance Charge:</strong> R"
         . number_format($distance_charge, 2)
         . "</p>";

    echo "<p><strong>Labour Cost:</strong> R"
         . number_format($labour_cost, 2)
         . "</p>";

    echo "<p><strong>Total Work Time:</strong> R"
         . number_format($work_hours, 2)
         . "</p>";

    echo "<p><strong>Hourly Cost:</strong> R"
         . number_format($hourly_cost, 2)
         . "</p>";

    echo "<p><strong>Total Before VAT:</strong> R"
         . number_format($subtotal, 2)
         . "</p>";

    echo "<p><strong>VAT (15%):</strong> R"
         . number_format($vat, 2)
         . "</p>";

    echo "<h3>Total Project Cost: R"
         . number_format($total_cost, 2)
         . "</h3>";

    

    $stmt->close();
    $conn->close();
    ?>