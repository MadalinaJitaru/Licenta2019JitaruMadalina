<?php
function getDataProfessor()
{
	require '../../classes/database.php';

	$result = $con->prepare("select * from professors where id = ?");
	$result->bind_param('i', $_SESSION['professorIdForProfessor']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row;
}
function getFacultyIdForFeedbackResults()
{
	require '../../classes/database.php';

	$result = $con->prepare("select * from faculty_professors where professor_id = ?");
	$result->bind_param('i', $_SESSION['idProfessor']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['faculty_id'];
}
function getCommentsFromExport($questionId)
{
	require '../../classes/database.php';
	$outputPositive = '';
	$outputNegative = '';
	$comments = '';
	
	$resultComment = $con->prepare("select * from professor_question_answers pqa join question_answers qa on pqa.question_answer_id=qa.id join answers a on a.id=qa.answer_id where pqa.professor_id=? and pqa.course_id=? and qa.faculty_question_id=?");
	$resultComment->bind_param('iii', $_SESSION['professorIdForProfessor'], $_SESSION['courseIdForProfessor'], $questionId);
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
function getAverageForExport($questionId)
{
	require '../../classes/database.php';
	
	$resultGrade = $con->prepare("select round(AVG(a.answer_grade), 2) as 'average' from professor_question_answers pqa join question_answers qa on pqa.question_answer_id=qa.id join answers a on a.id=qa.answer_id where pqa.professor_id=? and pqa.course_id=? and qa.faculty_question_id=?");
	$resultGrade->bind_param('iii', $_SESSION['professorIdForProfessor'], $_SESSION['courseIdForProfessor'], $questionId);
	$resultGrade->execute();

	$stmtGrade = $resultGrade->get_result();
	$rowGrade = mysqli_fetch_array($stmtGrade, MYSQLI_ASSOC);
	
	return $rowGrade['average'];
}
function exportResultsFeedback()
{
	require '../../classes/database.php';

	$professor = getDataProfessor();

	header('Content-Type: txt/csv; charset=utf-8');
	header('Content-Disposition: attachment; fileName='.$professor["title"].' '.strtoupper($professor["first_name"]).' '.$professor["last_name"].'.csv');

	$output = fopen("php://output", "w");
	fputcsv($output, array('Intrebare', 'Nota', 'Comentarii'));

	$facultyId = getFacultyIdForFeedbackResults();

	$result = $con->prepare("select * from questions q join faculty_questions fq on fq.question_id=q.id where fq.faculty_id = ?");
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();

	while($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$average = getAverageForExport($row['id']);

		$comments = getCommentsFromExport($row['id']);

		fputcsv($output, array($row['question_text'], $average, $comments));
	}
	fclose($output);
}
if(isset($_POST['exportResultsFeedback']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	exportResultsFeedback();
	die();
}
class FeedbackResults extends Controller {

}

?>