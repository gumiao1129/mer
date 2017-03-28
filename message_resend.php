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


$model = new CI_model();
$table = "private_messages";

if(isset($_POST["replyTextArea"]))
{
    $replyTextArea = trim(mysql_real_escape_string($_POST["replyTextArea"])); 
    $message_subject = trim(mysql_real_escape_string($_POST["message_subject"]));
}

if(isset($_SESSION['patient_id']))
{
     $patient_id=trim(mysql_real_escape_string($_SESSION['patient_id']));
     $columns = "from_id, from_id_cate, subject, message, create_date_time,senderDelete";
     $content = "'$patient_id', 'patient', '$message_subject', '$replyTextArea', NOW(), '0'";
     $model->dbinsert($table, $columns, $content);

     $columns = "message_id";
     $content = "from_id = '$patient_id' AND from_id_cate = 'patient' ORDER BY message_id DESC LIMIT 1";
     $result = $model->dbSelect($table, $columns, $content);
}
if(isset($_SESSION['physician_id']))
{
     $physician_id=trim(mysql_real_escape_string($_SESSION['physician_id']));
     $columns = "from_id, from_id_cate, subject, message, create_date_time,senderDelete";
     $content = "'$physician_id', 'physician', '$message_subject', '$replyTextArea', NOW(), '0'";
     $model->dbinsert($table, $columns, $content);

     $columns = "message_id";
     $content = "from_id = '$physician_id' AND from_id_cate = 'physician' ORDER BY message_id DESC LIMIT 1";
     $result = $model->dbSelect($table, $columns, $content);  
}

        $message_id = trim(mysql_real_escape_string($result["message_id"]));

        $table = "message_recipient";
        $message_details = $_SESSION['message_details'];
        unset($_SESSION['message_details']);
        if($message_id!=null)
        {
            foreach ($message_details as $key=>$value) 
            {
                if($value["to_id_cate"]=="physician")
                {
                    $to_id = $value["to_id"];
                    $to_id_cate = $value["to_id_cate"];
                    $columns = "message_id, to_id, to_id_cate, opened, recipientDelete, replayed";
                    $content = "$message_id, '$to_id', '$to_id_cate', '0', '0','0'";
                    $model->dbinsert($table, $columns, $content);
                }
                else if($value["to_id_cate"]=="patient")
                {
                    $to_id = $value["to_id"];
                    $to_id_cate = $value["to_id_cate"];
                    $columns = "message_id, to_id, to_id_cate, opened, recipientDelete, replayed";
                    $content = "$message_id, '$to_id', '$to_id_cate', '0', '0','0'";
                    $model->dbinsert($table, $columns, $content);
                }
            }
        }

?>
