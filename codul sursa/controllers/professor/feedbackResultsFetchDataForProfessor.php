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
function getAverage($rowQuestion)
{
	require './classes/database.php';
	
	$resultGrade = $con->prepare("select round(AVG(a.answer_grade), 2) as 'average' from professor_question_answers pqa join question_answers qa on pqa.question_answer_id=qa.id join answers a on a.id=qa.answer_id where pqa.professor_id=? and pqa.course_id=? and qa.faculty_question_id=?");
	$resultGrade->bind_param('iii', $_SESSION['professorIdForProfessor'], $_SESSION['courseIdForProfessor'], $rowQuestion['id']);
	$resultGrade->execute();

	$stmtGrade = $resultGrade->get_result();
	$rowGrade = mysqli_fetch_array($stmtGrade, MYSQLI_ASSOC);
	
	return $rowGrade['average'];
}
function isAverageDifferentThanMargins($rowQuestion)
{
	if( getAverage($rowQuestion) == 1.00 or 
		getAverage($rowQuestion) == 2.00 or
		getAverage($rowQuestion) == 3.00 or
		getAverage($rowQuestion) == 4.00 or
		getAverage($rowQuestion) == 5.00 )
	{
		return false;
	}  
	return true;
}
function getOutputForProgressBarBody($output, $rowQuestion)
{
	$average = getAverage($rowQuestion);
	$resultStyleAverage =  ($average - 1)*25 - 1;
	$resultFeedback = 100 - $resultStyleAverage - 1;

	if($resultFeedback == 100)
	{
		$resultFeedback = 99;
	}
	$output .= '<div class="containerProgressBar">
	<div class="roundCorners">
	<div class="progressBarBody">
	<div id="myBar" class="progressBar" style="width:'.$resultFeedback.'%"></div>
	</div>
	</div>
	</div>
	<div class="gradesContainerProfessor">
	<div class="margins">
	<p>1</p>
	<p>2</p>
	<p>3</p>
	<p>4</p>
	<p>5</p>
	</div>';
	if(isAverageDifferentThanMargins($rowQuestion))
	{
		$output .= '<div class="rateContainer">
		<p style="left: '.$resultStyleAverage.'%">';
		$output .= $average.'</p>
		</div>';		
	}
	$output .= '</div>';

	return $output;
}
function getOutputForCommentsFromDatabase($output, $rowQuestion)
{
	require './classes/database.php';
	$outputPositive = '';
	$outputNegative = '';
	
	$resultComment = $con->prepare("select * from professor_question_answers pqa join question_answers qa on pqa.question_answer_id=qa.id join answers a on a.id=qa.answer_id where pqa.professor_id=? and pqa.course_id=? and qa.faculty_question_id=?");
	$resultComment->bind_param('iii', $_SESSION['professorIdForProfessor'], $_SESSION['courseIdForProfessor'], $rowQuestion['id']);
	$resultComment->execute();

	$stmtComment = $resultComment->get_result();
	while ($rowComment = mysqli_fetch_array($stmtComment, MYSQLI_ASSOC))
	{
		if($rowComment['answer_positive'] != '')
		{
			$outputPositive .= ucfirst($rowComment['answer_positive']).'</br>';			
		}
		if($rowComment['answer_negative'] != '')
		{
			$outputNegative .= ucfirst($rowComment['answer_negative']).'</br>';			
		}
	}
	if($outputNegative == '' and $outputPositive == '')
	{
		$output .= 'Nu sunt comentarii!';
	}
	else
	{
		$output .= ucfirst($outputNegative).'</br>'.ucfirst($outputPositive);
	}
	return $output;
}
function getOutputForComments($output, $rowQuestion)
{
	$output .= '<div class="commentsContainer">
	<div class="dropdown">
	<p>Comentarii</p>
	<img src="./components/professor/feedbackResults/arrowDown.svg"/>
	<div class="dropdownContainer">
	<h1>';
	$output = getOutputForCommentsFromDatabase($output, $rowQuestion);
	$output .= '</h1>
	</div>
	</div>
	</div>';
	return $output;
}
function getOutputForQuestion($rowQuestion, $output, $indexQuestion)
{
	$output .= '<div class="result">
	<p>'.$indexQuestion.'. '.$rowQuestion['question_text'].'</p>';
	$output = getOutputForProgressBarBody($output, $rowQuestion);
	$output .= '</div>';
	$output = getOutputForComments($output, $rowQuestion);

	return $output;
}
function getQuestions($facultyId)
{
	$output = '';
	require './classes/database.php';
	$indexQuestion = 0;
	
	$resultQuestion = $con->prepare("select fq.id, q.question_text from faculty_questions fq join questions q on fq.question_id=q.id where faculty_id=?");
	$resultQuestion->bind_param('i', $facultyId);
	$resultQuestion->execute();

	$stmtQuestion = $resultQuestion->get_result();
	while ($rowQuestion = mysqli_fetch_array($stmtQuestion, MYSQLI_ASSOC))
	{
		$indexQuestion += 1;
		$output = getOutputForQuestion($rowQuestion, $output, $indexQuestion);
	}
	return $output;
}

function feedbackResultsfetchDataForProfessor()
{	
	$facultyIdProfessor = getFacultyId();

	return getQuestions($facultyIdProfessor);
}
?>