<?php
require_once("../content/header.php");
if(!isset($_SESSION["Client"])) header("Location: ../login.php");
$choice_group_screen = new Panel_group_choice_screen();
?>
<div class="big_contener">
	<?php $choice_group_screen->create_main_content(); ?>
	<?php	require_once("../content/footer.php"); ?>
</div>


