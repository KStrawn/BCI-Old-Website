<?php

	// Set the page title and include the HTML header:
	$page_title = 'View Users';
	$page_section = 'View Users';

	require_once ('includes/config.inc.php'); 
	include ('includes/header.html');
?>

<h1> Users </h1>

<table id="table">
	<?php // PHP to create the table of users
	
		$query = "SELECT first_name, last_name, user_level FROM users";
		$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
		
		// Makes sure there are staff to select
		if ($results->num_rows == 0) {
			echo '<tr><strong> There are no users in the table! </strong></tr>';
		} else {
			echo '<tr><th>First Name</th><th>Last Name</th><th>User Level</th></tr>';
			// Fetch and print all the records:
			while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				echo '<tr><td>' . $row['first_name'] . '</td><td>' . $row['last_name'] . '</td><td>' . $row['user_level'] . '</td></tr>' ;
			}
		}
	?>
</table>
	
<?php include ('includes/footer.html'); ?>