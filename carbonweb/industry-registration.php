<?php
include "home.php";
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
	// Validate the input data
	$name = trim($_POST["name"]);
	$location = trim($_POST["location"]);
	
	if (empty($name)) {
		$errors[] = "Industry name is required";
	}
	
	if (empty($location)) {
		$errors[] = "Industry location is required";
	}
	
	// If there are no validation errors, insert the data into the database
	if (empty($errors)) {
		
		include './connection.php';
		
		// Prepare the SQL query
		$sql = "INSERT INTO industry (name, location) VALUES ('$name', '$location')";
		
		// Execute the query
		if (mysqli_query($conn, $sql)) {
			// Redirect to a success page
			header("Location: industry-list.php");
			exit();
		} else {
			// Display an error message
			$errors[] = "Error: " . mysqli_error($conn);
		}
		
		// Close the database connection
		mysqli_close($conn);
	}
}

?>



<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Industry Registration Form</title>
	<style>
		body {
			font-family: Arial, sans-serif;
		}
		h1 {
			font-size: 32px;
			margin-top: 50px;
			margin-bottom: 20px;
			text-align: center;
		}
		form {
			margin: auto;
			width: 50%;
		}
		label {
			display: block;
			font-size: 20px;
			margin-top: 20px;
		}
		input[type="text"], select {
			font-size: 16px;
			padding: 10px;
			width: 100%;
		}
		input[type="submit"] {
			background-color: #334294;
			border: none;
			color: white;
			cursor: pointer;
			font-size: 16px;
			margin-top: 20px;
			padding: 10px;
			width: 100%;
		}
		input[type="submit"]:hover {
			background-color: #45a049;
		}
	</style>
</head>
<body>
	<h1>Industry Registration Form</h1>
	<form method="post" action="industry-registration.php">
		<label for="name">Industry Name</label>
		<input type="text" id="name" name="name" required>
		<label for="location">Location</label>
		<input type="text" id="location" name="location" required>
		<input type="submit"  value="Register Industry">
	</form>
</body>
</html>
