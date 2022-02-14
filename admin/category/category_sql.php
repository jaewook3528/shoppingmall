<?php
$category_table =
"CREATE TABLE IF NOT EXISTS `{$target_table}` (
  `index_no` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `catecode` varchar(15) NOT NULL,
  `upcate` varchar(12) NOT NULL,
  `catename` varchar(255) NOT NULL,
  `img_name` varchar(255) NOT NULL,
  `img_name_over` varchar(255) NOT NULL,
  `img_head` varchar(255) NOT NULL,
  `img_head_url` varchar(255) NOT NULL,
  `list_view` int(11) unsigned NOT NULL DEFAULT '0',
  `p_catecode` varchar(15) NOT NULL,
  `p_upcate` varchar(12) NOT NULL,
  `p_oper` enum('n','y') NOT NULL,
  `p_hide` tinyint(4) NOT NULL DEFAULT '0',
  `u_hide` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY  (`index_no`),
  KEY `catecode` (`catecode`,`upcate`),
  KEY `p_oper` (`p_oper`,`p_hide`,`u_hide`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
?>