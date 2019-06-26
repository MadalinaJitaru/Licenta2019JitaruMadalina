<?php 
include './components/style/leafRight/leafRight.php';
if (session_status() == PHP_SESSION_NONE) 
{
	session_start();
}
?>
<div class="leafRight">
	<?php include './components/user/logoutRight/logoutRight.php';?>
	<?php include './components/admin/crudEntityTitle/crudEntityTitle.php';?>
	<?php include './components/admin/crudEntity/crudEntity.php';?>
	<?php include './components/admin/crudEntityBackButton/crudEntityBackButton.php';?>
</div>