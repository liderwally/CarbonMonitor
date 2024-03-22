<!DOCTYPE html>
<html>
<head>
	<title>Carbon Dioxide Concentration</title>
	<style>
		body {
			font-family: Arial, sans-serif;
		}
		/* Style the top navigation bar */
		.topnav {
		  overflow: hidden;
		  background-color: #333;
		}
		/* Style the links inside the navigation bar */
		.topnav a {
		  float: left;
		  color: #f2f2f2;
		  text-align: center;
		  padding: 14px 16px;
		  text-decoration: none;
		  font-size: 17px;
		}
		/* Change the color of links on hover */
		.topnav a:hover {
		  background-color: #ddd;
		  color: black;
		}
		/* Style the "active" link */
		.topnav a.active {
		  background-color: #4CAF50;
		  color: white;
		}
	</style>
</head>
<body>
	<!-- Create the top navigation bar -->
	<div class="topnav" >
	  <a class="active" href="#">Home</a>
	  <a href="index.php">Dashboard</a>
	  <a href="industry-list.php">Industries</a>
	</div>
	<div id="container"></div>
</body>
	<!-- Include the Highcharts library and the JavaScript code for the polygon line graph -->
<script src="http://localhost/CO2-conc-dashboard/CO2-conc-dashboard/code.highcharts.com_highcharts.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4"></script>
<script src="http://localhost/CO2-conc-dashboard/CO2-conc-dashboard/code.highcharts.com_highcharts-more.js"></script>
<script src="http://localhost/CO2-conc-dashboard/CO2-conc-dashboard/code.highcharts.com_modules_exporting.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</html>