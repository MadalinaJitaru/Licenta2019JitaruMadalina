<?php 
include './components/style/leafLeft/leafLeft.php';
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
<div class="leafLeft">
	<?php include './components/user/logoutLeft/logoutLeft.php';?>
	<?php include './components/professor/feedbackResultsTitle/feedbackResultsTitle.php';?>
	<?php include './components/professor/feedbackResults/feedbackResults.php';?>
	<?php include './components/professor/feedbackResultsButton/feedbackResultsButton.php';?> 
</div>