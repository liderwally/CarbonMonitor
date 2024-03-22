<?php
include 'home.php';
include 'connection.php';
session_start();
$_SESSION['id']= 5;

 define('DB_HOST', '127.0.0.1');
 define('DB_USERNAME', 'root');
 define('DB_PASSWORD', '');
 define('DB_NAME', 'co2-conc');

 //get connection
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if(!$mysqli){
    die("connection failed: " . $mysqli->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChartJs - BarGraph</title>
    
    <style>
        .chartbox {
            width: 700px;
        }
    </style>
</head>
<body>
<?php



$query = sprintf("SELECT DISTINCT id, concentration, recordedTime FROM carbon_dioxide");

//execute query
$result = $mysqli->query($query);
$id = array();
$concentration = array();
$extractedMinutes = array();
$timestamps = array();
if ($result) {
  // Fetch the result as an associative array
  $data = $result->fetch_all(MYSQLI_ASSOC);
  
  // Access the "id" and "concentration" values from the array
  foreach ($data as $row) {
      $id[] = $row['id'];
      $concentration[] = $row['concentration'];
      $timestamps[] = $row['recordedTime'];
  }

  foreach ($timestamps as $timestamp) {
    // Create a DateTime object using the current timestamp
    // $ourtimes = $timestamp ->format('Y-m-d H:i:s');
    $dateTime = new DateTime($timestamp);

    // Extract the minute from the DateTime object
    $minute = $dateTime->format('i');

     // Append the extracted minute to the array
    $extractedMinutes[] = $minute;
} 
  // Free the result set
  $result->free();
} else {
  // Handle the query error
  echo "Error executing the query: " . $mysqli->error;
} 
// Close the database connection
$mysqli->close();

?>  


      <div class="chartbox">
          <canvas id="myChart"></canvas>
      </div>
      
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <script>
        console.log(<?php echo json_encode($concentration); ?>)

        // setup
        const concentration = <?php echo json_encode($concentration); ?>;
        const id = <?php echo json_encode($id); ?>;
        const minute = <?php echo json_encode($extractedMinutes); ?>;
        console.log(minute)
        const data = {
        labels: minute,
            datasets: [{
              label: 'Carbon dioxide concentration',
              data: concentration,
              borderWidth: 1
            }]
        };
1        // config
        const config = {
        type: 'bar',
        data: data,
        options: {
          scales: {
            x: {
              title: {
                display: true,
                text: 'Minute' // X-axis label
              },
              type: 'linear', // Set the x-axis scale type to 'linear'
              beginAtZero: true
            },
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'Concentration' // Y-axis label
              }
            }
          }
        }
        };

        // render
        const myChart = new Chart(
            document.getElementById('myChart'),
            config
        );
      </script>
</body>
</html>