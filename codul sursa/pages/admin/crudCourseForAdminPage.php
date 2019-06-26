<?php
include './components/style/leafLeft/leafLeft.php';
if (session_status() == PHP_SESSION_NONE) 
{
	session_start();
}
?>
<div class="leafLeft">
	<?php include './components/user/logoutLeft/logoutLeft.php';?>
	<?php include './components/admin/crudEntityTitle/crudEntityTitle.php';?>
	<?php include './components/admin/crudEntity/crudEntity.php';?>
	<?php include './components/admin/crudEntityBackButton/crudEntityBackButton.php';?>
</div>