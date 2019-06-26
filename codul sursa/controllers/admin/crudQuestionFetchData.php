<?php
if(isset($_POST['editQuestionId']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}
	$_SESSION['questionIdForEdit'] = $_POST['editQuestionId'];
	echo updateQuestionFetchData();
}
function requireDatabaseForQuestion()
{
	if(isset($_POST['editQuestionId']))
	{
		require '../../classes/database.php';			
	}
	else
	{
		require './classes/database.php';
	}
	return $con;
}
function getFacultyIdForEditQuestion()
{
	$con = requireDatabaseForQuestion();

	$result = $con->prepare("select * from faculty_professors where professor_id = ?");
	$result->bind_param('i', $_SESSION['idProfessor']);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['faculty_id'];
}
function fetchDataQuestionForUpdate($questionId)
{
	$facultyId = getFacultyIdForEditQuestion();

	$con = requireDatabaseForQuestion();

	$result = $con->prepare("select * from questions where id=?");
	$result->bind_param('i', $questionId);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);
	return $row;
}
function readQuestionFetchData()
{
	$output = '
	<span class="container" style="cursor:default">
	<div class="gridContainerQuestion">
	<div class="gridItem">Nr.</div>
	<div class="gridItem">Intrebarea</div>
	</div>
	</span>';
	$indexQuestion = 0;
	$facultyId = getFacultyIdForEditQuestion();

	require './classes/database.php';

	$result = $con->prepare('select q.id, q.question_text from questions q join faculty_questions fq on fq.question_id=q.id where fq.faculty_id=?');
	$result->bind_param('i', $facultyId);
	$result->execute();

	$stmt = $result->get_result();
	while ($row = mysqli_fetch_array($stmt, MYSQLI_ASSOC))
	{
		$indexQuestion += 1;
		$output .= '
		<label onclick="hideElement(\'optionError\')" for="line'.$indexQuestion.'" class="container">';
		if(isset($_SESSION['questionIdForEdit']) && $row['id'] == $_SESSION['questionIdForEdit'])
		{
			$output .= '<input checked id="line'.$indexQuestion.'" type="radio" name="question" value="'.$row['id'].'">';	
		}
		else
		{
			$output .= '<input id="line'.$indexQuestion.'" type="radio" name="question" value="'.$row['id'].'">';	
		}
		$output .= '
		<div class="gridContainerQuestion">
		<div class="gridItem">'.$indexQuestion.'. </div>
		<div class="gridItem">'.ucfirst($row["question_text"]).'</div>
		</div>
		</label>';
	}
	return $output;
}
function createQuestionFetchData()
{
	$output = '
	<h1>Adaugare intrebare</h1>
	<form action="./controllers/admin/crudQuestion.php" method="post">
	<div class="crudPopUpContainer">
	<div class="crudInputContainer">
	<label for="questionTextAdd">Intrebarea</label>
	<textarea type="text" name="questionTextAdd" id="questionTextAdd" placeholder="Introduceti intrebarea"></textarea>
	<br>
	</div>
	</div>
	<div class="crudEntityButton">
	<button type="submit" name="createQuestion">Adauga</button>
	</div>
	</form>';
	return $output;
}
function updateQuestionFetchData()
{
	$row = fetchDataQuestionForUpdate($_SESSION['questionIdForEdit']);
	$output = '
	<h1>Modificare intrebare</h1>
	<form action="./controllers/admin/crudQuestion.php" method="post">
	<div class="crudPopUpContainer">
	<div class="crudInputContainer">
	<label for="questionTextEdit">Intrebarea</label>
	<textarea type="text" name="questionTextEdit" id="questionTextEdit" placeholder="Introduceti intrebarea">'.$row["question_text"].'</textarea>
	</div>
	</div>
	<div class="crudEntityButton">
	<button type="submit" name="updateQuestion">Modifica</button>
	</div>
	</form>
	';
	return $output;
}
?>