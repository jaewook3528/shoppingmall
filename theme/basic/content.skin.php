<?php
if(!defined('_TUBEWEB_')) exit;
?>

<h2 class="pg_tit">
	<span><?php echo $tb['title']; ?></span>
	<p class="pg_nav">HOME<i>&gt;</i><?php echo $tb['title']; ?></p>
</h2> 

<?php 
echo get_view_thumbnail(conv_content($co["co_content"], 1, 0), 1000);
?>
