<?php

	// Set the page title and include the HTML header:
	$page_title = 'Create Class';
	$page_section = 'Classes';
	
	require_once ('includes/config.inc.php'); 
	include ('includes/header.html');
?>

<style type="text/css">

.input:hover {
    background: #F5F5F5;
}
label {
    display: block;
    width: 150px;
    float: left;
    margin: 2px 4px 6px 4px;
    text-align: right;
}
br {
	clear: left; 
}
hr {
	background-color: black;
	margin: 30px;
}

/* For custom button images:
.button {
    border: none;
    background: url('/forms/up.png') no-repeat top left;
    padding: 2px 8px;
}
.button:hover {
    border: none;
    background: url('/forms/down.png') no-repeat top left;
    padding: 2px 8px;
}
*/
</style>

<div id="cc">

<?php
if (isset($_POST['submit'])) {
	
	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);
	
	// Assume invalid values:
	$id = $name = $description = $grade = $prereqs = FALSE;
	
	// Set the variables with the incoming data
	$id = intval(mysqli_real_escape_string ($dbc, $trimmed['id']));
	$name = mysqli_real_escape_string ($dbc, $trimmed['name']);
	$description = mysqli_real_escape_string ($dbc, $trimmed['description']);
	$grade = mysqli_real_escape_string ($dbc, $trimmed['grade']);
	$prereqs = mysqli_real_escape_string ($dbc, $trimmed['prereqs']);
	
	// Insert the new data into the database
	$query = "INSERT INTO student_classes(class_id, name, description, grade, prerequisites) VALUES($id, '$name', '$description', '$grade', '$prereqs')";
	$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
	
	if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
		
		echo '<p> Your class has been added to the database </p>';
		
	} else { // If it did not run OK.
		echo '<p class="error">The class could not be registered due to a system error. We apologize for any inconvenience.</p>';
	}
	
}

?>

<h1> Classes </h1>


<?php // PHP to create the table of registered conferences from the database

	$query = "
	SELECT class_id, name, description, grade, prerequisites 
	FROM student_classes";
	$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
	
	// Makes sure there are staff to select
	if ($results->num_rows == 0) {
		echo '<strong> There are no classes in the database! </strong>';
	} else {
		echo '<table>';
		echo '<tr><th>class_id</th><th>name</th><th>description</th><th>grade</th><th>prereqs</th></tr>';
		// Fetch and print all the records:
		while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
			echo '<tr><td>' . $row['class_id'] . '</td><td>' . $row['name'] . '</td><td>' . $row['description'] .'</td><td>' . $row['grade'] .'</td><td>' . $row['prerequisites'] .'</td></tr>';
		}
		echo '</table>';
	}
?>

<hr size="3" />

<form enctype="multipart/form-data" action="create_class.php" method="post">
  <fieldset>
    <legend>Create a new Class:</legend>
	<label for="id" > Class ID: </label> <input type="text" id="id" name="id" class="input" /><br />
    <label for="name"> Name: </label> <input type="text" id="name" name="name" class="input" /><br />
    <label for="description"> Description: </label> <input type="text" id="description" name="description" class="input" /><br />
	<label for="grade"> Grade: </label> <input type="text" id="grade" name="grade" class="input" /><br />
	<label for="prereqs"> Prerequisites: </label> <input type="text" id="prereqs" name="prereqs" class="input" /><br />
    <label>&nbsp; </label> <input type="submit" value="Create!" name="submit" class="button"/>
 </fieldset>
</form>

</div>
	
<?php include ('includes/footer.html'); ?>