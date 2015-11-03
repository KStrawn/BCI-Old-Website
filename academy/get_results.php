<?php
require_once ('includes/mysqli_connect.php'); //Connect to the DB

$query = $dbc->query("SELECT ChildsName FROM students");
$arrResults = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));

/*
if($query) {

	//echo '<ul style="list-style-type:none">';
	// While there are results loop through them - fetching an Object (i like PHP5 btw!).
	while ($result = $query ->fetch_object()) {
		// Format the results, im using <li> for the list, you can change it.
		// The onClick function fills the textbox with the result.
		
		// YOU MUST CHANGE: $result->value to $result->your_colum
		echo '<li onClick="fill(\''.$result->value.'\');">'.$result->value.'</li>';
	}
	//echo '</ul>';
}*/

// Do your DB calls here to fill an array of results
//$arrResults = array('option 1', 'option 2', 'option 3');

// Print them out, one per line
echo implode("\n", $arrResults); 

?>