<?php


$ourtime = new DateTime('now' , new DateTimeZone('Africa/Nairobi'));
$ourtimes = $ourtime ->format('Y-m-d H:i:s');
echo $ourtimes;
$host = "localhost:3306";
$dbname = "CO2-conc";
$username = "root";
$password = "";

$conn = mysqli_connect($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: ".$conn->connect_error);
}

// Set up the API endpoint
$api_endpoint = '/opt/lampp/htdocs/CO2-conc-dashboard/CO2-industry-device-API/data-from-industry.php';
$industry_id =0; $lat = 0; $long = 0; $conc = 0; $state = 0;

// Set up the loop to post data every two minutes

if($_GET["data"]){
$data =  $_GET["data"];
// indId<lat<long<conc<state
$datas = explode("<", $data);
$industry_id = (int)$datas[0];
$lat = (float)$datas[1];
$long = (float)$datas[2];
$conc = (int)$datas[4];
$state= (int)$datas[3];
//$NAME = (String)$datas[5];
}




if($data) {
    // Get the data from the API
    // if(file_get_contents($api_endpoint)){
    // $response = file_get_contents($api_endpoint);
    // }

    // Check for errors
    // if ($response === false) {
    //     echo 'Error: could not retrieve data from API';
    // } else {
    //     $deta = json_decode($response, true);

        // Insert the data into the database
        // $industry_id = $data['industry_id'];
        // $concentration = $data['concentration'];
        $minutesformated = $ourtime -> format('i');     
        $dayformated = $ourtime -> format('d');
        $hoursformated = $ourtime -> format('H');
        $query = "INSERT INTO carbon_dioxide (industry_id,concentration,recordedTime) VALUES ( (SELECT id FROM `industry` WHERE id = $industry_id ),$conc, '$ourtimes')";
        $result = mysqli_query($conn, $query);
        // Check for errors
        if ($result === false) {
            echo 'Error: ' . mysqli_error($conn);
        } 
        else {
            echo 'Data inserted successfully';
        }
    

    // Wait for two minutes before posting again
    
}

// Close the database connection
mysqli_close($conn);
?>
