<?php
//config data for path
$base_url   = 'http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], "/"));
//config data for content
$page_title =  'MEDDGO';
$site_name  =  'MEDDGO';
$max_username_length = '12';

$st_num_item_list       = 4;
$msg_num_item_list      = 5;

//Upload profile pic info
$max_file = "2297152"; 						// Approx 2MB
$max_width = "400";							// Max width allowed for the large image
$max_photo_width = "650";							// Max width allowed for the large image
$thumb_width = "100";						// Width of thumbnail image
$thumb_height = "100";						// Height of thumbnail image

?>
