<?php
function emptyInput($input)
{
	if(empty($input))
	{
		return false;
	}
	return true;
}
function getFacultyIdForQuestion()
{
	require '../../classes/database.php';

	$result = $con->prepare("select * from faculty_professors where professor_id = ?");
	$result->bind_param('i', $_SESSION['idProfessor']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['faculty_id'];
} 
function setQuestionVariablesOnFalse($crudMethod)
{
	if($crudMethod == "edit")
	{
		$_SESSION['dataUpdadeEntityIsValid'] = false;		
	}
	elseif ($crudMethod == "add") 
	{
		$_SESSION['dataCreateEntityIsValid'] = false;		
	}
}
function getLastIdQuestion()
{
	require '../../classes/database.php';

	$facultyId = getFacultyIdForQuestion();

	$result = $con->prepare("select max(id) from questions");
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);	

	return $row['max(id)'];
}
function addReferencesToFacultyQuestions($idQuestion)
{
	require '../../classes/database.php';

	$facultyId = getFacultyIdForQuestion();

	$resultFacultyQuestions = $con->prepare("insert into faculty_questions (id, faculty_id, question_id) VALUES (NULL, ?, ?);");
	$resultFacultyQuestions->bind_param('ii', $facultyId, $idQuestion);
	$resultFacultyQuestions->execute();
}
function addQuestion($questionTextAdd)
{
	require '../../classes/database.php';

	$result = $con->prepare("insert into questions (id, question_text) VALUES (NULL, ?);");
	$result->bind_param('s', $questionTextAdd);
	$result->execute();

	$lastIdQuestion = getLastIdQuestion();
	addReferencesToFacultyQuestions($lastIdQuestion);
}
function editQuestion($questionText)
{
	require '../../classes/database.php';

	$result = $con->prepare("update questions set question_text = ? where questions.id = ?;");
	$result->bind_param('si', $questionText, $_SESSION['questionIdForEdit']);
	$result->execute();
}
function redirectionAdminIfCrudQuestionDataIsOrNotCorrect($questionText, $crudMethod)
{
	require '../../classes/database.php';

	$facultyId = getFacultyIdForQuestion();

	$result = $con->prepare("select count(q.id) from questions q join faculty_questions fq on fq.question_id=q.id where q.question_text = ? and fq.faculty_id = ?");
	$result->bind_param('si', $questionText, $facultyId);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	if ($crudMethod == "edit") 
	{
		editQuestion($questionText);
		header('Location: ../../crudQuestion-admin');
	}
	if ($crudMethod == "add") 
	{
		if($row['count(id)'] >= 1)
		{
			$_SESSION['sameDataEntity'] = true;
			header('Location: ../../crudQuestion-admin');
		}
		else
		{
			addQuestion($questionText);
			header('Location: ../../crudQuestion-admin');			
		}
	}
}
function redirectionAdminIfQuestionDataInputIsOrNotEmpty($questionText, $questionTextIsValid, $crudMethod)
{
	if($questionTextIsValid)
	{
		redirectionAdminIfCrudQuestionDataIsOrNotCorrect($questionText, $crudMethod);
	}
	else
	{
		setQuestionVariablesOnFalse($crudMethod);
		header('Location: ../../crudQuestion-admin');
	}
}
function createQuestion()
{
	require '../../classes/database.php';
	$crudMethod = "add";
	$questionTextAdd = trim(ucfirst($_POST['questionTextAdd']));

	$questionTextAddIsValid = true;

	//my sql injection
	$questionTextAdd = stripcslashes($questionTextAdd);
	$questionTextAdd = mysqli_real_escape_string($con, $questionTextAdd);

	$questionTextAddIsValid = emptyInput($questionTextAdd);

	redirectionAdminIfQuestionDataInputIsOrNotEmpty($questionTextAdd, $questionTextAddIsValid, $crudMethod);
}
function deleteQuestion()
{
	require '../../classes/database.php';

	$result = $con->prepare("delete from questions where id=?");
	$result->bind_param('i', intval($_POST['deleteQuestion']));
	$result->execute();

	header('Location: ../../crudQuestion-admin');	
}
function deleteAllQuestion()
{
	require '../../classes/database.php';

	$facultyId = getFacultyIdForQuestion();

	$result = $con->prepare("delete from questions where id in (select fq.question_id from faculty_questions fq where fq.faculty_id = ?)");
	$result->bind_param('i', $facultyId);
	$result->execute();

	header('Location: ../../crudQuestion-admin');	
}
function updateQuestion()
{
	require '../../classes/database.php';

	$crudMethod = "edit";

	$questionTextEdit = trim(ucfirst($_POST['questionTextEdit']));

	$questionTextEditIsValid = true;

	//my sql injection
	$questionTextEdit = stripcslashes($questionTextEdit);
	$questionTextEdit = mysqli_real_escape_string($con, $questionTextEdit);

	$questionTextEditIsValid = emptyInput($questionTextEdit);

	redirectionAdminIfQuestionDataInputIsOrNotEmpty($questionTextEdit, $questionTextEditIsValid, $crudMethod);
}
function importQuestion()
{
	require '../../classes/database.php';

	if($_FILES['file']['name'])
	{
		$fileName = explode(".", $_FILES['file']['name']);
		if($fileName[1] == 'csv')
		{
			$handle = fopen($_FILES['file']['tmp_name'], "r");
			$data = fgetcsv($handle);
			while($data = fgetcsv($handle))
			{
				$questionText = mysqli_real_escape_string($con, $data[0]);
				addQuestion($questionText);
			}
			fclose($handle);
			header('Location: ../../crudQuestion-admin');
		}
	}
}
function exportQuestion()
{
	require '../../classes/database.php';
	header('Content-Type: txt/csv; charset=utf-8');
	header('Content-Disposition: attachment; fileName=intrebari.csv');
	$output = fopen("php://output", "w");
	fputcsv($output, array('ID', 'Intrebare'));

	$facultyId = getFacultyIdForQuestion();

	$result = $con->prepare("select q.id, q.question_text from questions q join faculty_questions fq on fq.question_id=q.id where fq.faculty_id = ?");
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();

	while($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		fputcsv($output, $row);
	}
	fclose($output);
}
if(isset($_POST['createQuestion']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	createQuestion();
	die();
}
if(isset($_POST['deleteQuestion']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	deleteQuestion();
	die();
}
if(isset($_POST['deleteAllQuestion']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	deleteAllQuestion();
	die();
}
if(isset($_POST['updateQuestion']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	//print_r($_POST);

	updateQuestion();
	die();
}
if(isset($_POST['importQuestion']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	importQuestion();
	die();
}
if(isset($_POST['exportQuestion']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}

	exportQuestion();
	die();
}
class CrudQuestion extends Controller {
}
?>