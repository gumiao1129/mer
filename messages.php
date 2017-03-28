<?php
session_start();
error_reporting (0);
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

$miss_message='';
$limited = '';

if(isset($_POST['pmSubmit']))
{
    unset($_POST['pmSubmit']);
    $pmSubject=trim(mysql_real_escape_string($_POST['pmSubject'] ));
    $pmTextArea=trim(mysql_real_escape_string($_POST['pmTextArea'] ));
    
    $model = new CI_model();
    $table = "private_messages";
    if($pmSubject!=null||$pmTextArea!=null)
    {
        if(isset($_SESSION['patient_id']))
        {
             $patient_id=$_SESSION['patient_id'];
             $columns = "from_id, from_id_cate, subject, message, create_date_time,senderDelete";
             $content = "'$patient_id', 'patient', '$pmSubject', '$pmTextArea', NOW(), '0'";
             $model->dbinsert($table, $columns, $content);

             $columns = "message_id";
             $content = "from_id = '$patient_id' AND from_id_cate = 'patient' ORDER BY message_id DESC LIMIT 1";
             $result = $model->dbSelect($table, $columns, $content);
        }
        else if(isset($_SESSION['physician_id']))
        {
             $physician_id=$_SESSION['physician_id'];
             $columns = "from_id, from_id_cate, subject, message, create_date_time,senderDelete";
             $content = "'$physician_id', 'physician', '$pmSubject', '$pmTextArea', NOW(), '0'";
             $model->dbinsert($table, $columns, $content);

             $columns = "message_id";
             $content = "from_id = '$physician_id' AND from_id_cate = 'physician' ORDER BY message_id DESC LIMIT 1";
             $result = $model->dbSelect($table, $columns, $content);  
        }
    
        $message_id = $result["message_id"];

        $table = "message_recipient";
                        
        foreach ($_POST as $key=>$value) 
        {
            if (preg_match("/newInput_/", $key))
            {
                $newInputKey = explode("_", $key);
                
                
                
                if($newInputKey[1]=="physician")
                {
                    $columns = "message_id, to_id, to_id_cate, opened, recipientDelete, replayed";
                    $content = "$message_id, '$newInputKey[2]', '$newInputKey[1]', '0', '0','0'";
                    $model->dbinsert($table, $columns, $content);
                }
                else if($newInputKey[1]=="patient")
                {
                    $columns = "message_id, to_id, to_id_cate, opened, recipientDelete, replayed";
                    $content = "$message_id, '$newInputKey[2]', '$newInputKey[1]', '0', '0', '0'";
                    $model->dbinsert($table, $columns, $content);
                }

            }
        }
    header("Location: messages.php");
    }
    else
    {
        $miss_message=$lang_miss_message;
    }
}

$search_keys = "";

$message_tag_TBS = new CI_model_search_engine();
$to_id="";
$to_id_cate="";

if(isset($_SESSION['patient_id']))
{
    $to_id=$_SESSION['patient_id'];
    $to_id_cate='patient';
}
else if(isset($_SESSION['physician_id']))
{
    $to_id=$_SESSION['physician_id'];
    $to_id_cate= 'physician';
}

$limited = "LIMIT $msg_num_item_list";
$limit_msg = "";
//$limit_msg = "AND private_messages.message_id ";

$activity = $message_tag_TBS->search_messages($to_id, $to_id_cate, $limited, $limit_msg);
$msg_id = $activity[$msg_num_item_list-1]["message_id"];

foreach ($activity as &$value) {
   $value['message'] = str_replace("\r\n",'',$value['message'] );
   $value['message'] = str_replace("\n",'',$value['message'] );
}


//$activity = $message_tag_TBS->search_messages($search_table2, $define_id, $from_field_id, $from_id);



//Go to home page
            $template 		= "views/main_1.htm";
            $inner_template1 	= "views/nav_main_manu.html";
        if(isset($_GET["sendTo"])&& ($_GET["sendTo"]=="pa"))
        {
            $inner_template3    =  "views/message_layout_pa.html";
        }
        else if (isset($_GET["sendTo"])&& ($_GET["sendTo"]=="dr"))
        {
            $inner_template3    =  "views/message_layout.html";
        }
        else
        {
            $inner_template3    =  "views/message_layout.html";
        }
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
    
    $CurrRec['message_details'] = "message_details.php?width=400&modal=true&profile_img=$CurrRec[thumbnail_pic_name]&firstname=$CurrRec[firstname]&lastname=$CurrRec[lastname]&message_id=$CurrRec[message_id]&message_recipient_id=$CurrRec[id]";
} 
     
            @mysql_close();
            die();

           
?>
