<?php 
include './components/style/leafRight/leafRight.php';
if (session_status() == PHP_SESSION_NONE) 
{
	session_start();
}
if (!isset($_SESSION["loggedProfessor"]) || !$_SESSION["loggedProfessor"]) 
{
	header("Location: login-professor");
	die();
}
if (isset($_SESSION["oneCourse"]) && $_SESSION["oneCourse"]) 
{
	header("Location: feedbackResults-professor");
	die();
}
?>
<div class="leafRight">
	<?php include './components/user/logoutRight/logoutRight.php';?>
	<?php include './components/professor/chooseProfessorTitle/chooseProfessorTitle.php';?>
	<form action="./chooseProfessor-professor" method="post">
		<?php include './components/user/chooseProfessor/chooseProfessor.php';?>
		<?php include './components/professor/chooseProfessorButton/chooseProfessorButton.php';?> 
	</form>
</div>