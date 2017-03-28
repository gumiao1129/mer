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

$error_message  ='';

if(isset($_POST)&&( isset($_SESSION['patient_id']) || isset($_SESSION['physician_id']) ))
{
    if(isset($_POST["acount_password_edited"])&& $_POST["acount_password_edited"] == "yes")
    {
        $old_password = trim(mysql_real_escape_string($_POST['old_password']));
        $new_password = trim(mysql_real_escape_string($_POST['new_password']));
        $renew_password = trim(mysql_real_escape_string($_POST['renew_password']));
        
        //start validate all the fields
        $regValidation = new validation();
        
        //check password
        $check_old_password_empty = $regValidation->checkEmpty($old_password);
        $check_old_password_valid = $regValidation->checkValid($old_password);
        
        $check_new_password_empty = $regValidation->checkEmpty($new_password);
        $check_new_password_valid = $regValidation->checkValid($new_password);
        
        if($check_old_password_empty == 'pass' && $check_new_password_empty == 'pass')
        {
            //check confirmed password
            if($check_old_password_valid == 'pass' && $check_new_password_valid == 'pass')
            {
                if($new_password==$renew_password)
                {
                    $model = new CI_model();
                    $md_new_password = md5($new_password);
                    
                    if(isset($_SESSION['patient_id']))
                    {
                        $table = "patient";
                        $patient_id = $_SESSION['patient_id'];
                        $updateContent = "password = '$md_new_password' WHERE patient_id = $patient_id";
                        $model->dbUpdate($table, $updateContent);
                        
                        $procede = true;
                        echo "success";
                        
                    }
                    
                    if(isset($_SESSION['physician_id']))
                    {
                        $table = "physician";
                        $physician_id = $_SESSION['physician_id'];
                        
                        $updateContent = "password = '$md_new_password' WHERE physician_id = $physician_id";
                        $model->dbUpdate($table, $updateContent);
                        
                        $procede = true;
                        echo "success";
                    }

                }
                else
                {
                    $procede = false;
                    $error_message = $error_message.$lang_unconfirmedPassword;
                }
            }
            else
            {
                $procede = false;
                $error_message = $error_message.$lang_password.$lang_unmatch;
            }
        }
        else
        {
            $procede = false;
            $error_message = $error_message.$lang_password.$lang_fillEmpty;
        }
        echo $error_message;
        
        
    }
}




?>
