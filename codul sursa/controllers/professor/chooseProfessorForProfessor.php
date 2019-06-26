<?php
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
if(isset($_POST['feedbackResults']))
{
	if (session_status() == PHP_SESSION_NONE) 
	{
		session_start();
	}
	if(!isset($_POST['professor']))
	{
		$_SESSION['invalidData'] = true;
		header('Location: ./chooseProfessor-professor');
		
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
		header('Location: ./feedbackResults-professor');

	}
	
	die();
}
class ChooseProfessorForProfessor extends Controller {
}
?>