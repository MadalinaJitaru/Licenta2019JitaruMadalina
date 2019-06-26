<?php

if(isset($_POST['feedbackLoading']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}
	if(!isset($_POST['professor']))
	{
		$_SESSION['invalidData'] = true;
		header('Location: ./chooseProfessor-student');
	}
	else
	{	
		$ids = explode(' ', $_POST['professor']);
		$_SESSION['courseIdStudent'] = $ids[0];
		$_SESSION['professorIdStudent'] = $ids[1];
		header('Location: ./questionsForFeedback-student');
	}
	
	die();
}
class ChooseProfessorForStudent extends Controller {
}
?>