<?php
// check if the id parameter is set
if (isset($_GET['id'])) {
	// get the id parameter
	$id = $_GET['id'];

	// database connection code goes here
    include './connection.php';
	// query to delete industry from database
	$query = "DELETE FROM industry WHERE id = $id";
	$result = mysqli_query($conn, $query);

	// check if delete was successful
	if ($result) {
		// redirect to listing page
		header("Location: industry-list.php");
		exit;
	} else {
		// display error message
		echo "Error deleting industry. Please try again.";
	}

	// close database connection
	mysqli_close($conn);
}
?>
