<?php
if(isset($_POST['editCourseId']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}
	$_SESSION['courseIdForEdit'] = $_POST['editCourseId'];
	echo updateCourseFetchData();
}
function requireDatabaseForCourse()
{
	if(isset($_POST['editCourseId']))
	{
		require '../../classes/database.php';			
	}
	else
	{
		require './classes/database.php';
	}
	return $con;
}
function getFacultyIdForEditCourse()
{
	$con = requireDatabaseForCourse();

	$result = $con->prepare("select * from faculty_professors where professor_id = ?");
	$result->bind_param('i', $_SESSION['idProfessor']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['faculty_id'];
}
function fetchDataCourseForUpdate($courseId)
{
	$facultyId = getFacultyIdForEditCourse();

	$con = requireDatabaseForCourse();

	$result = $con->prepare("select * from courses where id=?");
	$result->bind_param('i', $courseId);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);
	return $row;
}
function fetchYearOfStudyForCourse($output, $yearOfStudyId)
{
	$facultyId = getFacultyIdForEditCourse();
	
	$con = requireDatabaseForCourse();	
	$result = $con->prepare("select * from study_years where faculty_id = ?");
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();
	while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$output .= '<label class="option">'.$row['study_year'];
		if($yearOfStudyId == $row['id'])
		{
			$output .= '<input checked type="radio" name="idStudyYear" value="'.$row['id'].'">';
		}
		else
		{
			$output .= '<input type="radio" name="idStudyYear" value="'.$row['id'].'">';
		}
		$output .= '
		<br>
		<span class="pseudoRadioButton"></span>
		</label>';
	}

	return $output;
}
function fetchSemesterForCourse($output, $semester)
{
	if($semester == -1)
	{
		$output .= '<label class="option">1';
		$output .= '<input type="radio" name="semester" value="1">';
		$output .= '
		<br>
		<span class="pseudoRadioButton"></span>
		</label>';
		$output .= '<label class="option">2';
		$output .= '<input type="radio" name="semester" value="2">';
		$output .= '
		<br>
		<span class="pseudoRadioButton"></span>
		</label>';
	}
	else
	{
		if($semester == 1)
		{
			$output .= '<label class="option">1';
			$output .= '<input checked type="radio" name="semester" value="1">';
			$output .= '
			<br>
			<span class="pseudoRadioButton"></span>
			</label>';
			$output .= '<label class="option">2';
			$output .= '<input type="radio" name="semester" value="2">';
			$output .= '
			<br>
			<span class="pseudoRadioButton"></span>
			</label>';
		}
		elseif ($semester == 2)
		{
			$output .= '<label class="option">1';
			$output .= '<input type="radio" name="semester" value="1">';
			$output .= '
			<br>
			<span class="pseudoRadioButton"></span>
			</label>';
			$output .= '<label class="option">2';
			$output .= '<input checked type="radio" name="semester" value="2">';
			$output .= '
			<br>
			<span class="pseudoRadioButton"></span>
			</label>';
		}
	}
	
	return $output;
}
function readCourseFetchData()
{
	$output = '
	<span class="container" style="cursor:default">
	<div class="gridContainerCourse">
	<div class="gridItem">Nr.</div>
	<div class="gridItem">An de studiu</div>
	<div class="gridItem">Semestru</div>
	<div class="gridItem">Titlu curs</div>
	</div>
	</span>';
	$indexCourse = 0;
	$facultyId = getFacultyIdForEditCourse();

	require './classes/database.php';

	$result = $con->prepare('select c.id, sy.study_year,c.semester, c.course_title from courses c join study_years sy on sy.id=c.study_year_id where sy.faculty_id=? order by sy.study_year, c.semester');
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();
	while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$indexCourse += 1;
		$output .= '
		<label onclick="hideElement(\'optionError\')" for="line'.$indexCourse.'" class="container">';
		if(isset($_SESSION['courseIdForEdit']) && $row['id'] == $_SESSION['courseIdForEdit'])
		{
			$output .= '<input checked id="line'.$indexCourse.'" type="radio" name="course" value="'.$row['id'].'">';	
		}
		else
		{
			$output .= '<input id="line'.$indexCourse.'" type="radio" name="course" value="'.$row['id'].'">';
		}
		$output .= '
		<div class="gridContainerCourse">
		<div class="gridItem">'.$indexCourse.'. </div>
		<div class="gridItem">'.ucfirst($row["study_year"]).'</div>
		<div class="gridItem">'.ucfirst($row["semester"]).'</div>
		<div class="gridItem">'.ucfirst($row["course_title"]).'</div>
		</div>
		</label>';
	}
	return $output;
}
function createCourseFetchData()
{
	$output = '
	<h1>Adaugare curs</h1>
	<form action="./controllers/admin/crudCourse.php" method="post">
	<div class="crudPopUpContainer">
	<div class="crudInputContainer">
	<label for="courseTitleAdd">Titlu curs</label>
	<input type="text" name="courseTitleAdd" id="courseTitleAdd" placeholder="Introduceti titlul cursului">
	<br>
	</div>
	<div class="crudRadioButtonContainer">
	<label>Anul de studiu</label>
	<div class="options">';
	$output = fetchYearOfStudyForCourse($output, -1);
	$output .= '
	</div>
	</div>
	<div class="crudRadioButtonContainer" id="crudRadioButtonContainerAddSemester">
	<label>Semestru</label>
	<div class="optionsSemester">';
	$output = fetchSemesterForCourse($output, -1);
	$output .= '
	</div>
	</div>
	</div>
	<div class="crudEntityButton">
	<button type="submit" name="createCourse">Adauga</button>
	</div>
	</form>';
	return $output;
}
function updateCourseFetchData()
{
	$row = fetchDataCourseForUpdate($_SESSION['courseIdForEdit']);
	$output = '
	<h1>Modificare curs</h1>
	<form action="./controllers/admin/crudCourse.php" method="post">
	<div class="crudPopUpContainer">
	<div class="crudInputContainer">
	<label for="courseTitleEdit">Titlu curs</label>
	<input type="text" name="courseTitleEdit" id="courseTitleEdit" placeholder="Introduceti titlul cursului" value="'.$row["course_title"].'">
	<br>
	</div>
	<div class="crudRadioButtonContainer">
	<label>Anul de studiu</label>
	<div class="options">';
	$output = fetchYearOfStudyForCourse($output, $row['study_year_id']);
	$output .= '
	</div>
	</div>
	<div class="crudRadioButtonContainer" id="crudRadioButtonContainerEditSemester">
	<label>Semestru</label>
	<div class="optionsSemester">';
	$output = fetchSemesterForCourse($output, $row['semester']);
	$output .= '
	</div>
	</div>
	</div>
	<div class="crudEntityButton">
	<button type="submit" name="updateCourse">Modifica</button>
	</div>
	</form>
	';
	return $output;
}
?>