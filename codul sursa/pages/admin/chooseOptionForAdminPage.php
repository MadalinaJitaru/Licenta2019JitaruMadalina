<?php 
include './components/style/leftSkewed/leftSkewed.php';
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
<div class="leftSkewed">
	<?php include './components/user/logoutLeft/logoutLeft.php';?>
	<?php include './components/admin/chooseOptionTitle/chooseOptionTitle.php';?>
	<?php include './components/admin/chooseOptionCrud/chooseOptionCrud.php';?> 
	<?php include './components/admin/chooseOptionDelimiter/chooseOptionDelimiter.php';?> 
	<?php include './components/admin/chooseOptionFeedbackView/chooseOptionFeedbackView.php';?>
</div>

