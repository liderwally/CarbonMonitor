<?php
include 'home.php';
include 'connection.php';
//$_SESSION['id'] = $_GET['id'];
 define('DB_HOST', '127.0.0.1');
 define('DB_USERNAME', 'root');
 define('DB_PASSWORD', '');
 define('DB_NAME', 'co2-conc');

 $params= array();
 //get connection
$mysqli = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
$url_id = 5;
$alloweddatetime = array();
$readyTime = array();
$selected_date = "" ;
if(!$mysqli){
    die("connection failed: " . $myqli->error);
}





// Get the current URL
$currentUrl = $_SERVER['REQUEST_URI'];
//echo $currentUrl.'<br>';

//Extract the query string from the URL
$queryString = parse_url($currentUrl, PHP_URL_QUERY);
//echo $queryString.'<br>';

//Parse the query string and retrieve the 'id' parameter
parse_str($queryString, $params);


if(count($params) > 0){
    $url_id = $params['id'];

}
if(count($params) > 1){
  $selected_date = $params['selected_date'];
}

// if($_GET){
//   $id= $_GET['id'];

// }
//$thisUrl = implode('', [$currentUrl,'&']);
//echo $thisUrl;
// Output the extracted ID
// echo "ID: " . $id;
//query to get data from the table

$query = sprintf("SELECT DISTINCT id, concentration, recordedTime FROM carbon_dioxide WHERE industry_id = $url_id ORDER BY id");

//execute query
$result = $mysqli->query($query);
$id = array();
$concentration = array();
$extractedMinutes = array();
$timestamps = array();
$thedaydata = array();
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
    $dateTime = new DateTime($timestamp);
    $ourtimes = $dateTime ->format('Y-m-d H:i:s');
        if(startsWith( $ourtimes, $selected_date)){
      array_push($alloweddatetime,$ourtimes);
      array_push($readyTime , datetimetohours($ourtimes));
      $data = getdata($conn, $url_id, $ourtimes);
      //echo '<br>'.$data.'<br>';
      array_push( $thedaydata , $data);

  }


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

//////////////////////////////////////////////////////////////////
// $mysqli->close();

// foreach( $readyTime as $time ){
//   echo $time.' ';

// }
// echo '<br>';
// foreach( $thedaydata as $time ){
//   echo $time.' ';

// }

////////////////////////////////////////////////////////////////
?>  

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChartJs - BarGraph</title>
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	
	<link rel="stylesheet" href="css/style.css">

    
    <style>
        .chartbox {
            width: 700px;
        }
    </style>
</head>
<body>
<div class="container">
    <!-- <h2>Date Selection Form</h2> -->
    <form >
      <div class="form-group">
        <label for="selected_date">Select Date:<?php echo $selected_date;?></label>
        <input type="date" class="form-control" id="selected_date" name="selected_dates">
      </div>
      <div type="" class="btn btn-primary" onclick="redirect()"> Submit</div>
    </form>
  </div>


  <div class="row">
    <div class="col-md-6  ml-5">


      <div class="chartbox">
          <canvas id="myChart"></canvas>
      </div>
      
      <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
      <script>
        console.log(<?php echo json_encode($concentration); ?>)

        // setup
        const concentration = <?php echo json_encode($thedaydata); ?>;
        const id = <?php echo json_encode($id); ?>;
        const minute = <?php echo json_encode($readyTime); ?>;
        console.log(minute)
        const data = {
        labels: minute,
            datasets: [{
              label: 'Carbon dioxide concentration',
              data: concentration,
              borderWidth: 1
            }]
        };

        // config
        const config = {
        type: 'bar',
        data: data,
        options: {
          scales: {
            x: {
              title: {
                display: true,
                text: 'Hours' // X-axis label
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
        function redirect(){
          let date = document.querySelector('#selected_date').value;
          //alert('reaching here!');
          window.location.href = "<?php echo $currentUrl; ?>&selected_date=" + date ;
        }

      </script>

    </div>
    <!-- <div class="col-md-6 p-5" > -->


</body>
</html>





<?php 

 function startsWith( $word , $word2){
  $len = strlen( $word2);
   return (substr($word ,0, $len) === $word2);
 }

function getdata($conn, $id, $datetime){
  //echo "SELECT concentration FROM `carbon_dioxide` WHERE industry_id = $id AND recordedTime='$datetime'";
  $result = mysqli_query($conn, "SELECT concentration FROM `carbon_dioxide` WHERE industry_id = $id AND recordedTime='$datetime'" );
  if($result){
    $rows = mysqli_fetch_assoc($result);
    return $rows['concentration'];
  }
  return 0;
};

function datetimetohours($datetime){
 $time = explode(' ', $datetime )[1];
 $separatedTime = explode(':', $time);
 $value =  (float)$separatedTime[0] + (float)$separatedTime[1] /60 + (float)$separatedTime[2]/ 3600;
//echo $datetime.'->'.$value;
return $value;


}
?>