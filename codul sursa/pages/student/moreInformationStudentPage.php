<?php 
include './components/style/leftSkewed/leftSkewed.php';
if (session_status() == PHP_SESSION_NONE) {
	session_start();
}
if (!isset($_SESSION["loggedStudent"]) || !$_SESSION["loggedStudent"]) 
{
	header("Location: login-student");
	die();
}
if (isset($_SESSION["validStudent"]) && $_SESSION["validStudent"]) 
{
	header("Location: chooseProfessor-student");
	die();
}
?>
<div class="leftSkewed">
	<?php include './components/user/logoutLeft/logoutLeft.php';?>
	<?php include './components/student/moreInformationTitle/moreInformationTitle.php';?>  
	<?php include './components/student/moreInformationInput/moreInformationInput.php';?> 
</div>