<?php
include '../connection.php';

// Set up the API endpoint
$api_endpoint = 'https://example.com/api/carbon-dioxide';

// Loop forever and update the database every 2 minutes
while (true) {
    // Get the data from the API
    $response = file_get_contents($api_endpoint);

    // Check for errors
    if ($response === false) {
        echo 'Error: could not retrieve data from API';
    } else {
        $data = json_decode($response, true);

        // Update the record in the database
        $industry_id = $data['industry_id'];
        $concentration = $data['concentration'];
        $query = "UPDATE carbon_dioxide SET concentration = $concentration WHERE industry_id = $industry_id";
        $result = mysqli_query($conn, $query);

        // Check for any error
        if ($result === false) {
            echo 'Error: ' . mysqli_error($conn);
        } else {
            echo 'Record updated successfully';
        }
    }

    // Wait for 2 minutes before updating again
    sleep(120);
}

// Close the database connection
mysqli_close($conn);
?>
