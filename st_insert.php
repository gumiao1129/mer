<?php
session_start();
error_reporting (0);
include_once('classes/config.php');
include_once('classes/sessions.php');
include_once('classes/tbs_class_php5.php');
include_once('lang/en.php');
include_once('configData.php');     
include_once('classes/validation.php');     
include_once('models/CI_model.php'); 
include_once('classes/random_code.php'); 

//$screeningtool_name = 'PASQ';
//$screeningtool_full_name = 'PSORIATIC ARTHRITIS SCREENING QUESTIONNAIRE';
//$screeningtool_name_md5 = md5($screeningtool_name);
//$specialist = 'Rheumatology';
//$st_icon_location = 'addons/pasq/lib/img/pasq_icon.png'; 

//$screeningtool_name = 'HAQ';
//$screeningtool_full_name = 'HEALTH ASSESSMENT QUESTIONNAIRE';
//$screeningtool_name_md5 = md5($screeningtool_name);
//$specialist = 'Rheumatology';
//$st_icon_location = 'addons/haq/lib/img/haq_icon.png';    

//$screeningtool_name = 'DAS';
//$screeningtool_full_name = 'DISEASE ACTIVITY SCORE';
//$screeningtool_name_md5 = md5($screeningtool_name);
//$specialist = 'Rheumatology';
//$st_icon_location = '';    

$screeningtool_name = 'RASQ';
$screeningtool_full_name = strtoupper('Rheumatoid Arthritis Screening Questionnaire');
$screeningtool_name_md5 = md5($screeningtool_name);
$specialist = 'Rheumatology';
$st_icon_location = 'addons/rasq/lib/img/rasq_icon.png';    

    $stInfo = new CI_model();
    $table = "st_category";
    $columns = "screeningtool_name, screeningtool_full_name, screeningtool_name_md5, create_date_time, specialist, st_icon_location";
    $content = "'$screeningtool_name', '$screeningtool_full_name', '$screeningtool_name_md5', NOW(), '$specialist', '$st_icon_location' ";
    $stInfo->dbinsert($table, $columns, $content);


?>
