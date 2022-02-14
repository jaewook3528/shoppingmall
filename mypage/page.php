<?php
include_once("./admin_head.php");
?>

<div id="wrapper">
	<div id="snb">
		<?php 
		include_once($admin_snb_file);
		?>
	</div>
	<div id="content">
		<?php 
		include_once("./{$code}.php");
		?>
	</div>
</div>

<?php
include_once("./admin_tail.php");
?>