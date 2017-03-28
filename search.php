<?php
session_start();
//error_reporting (0);
include_once('classes/config.php');
include_once('classes/sessions.php');
include_once('classes/tbs_class_php5.php');
include_once('lang/en.php');
include_once('side_nav.php');
include_once('configData.php');     
include_once('classes/validation.php');     
include_once('models/CI_model.php'); 
include_once('models/CI_model_TBS.php'); 
include_once('models/CI_model_search_engine.php'); 
include_once('classes/random_code.php'); 

//$_SESSION['patient_id']=1;

$search_keys = strtolower($_GET["q"]);
if (!$search_keys) return;



    $info = new CI_model_search_engine();                    
    if(isset($_SESSION['patient_id']))
    {
        $patient_id = $_SESSION['patient_id'];

        
         $search_table1 = "physician";
         $search_table_left_join="physician_patient_relationships";
         $search_table_session_id_field = "patient_id_one";
         $search_table_left_join_id_field = "physician_id_one";
         $session_id = $_SESSION['patient_id'];
         $search_field_array = array('physician.lastname', 'physician.firstname');
         $id_field = "physician_id";
         $items = $info->search_engine($search_keys, $search_table1, $search_table_left_join, $search_table_left_join_id_field, $search_field_array, $id_field, $session_id,$search_table_session_id_field);         
    }

     if(isset($_SESSION['physician_id']))
     {
        $search_table1 = "physician";
        
        $search_table_left_join="physician_physician_relationships";
        $search_table_left_join_id_field = "physician_id_two";
        $search_table_session_id_field = "physician_id_one";
        $session_id = $_SESSION['physician_id'];
        $search_field_array = array('physician.lastname', 'physician.firstname');
        $id_field = "physician_id";
        $items = $info->search_engine_without_itself($search_keys, $search_table1, $search_table_left_join, $search_table_left_join_id_field, $search_field_array, $id_field, $session_id,$search_table_session_id_field);          
     }


     
    foreach ($items as $key=>$value) {
        echo "$value[firstname] $value[lastname]|$value[thumbnail_pic_name]|$value[physician_id]|physician\n";
    }

?>