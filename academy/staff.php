<?php

	// Set the page title and include the HTML header:
	$page_title = 'Staff';
	$page_section = 'Staff';

	require_once ('includes/config.inc.php'); 
	include ('includes/header.html');
?>

<h1> Staff Table </h1>

<table id="staff">
	<?php // PHP to create the table of registered conferences from the database
	
		$query = "
		SELECT staff_id, staff_name 
		FROM staff";
		$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
		
		// Makes sure there are staff to select
		if ($results->num_rows == 0) {
			echo '<tr><strong> There are no staff in the table! </strong></tr>';
		} else {
			echo '<tr><th width="20">staff_id</th><th width="40%">name</th><th width="40%">Blank</th></tr>';
			// Fetch and print all the records:
			while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				echo '<tr><td>' . $row['staff_id'] . '</td><td>' . $row['staff_name'] . '</td><td></td></tr>' ;
			}
		}
	?>
</table>
	
<?php include ('includes/footer.html'); ?>