<?php

	// Set the page title and include the HTML header:
	$page_title = 'Enrollment';
	$page_section = 'Students';
	
	require_once ('includes/config.inc.php'); 
	include ('includes/header.html');
?>

<link type="text/css" href="css/jquery-ui-1.8.18.custom.css" rel="stylesheet" />
<script type="text/javascript" src="includes/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="includes/jquery-ui-1.8.18.custom.min.js"></script>

<script type="text/javascript">
	$(function(){
		
		var studentList = [
			<?php // PHP to create the array of child ids and names
	
				$query = "SELECT ChildID, ChildsName FROM student";
				$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
				
				// Makes sure there are child names selected
				if ($results->num_rows == 0) {
					echo '<tr><strong> There are no staff in the table! </strong></tr>';
				} else {
					// Fetch and print all the records:
					while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
						echo '{ label: "' . $row['ChildsName'] . '", value: "' . $row['ChildID'] . '" },';
					}
				}
			?>
		];
		
		var classList = [
			<?php // PHP to create the array of class ids and names
	
				$query = "SELECT sc.name, scs.section_id FROM student_classes sc JOIN student_class_sections scs ON scs.class_id = sc.class_id";
				$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
				
				// Makes sure there are class names selected
				if ($results->num_rows == 0) {
					echo '<tr><strong> There are no classes in the table! </strong></tr>';
				} else {
					// Fetch and print all the records:
					while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
						echo '{ label: "' . $row['name'] . '", value: "' . $row['section_id'] . '" },';
					}
				}
			?>
		];
		
		$( "#students" ).autocomplete({
			source: studentList
		});
		
		$( "#classes" ).autocomplete({
			source: classList
		});
		
	});
</script>


<?php

if (!isset($_GET['id'])) { // If a student id has not been submitted
	echo '<div style="color:red;margin-top:30px;margin-left:30px"><strong> Please select a student before coming to this page.</strong></div>';
} else { 

	// Register the student for a class if the submit button is pressed
	//**************************************************************************************************************
	if (isset($_POST['register'])) {
	
		// Check if the student is already registered for the class
		$q = "SELECT ChildID FROM student_registration WHERE section_id = {$_POST['sectionid']} AND ChildID = '{$_GET['id']}'";
		$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		
		if ($r->num_rows == 0) {	//If the studentid/sectionid combo doesn't exist
			// Register for class
			$q = "INSERT INTO student_registration(childid, section_id) VALUES('{$_GET['id']}', {$_POST['sectionid']})";
			$r = mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));
		}
	}

	
	// Query to get the default grade
	//**************************************************************************************************************
	$query = "SELECT grade FROM student_additional_data WHERE childid = '{$_GET['id']}'";
	$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
	$student = mysqli_fetch_array($results, MYSQLI_ASSOC);
	if ($results->num_rows == 0 || empty($student['grade']))	// If the student doesn't have a listed grade
		$defaultGrade = 1;
	else
		$defaultGrade = $student['grade'];
	
	
	// Query to get the name of the student selected
	//**************************************************************************************************************
	$query = "SELECT childsname FROM student WHERE childid = '{$_GET['id']}'";
	$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
	$student = mysqli_fetch_array($results, MYSQLI_ASSOC);
	
	
	// Set the page variables
	//**************************************************************************************************************
	$childid = $_GET['id'];
	$childName = $student['childsname'];
	if (isset($_GET['year']))
		$year = $_GET['year'];
	else
		$year = Date("Y");	// Default year is current year
	
	if (!empty($_GET['grade']))
		$grade = $_GET['grade'];
	else
		$grade = $defaultGrade;		// Default grade is the grade of the student if listed, otherwise 1st grade
	
		
		
	// Form to display the student's name
	//**************************************************************************************************************
	echo '<div id="en"><div id="enleft"><form action="enrollment.php" method="GET" class="topleft" >
	<label>Student: </label>
	<input readonly="readonly" value="' . $childName . '" />
	</form>';
	
	
	// Query get the years for the drop-down box of years
	//**************************************************************************************************************
	$query = "SELECT class_year FROM student_class_sections GROUP BY class_year";
	$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
	
	
	// Drop-down box to select a year for classes
	//**************************************************************************************************************
	echo '<form action="enrollment.php" method="GET" style="float:left;margin-left:30px;margin-top:20px">
	<label>School Year: </label>
	<select name="year" onchange="this.form.submit()">';
		while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
			if ($row['class_year'] == $year) {
				echo '<option selected="selected" value="' . $row['class_year'] . '">' . $row['class_year'] . '</option>';
			} else {
				echo '<option value="' . $row['class_year'] . '">' . $row['class_year'] . '</option>';
			}
		}
	echo '</select>
	<input type="hidden" name="id" value="' . $childid . '" />
	</form><br />';
	
	
	// Query to get the grades for the drop-down box of grades
	//**************************************************************************************************************
	$query = "
	SELECT sc.grade FROM student_classes sc 
	JOIN student_class_sections scs 
	ON sc.class_id = scs.class_id 
	WHERE scs.class_year = {$year} 
	GROUP BY sc.grade";
	$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
	
	
	// Drop down box to select a grade for classes
	//**************************************************************************************************************
	echo '<form action="enrollment.php" method="GET" style="margin-left:15px">
	<label> Select a Grade: </label>
	<select name="grade" onchange="this.form.submit()">';
		while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
			if ($row['grade'] == $grade) {
				echo '<option selected="selected" value="' . $row['grade'] . '">' . $row['grade'] . '</option>';
			} else {
				echo '<option value="' . $row['grade'] . '">' . $row['grade'] . '</option>';
			}
		}
	echo '</select>
	<input type="hidden" name="id" value="' . $childid . '" />
	<input type="hidden" name="year" value="' . $year . '" />
	</form>';
	
	
	// Query to get the classes for the drop-down box of classes
	//**************************************************************************************************************
	$query = "
	SELECT sc.name, scs.section_id FROM student_classes sc JOIN student_class_sections scs ON sc.class_id = scs.class_id 
	WHERE scs.class_year = {$year} 
	AND sc.grade = {$grade}";
	$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
	
	
	// Drop down box to select a class to register for
	//**************************************************************************************************************
	echo '<form action="enrollment.php?id=' . $childid . '" method="POST" style="margin-left:15px">
	<label> Select a Class: </label>
	<select name="sectionid" >';	//onchange="this.form.submit()"
	// Loop through classes and populate the drop-down box
	while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
		echo '<option value="' . $row['section_id'] . '">' . $row['name'] . '</option>';
	}
	echo '</select>';
	echo '<input type="submit" name="register" value="Register" />
	</form>';

	
	// Query for the table of currently enrolled classes
	//**************************************************************************************************************
	$query = "
	SELECT sc.grade, sc.name, sf.staff_name, sc.prerequisites 
	FROM student_registration sr JOIN student_class_sections scs ON sr.section_id = scs.section_id
	JOIN student_classes sc ON scs.class_id = sc.class_id
	JOIN staff sf ON scs.staff_id = sf.staff_id
	WHERE sr.ChildID = '$childid' 
	AND scs.class_year = $year";
	$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));

	
	// Table of currently enrolled classes
	//**************************************************************************************************************
	echo '<table id="enrolltable">
	<thead><tr><th scope="col"> Grade </th><th scope="col"> Class Name </th><th scope="col"> Teacher </th><th scope="col"> Prerequisites </th></tr></thead><tbody>';
	while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
		echo '<tr><td>' . $row['grade'] . '</td><td>' . $row['name'] . '</td><td>' . $row['staff_name'] . '</td><td>' . $row['prerequisites'] . '</td></tr>';
	}
	echo '</tbody></table></div></div>'/*end of "enleft" and "en" div*/;
	
	
	// Start of right column
	//**************************************************************************************************************
	echo '<div id="enright">';
	

	echo '<label> Student ID: </label><input type="text" value="' . $childid . '" readonly="readonly" /><br />';
	echo '<br />';
	echo '<label> Enrollment Date: </label><input type="text" value="' . $childid . '" readonly="readonly" /><br />';
	echo '<label> Registration Fee: </label><input type="text" value="' . $childid . '" readonly="readonly" /><br />';
	echo '<label> Monthly Tuition: </label><input type="text" value="' . $childid . '" readonly="readonly" /><br />';
	echo '<br />';
	echo '<label> Amount Paid at Enrollment: </label><input type="text" value="' . $childid . '" readonly="readonly" /><br />';
	echo '<label> Enrolled by whom: </label><input type="text" value="' . $childid . '" readonly="readonly" /><br />';
	echo '<br />';
	echo '<label style="width:180px"> Notes: </label><textarea rows="3"></textarea>';
	echo '<div align="center">
	<input type="submit" name="test" script="width:250px" value="Create Billing" /></div>';
	
	
	
	
	
	
	
	
	
	
	echo '</div>'/*end of "enright" div*/;
	
}

include ('includes/footer.html');
?>