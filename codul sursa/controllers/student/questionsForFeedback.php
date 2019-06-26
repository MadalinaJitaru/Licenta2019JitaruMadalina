<?php
function isItemsEmpty()
{
	require './classes/database.php';

	$resultQuestion = $con->prepare("select fq.id, q.question_text from faculty_questions fq join questions q on fq.question_id=q.id where faculty_id=?");
	$resultQuestion->bind_param('i', $_SESSION['facultyIdStudent']);
	$resultQuestion->execute();

	$stmtQuestion = $resultQuestion->get_result();
	while ($rowQuestion = mysqli_fetch_array($stmtQuestion, MYSQLI_ASSOC))
	{
		$radioButtonName = "progress".$rowQuestion['id'];
		if (intval($_POST[$radioButtonName]) == 0)
		{
			return false;
		} 
	}
	return true;
}
function getLastId($queryLastId)
{
	require './classes/database.php';

	$result = $con->prepare($queryLastId);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['max(id)'];
}
function insertAnswerInAnswersTable($radioButtonName, $positiveCommentsName, $negativeCommentsName)
{
	require './classes/database.php';

	$result = $con->prepare("INSERT INTO answers (id, answer_grade, answer_positive, answer_negative) VALUES (NULL, ?, ?, ?)");
	$result->bind_param('iss', intval($_POST[$radioButtonName]), trim(ucfirst($_POST[$positiveCommentsName])), trim(ucfirst($_POST[$negativeCommentsName])));
	$result->execute();
}
function insertAnswerIdIntoQuestionAnswersTable($lastAnswerId, $rowQuestion)
{
	require './classes/database.php';

	$result = $con->prepare("INSERT INTO question_answers (id, faculty_question_id, answer_id) VALUES (NULL, ?, ?)");
	$result->bind_param('ii', intval($rowQuestion['id']), intval($lastAnswerId));
	$result->execute();
}
function insertQuestionAnswerIdIntoProfessorQuestionAnswers($idProfessor, $idCourse,$lastQuestionAnswerId)
{
	require './classes/database.php';

	$result = $con->prepare("INSERT INTO professor_question_answers (id, professor_id, course_id, question_answer_id) VALUES (NULL, ?, ?, ?)");
	$result->bind_param('iii', intval($idProfessor), intval($idCourse), intval($lastQuestionAnswerId));
	$result->execute();
}
function insertDataOnFeedbackReceivedProfessorTable()
{
	require './classes/database.php';

	$result = $con->prepare("INSERT INTO feedback_received_professors (id, student_id, professor_id, course_id) VALUES (NULL, ?, ?, ?);");
	$result->bind_param('iii', intval($_SESSION['idStudent']), intval($_SESSION['professorIdStudent']), intval($_SESSION['courseIdStudent']));
	$result->execute();
}
function getAnswers()
{
	require './classes/database.php';

	$resultQuestion = $con->prepare("select fq.id, q.question_text from faculty_questions fq join questions q on fq.question_id=q.id where faculty_id=?");
	$resultQuestion->bind_param('i', $_SESSION['facultyIdStudent']);
	$resultQuestion->execute();

	$stmtQuestion = $resultQuestion->get_result();
	while ($rowQuestion = mysqli_fetch_array($stmtQuestion, MYSQLI_ASSOC))
	{
		$radioButtonName = "progress".$rowQuestion['id'];
		$positiveCommentsName = "positive".$rowQuestion['id'];
		$negativeCommentsName = "negative".$rowQuestion['id']; 
		insertAnswerInAnswersTable($radioButtonName, $positiveCommentsName, $negativeCommentsName);
		$lastAnswerId = getLastId("select max(id) from answers");
		insertAnswerIdIntoQuestionAnswersTable($lastAnswerId, $rowQuestion);
		$lastQuestionAnswerId = getLastId("select max(id) from question_answers");
		insertQuestionAnswerIdIntoProfessorQuestionAnswers($_SESSION['professorIdStudent'], $_SESSION['courseIdStudent'], $lastQuestionAnswerId);	
	}
}

if(isset($_POST['feedbackCompleted']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	if(!isItemsEmpty())
	{
		$_SESSION['radioButtonEmpty'] = true;
		header('Location: ./questionsForFeedback-student');
	}
	else
	{
		getAnswers();
		insertDataOnFeedbackReceivedProfessorTable();
		header('Location: ./chooseProfessor-student');
	}

	die();
}
class QuestionsForFeedback extends Controller {

}

?>