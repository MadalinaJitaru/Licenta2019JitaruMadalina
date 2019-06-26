<?php 
function getStudentFacultyId($groupIdStudent)
{
	require './classes/database.php';

	$result = $con->prepare("select * from groups where id = ?");
	$result->bind_param('i', $groupIdStudent);
	$result->execute();

	$stmt = $result->get_result();
	$row = mysqli_fetch_array($stmt, MYSQLI_ASSOC);

	return $row['faculty_id'];
} 
function getOutputForProgressBarBody($output, $rowQuestion)
{
	$output .= '<div class="progressBarBody">
	<div id="myBar'.$rowQuestion['id'].'" class="progressBar"></div>
	</div>
	<br>
	<div class="equalSpace">
	<p>1</p>
	<p>2</p>
	<p>3</p>
	<p>4</p>
	<p>5</p>
	</div>';

	return $output;
}
function getOutputForRadioButton($output, $rowQuestion, $radioButtonValue, $indexRadioButton)
{
	$output .= '<label class="radioButtonContainer"onclick="move('.$indexRadioButton.', \'myBar'.$rowQuestion['id'].'\')">
	<input type="radio" name="progress'.$rowQuestion['id'].'" value="'.$radioButtonValue.'"/>
	<span class="gradeRadioButton" ></span>
	</label>';
	return $output;
}
function getOutputForRadioButtons($output, $rowQuestion)
{
	$output .= '<div class="equalSpace">';
	$output = getOutputForRadioButton($output, $rowQuestion, 1,  2);
	$output = getOutputForRadioButton($output, $rowQuestion, 2,  25);
	$output = getOutputForRadioButton($output, $rowQuestion, 3, 50);
	$output = getOutputForRadioButton($output, $rowQuestion, 4, 75);
	$output = getOutputForRadioButton($output, $rowQuestion, 5, 100);
	$output .= '</div>';

	return $output;
}
function getOutputForComments($output, $rowQuestion)
{
	$output .= '<div class="commentsContainer">
	<p>Alte comentarii:</p>
	<div class="textareaContainer">
	<textarea name="positive'.$rowQuestion['id'].'" placeholder="+ lucruri pozitive"></textarea>
	<textarea name="negative'.$rowQuestion['id'].'" placeholder="- lucruri negative"></textarea>
	</div>
	</div> 
	</div>';
	return $output;
}
function getOutputForQuestion($rowQuestion, $output, $indexQuestion)
{
	$output .= '<div class="question">
	<p>'.$indexQuestion.'. '.$rowQuestion['question_text'].'</p>';
	$output = getOutputForProgressBarBody($output, $rowQuestion);
	$output = getOutputForRadioButtons($output, $rowQuestion);
	$output = getOutputForComments($output, $rowQuestion);

	return $output;
}
function getQuestions($facultyIdStudent)
{
	$output = '';
	require './classes/database.php';
	$indexQuestion = 0;
	
	$resultQuestion = $con->prepare("select fq.id, q.question_text from faculty_questions fq join questions q on fq.question_id=q.id where faculty_id=?");
	$resultQuestion->bind_param('i', $facultyIdStudent);
	$resultQuestion->execute();

	$stmtQuestion = $resultQuestion->get_result();
	while ($rowQuestion = mysqli_fetch_array($stmtQuestion, MYSQLI_ASSOC))
	{
		$indexQuestion += 1;
		$output = getOutputForQuestion($rowQuestion, $output, $indexQuestion);
	}
	return $output;
}

function questionsfetchDataStudent()
{
	$groupIdStudent = $_SESSION['groupIdStudent'];
	$facultyIdStudent = getStudentFacultyId($groupIdStudent);
	$_SESSION['facultyIdStudent'] = $facultyIdStudent;
	return getQuestions($facultyIdStudent);
}
?>