<?php 
function isTitular($idCourse)
{
	require './classes/database.php';

	$result = $con->prepare("select is_titular from professor_courses where professor_id = ? and course_id = ?");
	$result->bind_param('ii', $_SESSION['idProfessor'], $idCourse);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);
	
	if ($row['is_titular'] == 0)
	{
		return false;
	}

	return true;
}
function getOutputProfessorForProfessor($output, $rowCourse, $rowProfessor)
{
	$output .= '<label class="professor">'.$rowProfessor['title'].' '.strtoupper($rowProfessor['last_name']).' '.$rowProfessor['first_name'].'<input type="radio" name="professor" value="'.$rowCourse['id'].' '.$rowProfessor['id'].'"><br>
	<span class="pseudoRadioButton"></span>
	</label>';
	return $output;
}
function getOutputProfessorsForProfessor($output, $rowCourse)
{
	require './classes/database.php';
	$output .= '<div class="chooseProfessor"><div class="course">
	<p>'.$rowCourse['course_title'].'</p>
	</div>        
	<div class="professors">';

	if(isTitular($rowCourse['id']))
	{
		$resultProfessor = $con->prepare("select distinct p.id, p.first_name, p.last_name, p.title from professor_courses pc join professors p ON p.id=pc.professor_id join professor_groups pg on pg.professor_id=p.id where pc.course_id=? ");
		$resultProfessor->bind_param('i', $rowCourse['id']);
	}
	else
	{
		$resultProfessor = $con->prepare("select id, first_name, last_name, title from professors where id=?");
		$resultProfessor->bind_param('i', $_SESSION['idProfessor']);
	}
	$resultProfessor->execute();

	$stmtProfessor = $resultProfessor->get_result();

	while ($rowProfessor = mysqli_fetch_array($stmtProfessor, MYSQLI_ASSOC))
	{
		$output = getOutputProfessorForProfessor($output, $rowCourse, $rowProfessor);
	}
	
	$output .= '</div></div>';
	return $output;
}
function getCoursesAndProfessorsForProfessor($semester)
{
	$output = '';
	require './classes/database.php';
	
	$resultCourse = $con->prepare("select distinct c.id, c.course_title from professor_courses pc join courses c on pc.course_id=c.id join professors p on p.id=pc.professor_id where c.semester=? and p.id=? order by pc.is_titular desc");
	$resultCourse->bind_param('ii', $semester, $_SESSION['idProfessor']);
	$resultCourse->execute();

	$stmtCourse = $resultCourse->get_result();
	while ($rowCourse = mysqli_fetch_array($stmtCourse, MYSQLI_ASSOC))
	{
		$output = getOutputProfessorsForProfessor($output, $rowCourse);
	}
	return $output;
}

function fetchDataProfessor()
{
	$semester = getSemester();

	return getCoursesAndProfessorsForProfessor($semester);
}
?>