<?php

	// Set the page title and include the HTML header:
	$page_title = 'Conference Finder';
	$page_section = 'root';
	
	require_once ('includes/config.inc.php'); 
	include ('includes/header.html');
?>


<link type="text/css" href="css/jquery-ui-1.8.18.custom.css" rel="stylesheet" />
<script type="text/javascript" src="includes/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="includes/jquery-ui-1.8.18.custom.min.js"></script>

<script type="text/javascript">
	$(function(){
		
		var availableTags = [
			
			<?php // PHP to create the array of child names
	
				$query = "SELECT ChildsName FROM student";
				$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
				
				// Makes sure there are child names selected
				if ($results->num_rows == 0) {
					echo '<tr><strong> There are no staff in the table! </strong></tr>';
				} else {
					// Fetch and print all the records:
					while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
						echo '"' . $row['ChildsName'] . '",';
					}
				}
			?>
			
			
		];
		
		$( "#tags" ).autocomplete({
			source: availableTags
		});
	});
</script>
	

<h1> Look Up Student </h1>

<div class="ui-widget">
	
	<form enctype="multipart/form-data" action="student_profile.php" method="post">
		<label for="tags">Tags: </label>
		<input id="tags" name="name" />
		<label>&nbsp; </label> <input type="submit" value="Go!" name="send" class="button"/>
	</form>
</div>

<div class="ui-widget">
	<label for="test">Tags: </label>
	<input id="test" />
</div>



<table id="staff">
	<?php // PHP to create the table of registered conferences from the database
	
		$query = "
		SELECT ChildID, ChildsName, Age 
		FROM student";
		$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
		
		// Makes sure there are staff to select
		if ($results->num_rows == 0) {
			echo '<tr><strong> There are no staff in the table! </strong></tr>';
		} else {
			echo '<tr><th width="20">ChildID</th><th width="40%">ChildsName</th><th width="40%">Age</th></tr>';
			// Fetch and print all the records:
			while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				echo '<tr><td>' . $row['ChildID'] . '</td><td>' . $row['ChildsName'] . '</td><td>' . $row['Age'];
			}
		}
	?>
</table>
	
<?php include ('includes/footer.html'); ?>




