<?php

	// Set the page title and include the HTML header:
	$page_title = 'Class Offering';
	$page_section = 'Classes';
	
	require_once ('includes/config.inc.php'); 
	include ('includes/header.html');
?>

<link type="text/css" href="css/jquery-ui-1.8.19.custom.css" rel="stylesheet" />
<script type="text/javascript" src="includes/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="includes/jquery-ui-1.8.19.custom.min.js"></script>

<script>
	$(function(){

		var availableStaff = [
			<?php // PHP to create the array of staff names
	
				$query = "SELECT staff_name FROM staff";
				$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
				
				// Makes sure there are staff names selected
				if ($results->num_rows == 0) {
					echo '<strong> There are no staff in the table! </strong>';
				} else {
					// Fetch and print all the records:
					while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
						echo '"' . $row['staff_name'] . '",';
					}
				}
			?>
		];
		
		//print_r(availableStaff);
		
		var availableClasses = [
			
			<?php // PHP to create the array of child names
	
				$query = "SELECT name FROM student_classes";
				$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
				
				// Makes sure there are class names selected
				if ($results->num_rows == 0) {
					echo '<tr><strong> There are no classes in the table! </strong></tr>';
				} else {
					// Fetch and print all the records:
					while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
						echo '"' . $row['name'] . '",';
					}
				}
			?>
			
			
		];
		
		$( "#instructor" ).autocomplete({
			source: availableStaff
		});
		
		$( "#classes" ).autocomplete({
			source: availableClasses
		});
	});
</script>

<div id="co">

<?php
	
	$mode = FALSE;
	$error = FALSE;
	
	if (isset($_POST['add']) || isset($_POST['update'])) {
		$mode = 'add';
		
		// Trim all the incoming data:
		$trimmed = array_map('trim', $_POST);
		
		// Assume invalid values:
		$className = $classID = $year = $instructorName = $period = $room_num = $books = $supplies = $notes = $semester = $staffID = FALSE;
		
		// Set the variables with the incoming data
		$className = mysqli_real_escape_string ($dbc, $trimmed['class_name']);
		$year = intval(mysqli_real_escape_string ($dbc, $trimmed['year']));
		$instructorName = mysqli_real_escape_string ($dbc, $trimmed['instructor_name']);
		$semester = intval(mysqli_real_escape_string ($dbc, $trimmed['semester']));
		$period = intval(mysqli_real_escape_string ($dbc, $trimmed['period']));
		$room_num = mysqli_real_escape_string ($dbc, $trimmed['roomnum']);
		$books = mysqli_real_escape_string ($dbc, $trimmed['books']);
		$supplies = mysqli_real_escape_string ($dbc, $trimmed['supplies']);
		$notes = mysqli_real_escape_string ($dbc, $trimmed['notes']);

		
		// Query to check the validity of the name of the class
		//**************************************************************************************************************
		$query = "SELECT class_id FROM student_classes WHERE name = '$className'";
		$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
		$class = mysqli_fetch_array($results, MYSQLI_ASSOC);
		
		// Makes sure that the name of the class is valid
		if ($results->num_rows == 0) {
			echo '<strong> There are no classes with that name! </strong>';
			$error = TRUE;
		} else {
			$classID = intval($class['class_id']);
		}
		
		// Query to check the validity of the name of the instructor
		//**************************************************************************************************************
		$query = "SELECT staff_id FROM staff WHERE staff_name = '$instructorName'";
		$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
		$staff = mysqli_fetch_array($results, MYSQLI_ASSOC);
		
		// Makes sure that the name of the instructor is valid
		if ($results->num_rows == 0) {
			echo '<strong> There is no instructor with that name! </strong>';
			$error = TRUE;
		} else {
			$staffID = intval($staff['staff_id']);
		}
		
		if (isset($_POST['add'])) {
			// Add the class section to the database:
			if (!$error) {
				$query = "INSERT INTO student_class_sections (class_id, class_year, staff_id, semester, period, roomnum, books, supplies, notes) 
							VALUES ($classID, $year, $staffID, '$semester', $period, '$room_num', '$books', '$supplies', '$notes')";
				$result = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
				
				if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
				
					
					
				} else { // If it did not run OK.
					echo '<p class="error">The class was not successfully added to the database.</p>';
					echo "<p>{$classID}, {$year}, {$staffID}, '{$semester}', {$period}, '{$room_num}', '{$books}', '{$supplies}', '{$notes}'</p>";
				}
			}
		} else {
			// Update the class section in the database:
			if (!$error) {
				$sectionID = intval(mysqli_real_escape_string ($dbc, $trimmed['section']));
				
				$query = "UPDATE student_class_sections 
				SET class_id = $classID, 
				class_year = $year, 
				staff_id = $staffID, 
				semester = '$semester', 
				period = $period, 
				roomnum = '$room_num', 
				books = '$books', 
				supplies = '$supplies', 
				notes = '$notes'
				WHERE section_id = $sectionID";
				$result = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
				
				if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
					
				} else { // If it did not run OK.
					echo '<p class="error">The class section was not successfully updated in the database.</p>';
				}
			}
		}

		
	} else if (isset($_POST['update'])) {
		$mode = 'add';
		
		// Trim all the incoming data:
		$trimmed = array_map('trim', $_POST);
		
		// Assume invalid values:
		$sectionID = $className = $classID = $year = $staffID = $instructorName = FALSE;
		
		// Set the variables with the incoming data
		$sectionID = intval(mysqli_real_escape_string ($dbc, $trimmed['section']));
		$className = mysqli_real_escape_string ($dbc, $trimmed['class_name']);
		$year = intval(mysqli_real_escape_string ($dbc, $trimmed['year']));
		$instructorName = mysqli_real_escape_string ($dbc, $trimmed['instructor_name']);
		
		
		$query = "SELECT class_id FROM student_classes WHERE name = '$className'";
		$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
		
		// Makes sure that the name of the class is valid
		if ($results->num_rows == 0) {
			echo '<strong> There are no classes with that name! </strong>';
			$error = TRUE;
		} else {
			while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				$classID = intval($row['class_id']);
			}
		}
		
		$query = "SELECT staff_id FROM staff WHERE staff_name = '$instructorName'";
		$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
		
		// Makes sure that the name of the instructor is valid
		if ($results->num_rows == 0) {
			echo '<strong> There is no instructor with that name! </strong>';
			$error = TRUE;
		} else {
			while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
				$staffID = intval($row['staff_id']);
			}
		}
		
		
		
		
		
		
	} else if (isset($_POST['edit'])) {
		$mode = 'edit';
		
		
		
	} else {
		$mode = 'view';
	}

	
	// Select Year Box
	//***********************************************
	
	$query = "SELECT Class_year FROM student_class_sections GROUP BY Class_year";
	$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
	
	// Makes sure there are class sections to view
	if ($results->num_rows == 0) {
		echo '<strong> There are no class sections in the database! </strong>';
	} else {
		echo '<select >';
		// Fetch and print all the records:
		while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
			if ($mode == 'edit' || $mode == 'add') {
				if ($_POST['year'] == $row['Class_year']) {
					echo '<option selected="selected" value="' . $row['Class_year'] . '">' . $row['Class_year'] . '</option>';
				} else {
					echo '<option value="' . $row['Class_year'] . '">' . $row['Class_year'] . '</option>';
				}
			} else {
				echo '<option value="' . $row['Class_year'] . '">' . $row['Class_year'] . '</option>';
			}
		}
		echo '</select>';
	}
	
	
	// Classes Table
	//***********************************************
	echo '<h1> Sections </h1>';
	

	// Query to get the list of class sections
	$query = "SELECT section_id, name, class_year, staff_name, student_class_sections.class_id AS class_id, semester, period, roomnum, books, supplies 
	FROM student_classes, student_class_sections, staff 
	WHERE student_class_sections.class_id = student_classes.class_id 
	AND student_class_sections.staff_id = staff.staff_id";
	$results = mysqli_query ($dbc, $query) or trigger_error("Query: $query\n<br />MySQL Error: " . mysqli_error($dbc));
	
	// Makes sure there are staff to select
	if ($results->num_rows == 0) {
		echo '<strong> There are no classes in the database! </strong>';
	} else {
		echo '<table>';
		echo '<tr><th> Class Name</th><th> Class Year </th><th> Instructor </th><th> Semester </th><th> Period </th><th> Room </th>
				<th> Textbook </th><th> Supplies </th><th>  </th></tr>';
		// Fetch and print all the records:
		while ($row = mysqli_fetch_array($results, MYSQLI_ASSOC)) {
			
			if ($mode == 'add' || $mode == 'view') {
				echo '<tr><td>' . $row['name'] . '</td><td>' . $row['class_year'] . '</td><td>' . $row['staff_name'] . '</td><td>' . $row['semester'] . '</td>
						<td>' . $row['period'] . '</td><td>' . $row['roomnum'] . '</td><td>' . $row['books'] . '</td><td>' . $row['supplies'] . '</td>';
				echo '<td><form action="class_offering.php" method="post">
					<input type="submit" name="edit" value="Edit" /> 
					<input type="hidden" name="section" value="' . $row['section_id'] . '" />
					</form></td></tr>';
			} else if ($mode == 'edit') {
				// If if's the section they want to edit:
				if ($_POST['section'] == $row['section_id']) {
					echo '<form action="class_offering.php" method="post"><tr><td><input id="classes" name="class_name" value="' . $row['name'] . '" /></td>';
					echo '<td><input style="width:40px" type="text" name="year" value="' . $row['class_year'] . '" /></td>';
					echo '<td><input id="instructor" name="instructor_name" value="' . $row['staff_name'] . '" /></td>';
					echo '<td><input style="width:30px" name="semester" value="' . $row['semester'] . '" /></td>';
					echo '<td><input style="width:20px" name="period" value="' . $row['period'] . '" /></td>';
					echo '<td><input style="width:50px" name="roomnum" value="' . $row['roomnum'] . '" /></td>';
					echo '<td><input name="books" value="' . $row['books'] . '" /></td>';
					echo '<td><input name="supplies" value="' . $row['supplies'] . '" /></td>';
					echo '<td>
						<input type="submit" name="update" value="Submit" /> 
						<input type="hidden" name="notes" value="None" />
						<input type="hidden" name="section" value="' . $row['section_id'] . '" />
						</tr></form></td>';
				} else {
					echo '<tr><td>' . $row['name'] . '</td><td>' . $row['class_year'] . '</td><td>' . $row['staff_name'] . '</td><td>' . $row['semester'] . '</td>
						<td>' . $row['period'] . '</td><td>' . $row['roomnum'] . '</td><td>' . $row['books'] . '</td><td>' . $row['supplies'] . '</td>';
					echo '<td><form action="class_offering.php" method="post">
						<input type="submit" name="edit" value="Edit" /> 
						<input type="hidden" name="section" value="' . $row['section_id'] . '" />
						</form></td></tr>';
				}
				
			}

		}
		echo '</table>';
	}
	

	
?>

<hr size="3" />


<form enctype="multipart/form-data" action="class_offering.php" method="post">
  <fieldset id="cofieldset">
    <legend>Create a new Class Section:</legend>
	<label for="classes" > Class Name: </label> <input id="classes" name="class_name" /><br />
    <label for="year"> Year: </label> <input type="text" id="year" name="year" class="input" /><br />
    <label for="instructor"> Instructor: </label> <input id="instructor" name="instructor_name" /><br />
	<label for="semester"> Semester: </label> <input id="semester" name="semester" /><br />
	<label for="period"> Period: </label> <input id="period" name="period" /><br />
	<label for="roomnum"> Room Number: </label> <input id="roomnum" name="roomnum" /><br />
	<label for="books"> Text Book(s): </label> <input id="books" name="books" /><br />
	<label for="supplies"> Required Supplies: </label> <input id="supplies" name="supplies" /><br />
	<label for="notes"> Notes: </label> <input id="notes" name="notes" /><br />
    <label>&nbsp; </label> <input type="submit" name="add" value="Create!" class="button"/>
 </fieldset>
</form>

</div> <!-- End of "co" div -->
	
<?php include ('includes/footer.html'); ?>