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
?>
<div class="leafRight">
	<?php include './components/user/logoutRight/logoutRight.php';?>
	<?php include './components/admin/chooseProfessorTitle/chooseProfessorTitle.php';?>
	<?php include './components/admin/exportResultsFeedback/exportAllResults.php';?>
	<form action="./chooseProfessor-admin" method="post">
		<?php include './components/user/chooseProfessor/chooseProfessor.php';?>
		<?php include './components/admin/chooseProfessorButton/chooseProfessorButton.php';?>
	</form>
	<?php include './components/admin/chooseProfessorBackButton/chooseProfessorBackButton.php';?>
</div>