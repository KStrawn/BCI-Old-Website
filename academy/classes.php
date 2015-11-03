<?php
// This is the main page for the site.

// Include the configuration file:
require_once ('includes/config.inc.php'); 

// Set the page title and include the HTML header:
$page_title = 'Staff Table';
include ('includes/header.html');

require_once ('includes/mysqli_connect.php'); //Connect to the DB
?>

<h1> Staff Table </h1>

<table id="staff">
	<?php // PHP to create the table of registered conferences from the database
	
		$query = "
		SELECT staff_id, first_name, last_name 
		FROM staff";
		$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
		
		// Makes sure there are staff to select
		if ($results->num_rows == 0) {
			echo '<tr><strong> There are no staff in the table! </strong></tr>';
		} else {
			echo '<tr><th width="20">staff_id</th><th width="40%">first_name</th><th width="40%">last_name</th></tr>';
			// Fetch and print all the records:
			while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				echo '<tr><td>' . $row['staff_id'] . '</td><td>' . $row['first_name'] . '</td><td>' . $row['last_name'];
			}
		}
	?>
</table>
	
</body>
</html>