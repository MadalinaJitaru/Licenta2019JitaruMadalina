<?php
function getSemesterForResultsFeedback()
{
	if(date("n") < 5)
	{
		return 1;
	}
	return 2;
}
function getFacultyIdForAllFeedbackResults()
{
	require '../../classes/database.php';

	$result = $con->prepare("select * from faculty_professors where professor_id = ?");
	$result->bind_param('i', $_SESSION['idProfessor']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['faculty_id'];
}
function existFeedback($idProfessor, $idCourse)
{
	require './classes/database.php';
	
	$result = $con->prepare("select count(id) from feedback_received_professors where professor_id = ? and course_id = ?");
	$result->bind_param('ii', $idProfessor, $idCourse);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	if($row['count(id)'] == 0)
	{
		return false;
	}
	return true;
}
function getAverageForExportAll($professorId, $courseId, $questionId)
{
	require '../../classes/database.php';
	
	$resultGrade = $con->prepare("select round(AVG(a.answer_grade), 2) as 'average' from professor_question_answers pqa join question_answers qa on pqa.question_answer_id=qa.id join answers a on a.id=qa.answer_id where pqa.professor_id=? and pqa.course_id=? and qa.faculty_question_id=?");
	$resultGrade->bind_param('iii', $professorId, $courseId, $questionId);
	$resultGrade->execute();

	$stmtGrade = $resultGrade->get_result();
	$rowGrade = mysqli_fetch_array($stmtGrade, MYSQLI_ASSOC);
	
	return $rowGrade['average'];
}
function getCommentsForExportAll($professorId, $courseId, $questionId)
{
	require '../../classes/database.php';
	$outputPositive = '';
	$outputNegative = '';
	$comments = '';
	
	$resultComment = $con->prepare("select * from professor_question_answers pqa join question_answers qa on pqa.question_answer_id=qa.id join answers a on a.id=qa.answer_id where pqa.professor_id=? and pqa.course_id=? and qa.faculty_question_id=?");
	$resultComment->bind_param('iii', $professorId, $courseId, $questionId);
	$resultComment->execute();

	$stmtComment = $resultComment->get_result();
	while ($rowComment = mysqli_fetch_array($stmtComment, MYSQLI_ASSOC))
	{
		if($rowComment['answer_positive'] != '')
		{
			$outputPositive .= ucfirst($rowComment['answer_positive']).' ';			
		}
		if($rowComment['answer_negative'] != '')
		{
			$outputNegative .= ucfirst($rowComment['answer_negative']).' ';			
		}
	}
	if($outputNegative == '' and $outputPositive == '')
	{
		$comments .= 'Nu sunt comentarii!';
	}
	else
	{
		$comments .= ucfirst($outputNegative).' '.ucfirst($outputPositive);
	}
	return $comments;
}
function exportAllResultsFeedback()
{
	require '../../classes/database.php';

	header('Content-Type: txt/csv; charset=utf-8');
	header('Content-Disposition: attachment; fileName=statisticiFeedback.csv');

	$output = fopen("php://output", "w");
	fputcsv($output, array('Curs', 'Intrebare', 'Profesor', 'Nota', 'Comentarii'));

	$semester = getSemesterForResultsFeedback();
	$facultyId = getFacultyIdForAllFeedbackResults();

	$result = $con->prepare("	select * from courses c join study_years sy on sy.id=c.study_year_id join professor_courses pc on pc.course_id=c.id join professors p on p.id=pc.professor_id where sy.faculty_id=? and c.semester = ? order by c.course_title");
	$result->bind_param('ii', $facultyId, $semester);
	$result->execute();

	$stmt = $result->get_result();

	while($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$professor = $row['title'].' '.$row['first_name'].' '.$row['last_name'];

		$resultQuestion = $con->prepare("select * from questions q join faculty_questions fq on fq.question_id=q.id where fq.faculty_id = ?");
		$resultQuestion->bind_param('i', $facultyId);
		$resultQuestion->execute();

		$stmtQuestion = $resultQuestion->get_result();

		while($rowQuestion = mysqli_fetch_array($stmtQuestion, MYSQLI_ASSOC))
		{
			$average = getAverageForExportAll($row['professor_id'], $row['course_id'], $rowQuestion['id']);

			$comments = getCommentsForExportAll($row['professor_id'], $row['course_id'], $rowQuestion['id']);

			fputcsv($output, array($row['course_title'], $rowQuestion['question_text'], $professor, $average, $comments));
		}
	}
	fclose($output);
}
if(isset($_POST['feedbackResults']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}
	if(!isset($_POST['professor']))
	{
		$_SESSION['invalidData'] = true;
		header('Location: ./chooseProfessor-admin');
	}
	else
	{
		$ids = explode(' ', $_POST['professor']);
		$_SESSION['courseIdForProfessor'] = $ids[0];
		$_SESSION['professorIdForProfessor'] = $ids[1];
		if(existFeedback($_SESSION['professorIdForProfessor'], $_SESSION['courseIdForProfessor']))
		{
			$_SESSION['existFeedback'] = true;
		}
		else
		{
			$_SESSION['existFeedback'] = false;	
		}
		//echo $_SESSION['courseIdForProfessor'];
		header('Location: ./feedbackResults-admin');

	}
	
	die();
}
if(isset($_POST['exportAllResultsFeedback']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	exportAllResultsFeedback();
	die();
}
class ChooseProfessorForAdmin extends Controller {
}
?>