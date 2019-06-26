<style>
	<?php include 'feedbackResultsButton.css';?>
</style>
<div class="feedbackResultsButtonForProfessor">
	<?php 
	if (isset($_SESSION['moreCourses']) && $_SESSION['moreCourses']) 
	{
		echo '<a href="chooseProfessor-professor"><button>Inapoi</button></a>';   
	}
	if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']) 
	{
		echo '<a href="chooseProfessor-admin"><button>Inapoi</button></a>';   
	}
	?>
</div>