<?php
include "connection.php";
 include "home.php"; 
 ?>

<!-- include Bootstrap CSS -->
<link rel="stylesheet" href="http://localhost/CO2-conc-dashboard/stackpath.bootstrapcdn.com_bootstrap_4.3.1_css_bootstrap.min.css">
<!-- header code goes here -->
<div class="container">
	<div class="row">
		<div class="col">
			<h1>Registered Industries</h1>
		</div>
		<div class="col text-right m-5">
			<a href="industry-registration.php" class="btn btn-primary">Add Industry</a>
		</div>
	</div>

<div class="row mt-3">
	<div class="col-md-6">
		<form method="get" action="industry-list.php">
			<div class="form-group">
				<label for="search">Search by Industry or Location</label>
				<input type="text" class="form-control" id="search" name="search" placeholder="Enter keywords">
			</div>
			<button type="submit" class="btn btn-primary">Search</button>
		</form>
	</div>
</div>

<div class="row mt-3">
	<?php
	// database connection code goes here
include './connection.php';


	// initialize search query
	$search_query = "";

	// check if search keyword is set
	if (isset($_GET['search'])) {
		$search = $_GET['search'];
		$search_query = "WHERE name LIKE '%$search%' OR location LIKE '%$search%'";
	}

	// query to get list of industries
	$query = "SELECT * FROM industry $search_query";
	$result = mysqli_query($conn, $query);

	// check if any results are found
	if (mysqli_num_rows($result) > 0) {
		// loop through each row in the result set
		while ($row = mysqli_fetch_assoc($result)) {
			// extract industry data from row
			$id = $row['id'];
			$name = $row['NAME'];
			$location = $row['location'];

			// display industry data and buttons in a card
			echo "<div class='col-md-4 mb-3'>";
			echo "<div class='card'>";
			echo "<div class='card-body'>";
			echo "<h5 class='card-title'>$name</h5>";
			echo "<h6 class='card-subtitle mb-2 text-muted'>$location</h6>";
			// echo "<div class - 'text-left'>";

			echo "<div class='text-right'>";
			echo "<a href='industry-dashboard.php?id=$id' class='btn btn-sm btn-primary'>View Dashboard</a> ";

			// echo "<a href='industry-editing.php?id=$id' class='btn btn-sm btn-primary'>Edit</a> ";
			echo "<a href='industry-delete.php?id=$id' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this industry?\")'>Delete</a>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
			echo "</div>";
		}
	} else {
		// display no results message
		echo "<div class='col'>";
		echo "<p>No industries found.</p>";
		echo "</div>";
	}

	// close database connection
	mysqli_close($conn);
	?>
</div>
</div>
<!-- include Bootstrap JS and jQuery -->
<script src="http://localhost/CO2-conc-dashboard/code.jquery.com_jquery-3.3.1.slim.min.js"></script>
<script src="http://localhost/CO2-conc-dashboard/cdnjs.cloudflare.com_ajax_libs_popper.js_1.14.7_umd_popper.min.js"></script>
<script src="http://localhost/CO2-conc-dashboard/stackpath.bootstrapcdn.com_bootstrap_4.3.1_js_bootstrap.min.js"></script>