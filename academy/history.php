<?php  

	// Set the page title and include the HTML header:
	$page_title = 'History';
	$page_section = 'Attendance';
	
	require_once ('includes/config.inc.php'); 
	include ('includes/header.html');
?>

<link type="text/css" href="css/jquery-ui-1.8.19.custom.css" rel="stylesheet" />
<script type="text/javascript" src="includes/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="includes/jquery-ui-1.8.19.custom.min.js"></script>

<script>
	$(function() {
		
		$( "#datepicker" ).datepicker();
		
		var studentList = [
			<?php // PHP to create the array of child names
	
				$query = "SELECT ChildID, ChildsName FROM student";
				$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
				
				// Makes sure there are child names selected
				if ($results->num_rows == 0) {
					echo '<strong> There are no staff in the table! </strong>';
				} else {
					// Fetch and print all the records:
					while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
						echo '{ label: "' . $row['ChildsName'] . '", value: "' . $row['ChildID'] . '" },';
					}
				}
			?>
		];
		
		$( "#students" ).autocomplete({
			source: studentList
		});
	});
</script>

<?php
// Set the page variables
//**************************************************************************************************************
if (isset($_GET['id'])) {
	$childid = $_GET['id'];
}

// Check if a date is selected, otherwise use today's date
if (isset($_GET['overalldate'])) {
	$overalldate = $_GET['overalldate'];
} else {
	$overalldate = Date("m/d/Y");
}
$sqloveralldate = date("Y-m-d", strtotime($overalldate));


// Start of left column
//**************************************************************************************************************
echo '<div id="his">
	<div id="hisleft">';
	
// Datepicker box to show attendance from that date
//**************************************************************************************************************
echo '<form action="history.php" method="GET">
	<label>Choose a date:</label><input type="text" name="overalldate" id="datepicker" value="' . $overalldate . '" onchange="this.form.submit();">';
	if (isset($_GET['id'])) {
		echo '<input type="hidden" name="id" value="' . $childid . '" />';
	}
echo '</form>';

// Query to get the list of classes on the selected date
//**************************************************************************************************************
$query = "SELECT scs.section_id, sc.name FROM student_class_sections scs JOIN student_classes sc ON scs.class_id = sc.class_id 
			WHERE sc.name LIKE '%homeroom%'";
$results1 = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));

// Tables - one for each homeroom instance
//**************************************************************************************************************
while ($table = mysqli_fetch_array($results1, MYSQLI_ASSOC)) {
	echo '<table>';
	echo '<thead><tr><th scope="col">' . $table['section_id'] . '</th><th scope="col">' . $overalldate . '</th></tr></thead><tbody>';
	
	// Query to get the list of students in a homeroom
	//**************************************************************************************************************
	$query = "SELECT s.ChildsName, sa.status FROM student s JOIN student_attendance sa ON s.childid = sa.childid 
	WHERE sa.section_id = {$table['section_id']} 
	AND sa.class_date = '{$sqloveralldate}' ";
	$results2 = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
	
	while ($student = mysqli_fetch_array($results2, MYSQLI_ASSOC)) {
		echo '<tr><td>' . $student['ChildsName'] . '</td><td>' . $student['status'] . '</td></tr>';
	}

	echo '</table>';
}


// Start of right column1
//**************************************************************************************************************
echo '</div><div id="hisright"><div id="hisright1">';

if (!isset($_GET['id'])) { // If a student id has not been submitted

	// Form to display the EMPTY student name/id selection box
	//**************************************************************************************************************
	echo '<form action="history.php" method="GET" >
	<label for="students">Student: </label>
	<input id="students" name="id" />
	</form>';

} else { // if a student id has been selected
	
	// Query to get the name of the student selected
	//**************************************************************************************************************
	$query = "SELECT ChildsName FROM student WHERE ChildID = '{$_GET['id']}'";
	$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
	$student = mysqli_fetch_array($results, MYSQLI_ASSOC);
	$childName = $student['ChildsName'];
	
	
	// Form to display the FILLED IN student name/id selection box
	//**************************************************************************************************************
	echo '<form action="history.php" method="GET" >
	<label for="students">Student: </label>
	<input id="students" name="id" value="' . $childid . '" /><br />';
	
	
	// Datepicker box to show attendance from that date
	//**************************************************************************************************************
	echo '<label>Date:</label><input type="text" name="overalldate2" id="datepicker" value="' . $overalldate . '" onchange="this.form.submit();">';
	echo '</form>';

	
	// Query to get the list of attendance statuses for the last 10 days
	//**************************************************************************************************************
	$query = "SELECT sa.class_date, sa.status FROM student_class_sections scs JOIN student_classes sc ON scs.class_id = sc.class_id 
				JOIN student_attendance sa ON sa.section_id = scs.section_id 
				WHERE sc.name LIKE '%homeroom%' 
				AND sa.childid = '$childid'
				ORDER BY sa.class_date DESC LIMIT 10";
	$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
	
	
	//  Table of last 10 statuses for the selected student
	//**************************************************************************************************************
	echo '<table style="width:200px"><thead><tr><th scope="col">' . $childName . '</th><th></th></tr></thead>';
	while ($date = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
		echo '<tr><td>' . $date['class_date'] . '</td><td style="text-align:center">' . $date['status'] . '</td></tr>';
	}
	echo '</table>';
	
	
}
	
// Start of right column2
//**************************************************************************************************************?>
</div><div id="hisright2">
	<table style="width:250px">
		<tr><th> Legend </th><th></th></tr>
		<tr><td> Present </td><td> P </td></tr>
		<tr><td> Absent </td><td> A </td></tr>
		<tr><td> Excused Absence </td><td> E </td></tr>
		<tr><td> Tardee </td><td> T </td></tr>
		<tr><td> Excused Tardee </td><td> X </td></tr>
	</table>
</div></div></div>

<?php include ('includes/footer.html'); ?>