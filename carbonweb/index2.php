<?php
// Assuming you have a database connection established
include "connection.php";
//echo time();
// Get the selected date from the calendar
$selectedDate = $_GET['selected_date']; // Assuming you're using POST method to send the selected date
$alloweddatetime = array();

// Query the database to retrieve data for the selected date
$query = "SELECT * FROM carbon_dioxide WHERE 1";
$result = mysqli_query($conn, $query);

// Check if the query was successful and if there is a matching row
while($result && mysqli_num_rows($result) > 0) {
    // Fetch the data from the result

    $rows = mysqli_fetch_assoc($result);

    echo  mysqli_num_rows($result);
   // $idrow =  count($rows);
    //echo $idrow.'<br>';
    //echo ($rows['recordedTime']);

    if(startsWith( $rows['recordedTime'], $selectedDate)){
        echo ' TRUE <br>';
        array_push($alloweddatetime,$rows['recordedTime']);
    }
    // else{
    //     echo '<br>';
    // }



} 

echo count($alloweddatetime);  

// Close the database connection
mysqli_close($conn);



function startsWith( $word , $word2){
  $len = strlen( $word2);
  return (substr($word ,0, $len) === $word2);

}

?>
