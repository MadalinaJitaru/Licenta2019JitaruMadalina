<?php 
function getSemester()
{
	if(date("n") < 5)
	{
		return 1;
	}
	return 2;
}

function getYearOfStudy($idGroup)
{
	require './classes/database.php';

	$result = $con->prepare("select * from groups where id=?");
	$result->bind_param('i', $idGroup);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);
	
	return $row['study_year_id'];
}
function isGivenFeedbackForThisProfessor($rowCourse, $rowProfessor)
{
	require './classes/database.php';

	$result = $con->prepare("select * from feedback_received_professors where professor_id=? and student_id=? and course_id=?");
	$result->bind_param('iii', $rowProfessor["id"],$_SESSION['idStudent'],  $rowCourse['id']);
	$result->execute();

	$stmt = $result->get_result();
	if($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		return true;
	}
	return false;
}
function getOutputProfessorWithInputDisabledForStudent($output, $rowCourse, $rowProfessor)
{
	$output .= '<label class="professor" style="color:#cacaca">'.$rowProfessor["title"].' '.strtoupper($rowProfessor["last_name"]).' '.$rowProfessor["first_name"].'<input disabled type="radio" name="professor" value="'.$rowCourse['id'].' '.$rowProfessor["id"].'"><br>
	<span class="pseudoRadioButton"></span>
	</label>';
	return $output;
}
function getOutputProfessorWithoutInputDisabledForStudent($output, $rowCourse, $rowProfessor)
{
	$output .= '<label class="professor">'.$rowProfessor["title"].' '.strtoupper($rowProfessor["last_name"]).' '.$rowProfessor["first_name"].'<input type="radio" name="professor" value="'.$rowCourse['id'].' '.$rowProfessor["id"].'"><br>
	<span class="pseudoRadioButton"></span>
	</label>';
	return $output;
}
function getOutputProfessorForStudent($output, $rowCourse, $rowProfessor)
{
	if(isGivenFeedbackForThisProfessor($rowCourse, $rowProfessor))
	{
		$output = getOutputProfessorWithInputDisabledForStudent($output, $rowCourse, $rowProfessor,);
	}
	else
	{
		$output = getOutputProfessorWithoutInputDisabledForStudent($output, $rowCourse, $rowProfessor);
	}
	return $output;
}
function getOutputProfessorsForStudent($output, $groupIdStudent, $rowCourse)
{
	require './classes/database.php';
	$output .= '<div class="chooseProfessor"><div class="course">
	<p>'.$rowCourse['course_title'].'</p>
	</div>        
	<div class="professors">';

	$resultProfessor = $con->prepare("select distinct p.id, p.first_name, p.last_name, p.title from professor_courses pc join professors p ON p.id=pc.professor_id join professor_groups pg on pg.professor_id=p.id where pc.course_id=? and pg.group_id=?");
	$resultProfessor->bind_param('ii',  $rowCourse['id'], $groupIdStudent);
	$resultProfessor->execute();

	$stmtProfessor = $resultProfessor->get_result();
	while ($rowProfessor = mysqli_fetch_array($stmtProfessor, MYSQLI_ASSOC))
	{
		$output = getOutputProfessorForStudent($output, $rowCourse, $rowProfessor);
	}
	$output .= '</div></div>';
	return $output;
}
function getCoursesAndProfessorsForStudent($groupIdStudent, $semester, $yearOfStudy)
{
	$output = '';
	require './classes/database.php';
	
	$resultCourse = $con->prepare("select distinct c.id, c.course_title from professor_groups pg join professor_courses pc on pg.professor_id=pc.professor_id join courses c on pc.course_id=c.id join professors p on p.id=pg.professor_id where pg.group_id=? and c.semester=? and c.study_year_id=?");
	$resultCourse->bind_param('iii', $groupIdStudent, $semester, $yearOfStudy);
	$resultCourse->execute();

	$stmtCourse = $resultCourse->get_result();
	while ($rowCourse = mysqli_fetch_array($stmtCourse, MYSQLI_ASSOC))
	{
		$output = getOutputProfessorsForStudent($output, $groupIdStudent, $rowCourse);
	}

	return $output;
}
function fetchDataStudent()
{
	$semester = getSemester();
	$groupIdStudent = $_SESSION['groupIdStudent'];
	$yearOfStudy = getYearOfStudy($groupIdStudent);


	return getCoursesAndProfessorsForStudent($groupIdStudent, $semester, $yearOfStudy);
}
?>