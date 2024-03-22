<?php
include 'home.php';
include 'connection.php';

$id  = 1;
$name = "";
$location = "";

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
	
		// Validate the input data
		// $id = trim($_POST["id"]);
		// Get the current URL
	$currentUrl = $_SERVER['REQUEST_URI'];

	// Extract the query string from the URL
	$queryString = parse_url($currentUrl, PHP_URL_QUERY);

	// Parse the query string and retrieve the 'id' parameter
	parse_str($queryString, $params);

	// Extract the 'id' value
	$url_id = $params['id'];

	$name = trim($_POST["name"]);
	$location = trim($_POST["location"]);
	
	if (empty($name)) {
		$errors[] = "Industry name is required";
	}
	
	if (empty($location)) {
		$errors[] = "Industry location is required";
	}
	
	// If there are no validation errors, update the data in the database
	if (empty($errors)) {
		include 'connection.php';
		}
		
		// Prepare the SQL query
		// $sql = "UPDATE industry SET NAME='.$name.', location='.$location.' WHERE id='$url_id'";
		$sql = "UPDATE industry SET NAME='$name', location='$location' WHERE id='$url_id'";

		// Execute the query
		if(mysqli_query($conn, $sql)) {
			// Redirect to a success page
			header("Location:industry-list.php");
			exit();
		} else {
			// Display an error message
			$errors[] = "Error: " . mysqli_error($conn);
		}
		
		// Close the database connection
		mysqli_close($conn);
	}


?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Edit Industry</title>
	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="http://localhost/CO2-conc-dashboard/stackpath.bootstrapcdn.com_bootstrap_4.3.1_cssbootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>
<body>
	<div class="container mt-4">
		<div class="row">
			<div class="col-md-6 offset-md-3">
				<h1>Edit Industry</h1>
				<form action="industry-editing.php" method="POST">
					<input type="hidden" name="id" value="<?php echo $id; ?>">
					<div class="form-group">
						<label for="name">Name</label>
						<input type="text" class="form-control" id="name" name="name" value="<?php echo $name; ?>">
					</div>
					<div class="form-group">
						<label for="location">Location</label>
						<input type="text" class="form-control" id="location" name="location" value="<?php echo $location; ?>">
					</div>
					<button type="submit" class="btn btn-primary">Save Changes</button>
				</form>
			</div>
		</div>
	</div>


</body>

	<!-- Bootstrap JS -->
	<script src="http://localhost/CO2-conc-dashboard/code.jquery.com_jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="http://localhost/CO2-conc-dashboard/cdn.jsdelivr.net_npm_@popperjs_core@2.9.3_dist_umd_popper-base.min.js"></script>
	<script src="http://localhost/CO2-conc-dashboard/stackpath.bootstrapcdn.com_bootstrap_4.3.1_js_bootstrap.min.js"></script>
</html>