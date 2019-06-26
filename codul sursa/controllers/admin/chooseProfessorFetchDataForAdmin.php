<?php
function getFacultyId()
{
	require './classes/database.php';

	$result = $con->prepare("select * from faculty_professors where professor_id = ?");
	$result->bind_param('i', $_SESSION['idProfessor']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['faculty_id'];
} 
function getOutputProfessor($output, $rowCourse, $rowProfessor)
{
	$output .= '<label class="professor">'.$rowProfessor["title"].' '.strtoupper($rowProfessor["last_name"]).' '.$rowProfessor["first_name"].'<input type="radio" name="professor" value="'.$rowCourse['id'].' '.$rowProfessor["id"].'"><br>
	<span class="pseudoRadioButton"></span>
	</label>';
	return $output;
}
function getOutputProfessors($output, $rowCourse)
{
	require './classes/database.php';
	$output .= '<div class="chooseProfessor"><div class="course">
	<p>'.$rowCourse['course_title'].'</p>
	</div>        
	<div class="professors">';

	$resultProfessor = $con->prepare("select distinct p.id, p.first_name, p.last_name, p.title from professor_courses pc join professors p ON p.id=pc.professor_id join professor_groups pg on pg.professor_id=p.id where pc.course_id=? ");
	$resultProfessor->bind_param('i', $rowCourse['id']);
	
	$resultProfessor->execute();

	$stmtProfessor = $resultProfessor->get_result();

	while ($rowProfessor = mysqli_fetch_array($stmtProfessor, MYSQLI_ASSOC))
	{
		$output = getOutputProfessor($output, $rowCourse, $rowProfessor,);
	}
	
	$output .= '</div></div>';
	return $output;
}
function getCoursesAndProfessors($semester, $facultyId)
{
	$output = '';
	require './classes/database.php';
	
	//toate cursurile de la facultatea adminului
	$resultAllCourse = $con->prepare("select c.course_title, c.id from courses c join study_years sy on sy.id=c.study_year_id where c.semester=? and sy.faculty_id=?");
	$resultAllCourse->bind_param('ii', $semester, $facultyId);
	$resultAllCourse->execute();

	$stmtAllCourse = $resultAllCourse->get_result();
	while ($rowAllCourse = mysqli_fetch_array($stmtAllCourse, MYSQLI_ASSOC))
	{
		$output = getOutputProfessors($output, $rowAllCourse);
	}

	//cursurile ramase pentru prof
	$resultCourse = $con->prepare("select c.course_title, c.id from courses c join study_years sy on sy.id=c.study_year_id join professor_courses pc on pc.course_id=c.id where c.semester=? and sy.faculty_id<>? and pc.professor_id=?");
	$resultCourse->bind_param('iii', $semester, $facultyId, $_SESSION['idProfessor']);
	$resultCourse->execute();

	$stmtCourse = $resultCourse->get_result();
	while ($rowCourse = mysqli_fetch_array($stmtCourse, MYSQLI_ASSOC))
	{
		$output = getOutputProfessors($output, $rowCourse);
	}
	return $output;
}

function fetchDataAdmin()
{
	$semester = getSemester();
	$facultyId = getFacultyId();
	
	return getCoursesAndProfessors($semester, $facultyId);
}
?>