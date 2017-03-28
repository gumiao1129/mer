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





$message_tag_TBS = new CI_model_search_engine();
$search_table1 = 'message_recipient';
$search_table2 = 'private_messages';
$define_id = 'message_id';
$id_field = 'id';
$from_field_id = 'from_id';
$from_field_cate = 'from_id_cate';

if(isset($_SESSION['patient_id']))
{
    $from_id=$_SESSION['patient_id'];
    $from_cate='patient';
}
else if(isset($_SESSION['physician_id']))
{
    $from_id=$_SESSION['physician_id'];
    $from_cate= 'physician';
}


$activity = $message_tag_TBS->search_messages_tag($search_table1, $search_table2, $define_id, $id_field, $from_field_id, $from_field_cate,$from_id, $from_cate);
foreach ($activity as &$value) {
   $value['message'] = str_replace("\r\n",'',$value['message'] );
}



            $template 		= "views/main_1.htm";
            $inner_template1 	= "views/nav_main_manu.html";
            $inner_template3    =  "views/sent_message_layout.html";
            $inner_message_sub    = "views/inner_message_sub.html";
            $TBS 			= new clsTinyButStrong;
            $TBS->NoErr 	= true;

            $TBS->LoadTemplate("$template");

            $TBS->Render 	= TBS_OUTPUT;

            $TBS->MergeBlock('activity',$activity);
            
            /*
            $search_table2 = 'private_messages'; 
            foreach ($activity as $col_value) 
            {
               $number=$message_tag_TBS->search_messages_member($search_table2, "message_id", $col_value['message_id']);
            } 
            $TBS->MergeBlock('number',$number);
             * 
             */
            $TBS->Show();
            
            
function read_unread($BlockName,&$CurrRec,$RecNum){
     if ($CurrRec['opened']!=null&&$CurrRec['opened']==0)
     {
         $CurrRec['level'] = "#E6E6E6";
     }
    else if($CurrRec['opened']!=null&&$CurrRec['opened']==1)
    {
        $CurrRec['level'] = "";
    }
    
    $CurrRec['message_details'] = "message_details.php?width=400&modal=true&profile_img=$CurrRec[thumbnail_pic_name]&firstname=$CurrRec[firstname]&lastname=$CurrRec[lastname]&message_id=$CurrRec[message_id]";
} 
            @mysql_close();
            die();
?>
