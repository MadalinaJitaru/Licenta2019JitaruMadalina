<?php
include './components/style/leafLeft/leafLeft.php';
if (session_status() == PHP_SESSION_NONE) 
{
	session_start();
}
?>
<div class="leafLeft">
	<?php include './components/user/logoutLeft/logoutLeft.php';?>
	<?php include './components/student/questionsForFeedbackTitle/questionsForFeedbackTitle.php';?>
	<form action="./questionsForFeedback-student" method="post">
		<?php include './components/student/questionForFeedback/questionForFeedback.php';?>
		<?php include './components/student/questionsForFeedbackSendButton/questionsForFeedbackSendButton.php';?> 
	</form>
	<?php  include './components/student/questionsForFeedbackBackButton/questionsForFeedbackBackButton.php';?>
</div>