<?php 
$con = mysqli_connect("localhost","root","");
mysqli_select_db($con, "feedback");
if (!$con->set_charset("utf8")) {
	printf("Error loading character set utf8: %s\n", $con->error);
}

?>