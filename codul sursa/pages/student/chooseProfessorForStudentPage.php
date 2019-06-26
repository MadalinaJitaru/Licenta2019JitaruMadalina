<?php 
include './components/style/leafRight/leafRight.php';
if (session_status() == PHP_SESSION_NONE) 
{
	session_start();
}
if (!isset($_SESSION["validStudent"]) || !$_SESSION["validStudent"]) 
{
	header("Location: moreInformation-student");
	die();
}
?>
<div class="leafRight">
	<?php include './components/user/logoutRight/logoutRight.php';?>
	<?php include './components/student/chooseProfessorTitle/chooseProfessorTitle.php';?>
	<form action="./chooseProfessor-student" method="post">
		<?php include './components/user/chooseProfessor/chooseProfessor.php';?>
		<?php include './components/student/chooseProfessorNextButton/chooseProfessorNextButton.php';?> 
	</form>
</div>