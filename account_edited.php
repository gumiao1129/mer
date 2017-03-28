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
    if(isset($_POST["acount_usename_edited"])&& $_POST["acount_usename_edited"] == "yes")
    {
        $user_name = trim(mysql_real_escape_string($_POST['username_title']));
        $password  = md5(trim(mysql_real_escape_string($_POST['password_title'])));
        
        //start validate all the fields
        $regValidation = new validation();
          
        //check username
        $check_username_empty  = $regValidation->checkEmpty($user_name);
        $check_username_valid  = $regValidation->checkValid($user_name);
        $check_username_length = $regValidation->username($user_name, $max_username_length);
        
        if($check_username_empty == 'pass')
        {
            if($check_username_valid == 'pass')
            {
                if($check_username_length == 'pass')
                {
                    
                    $model = new CI_model();
                    
                    $table = "patient, physician";
                    $columns = "*";
                    $content = "patient.username = '$user_name' OR physician.username = '$user_name'";
                    $result = $model->dbSelect($table, $columns, $content);
                
                    if($result == null)
                    {
                        if(isset($_SESSION['patient_id']))
                        {
                            $table = "patient";
                            $patient_id = $_SESSION['patient_id'];
                            $content1 = "patient_id = '$patient_id'";
                            $result_check = $model->dbSelect($table, $columns, $content1);
                        
                            if($password==$result_check['password'])
                            {
                                 $updateContent = "username = '$user_name' WHERE patient_id = $patient_id";
                                 $model->dbUpdate($table, $updateContent);
                                 echo "success";
                            }
                            else
                            {
                                 $procede = false;
                                 $error_message = $error_message.$lang_username_not_match_password;
                            }
                        }
                        
                        if(isset($_SESSION['physician_id']))
                        {
                            $table = "physician";
                            $physician_id = $_SESSION['physician_id'];
                            $content1 = "physician_id = '$physician_id'";
                            $result_check = $model->dbSelect($table, $columns, $content1);
                        
                            if($password==$result_check['password'])
                            {
                                 $updateContent = "username = '$user_name' WHERE physician_id = $physician_id";
                                 $model->dbUpdate($table, $updateContent);
                                 echo "success";
                            }
                            else
                            {
                                 $procede = false;
                                 $error_message = $error_message.$lang_username_not_match_password;
                            }
                        }
                    }
                    else
                    {
                        $procede = false;
                        $error_message = $error_message.$lang_username_existed;
                    }
                    
                }
                else
                {
                    $procede = false;
                    $error_message = $error_message.$lang_user_name.$lang_usernameShortOrLong;
                }
            }
            else
            {
                $procede = false;
                $error_message = $error_message.$lang_user_name.$lang_unmatch;
            }
        }
        else
        {
            $procede = false;
            $error_message = $error_message.$lang_user_name.$lang_fillEmpty;
        }
        
        //check password
        $check_password_empty = $regValidation->checkEmpty($password);
        $check_password_valid= $regValidation->checkValid($password);
        if($check_password_empty == 'pass')
        {
            if($check_password_valid == 'pass')
            {
                $procede = true;
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
