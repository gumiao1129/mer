<?php
error_reporting (0);
include_once('classes/config.php');
include_once('classes/sessions.php');
include_once('classes/tbs_class_php5.php');
include_once('lang/en.php');
include_once('configData.php');     
include_once('classes/validation.php');     
include_once('models/CI_model.php'); 
include_once('classes/random_code.php'); 


if(isset($_POST['submitted']))
{
    // get login info and check the DB
    $user_name_login	= mysql_real_escape_string($_POST['user_name_login']);
    $password_login	= mysql_real_escape_string($_POST['password_login']);
    $password_login	= md5($password_login);

    $login_error_message  ='';

    //start validate all the login fields
    $loginValidation = new validation();
    //check username
    $check_username_empty = $loginValidation->checkEmpty($user_name_login);
    $check_username_valid= $loginValidation->checkValid($user_name_login);
    $check_username_length=$loginValidation->username($user_name_login, $max_username_length);

            if($check_username_empty == 'pass')
            {
                if($check_username_valid == 'pass')
                {
                    if($check_username_length == 'pass')
                    {
                        $procede = true;
                    }
                    else
                    {
                        $procede = false;
                        $login_error_message = $login_error_message.$lang_user_name.$lang_usernameShortOrLong;
                    }
                }
                else
                {
                    $procede = false;
                    $login_error_message = $login_error_message.$lang_user_name.$lang_unmatch;
                }
            }
            else
            {
                $procede = false;
                $login_error_message = $login_error_message.$lang_user_name.$lang_fillEmpty;
            }

            //check password
            $check_password_empty = $loginValidation->checkEmpty($password_login);
            $check_password_valid= $loginValidation->checkValid($password_login);
            if($check_password_empty == 'pass')
            {
                if($check_password_valid == 'pass')
                {
                    $procede = true;
                }
                else
                {
                    $procede = false;
                    $login_error_message = $login_error_message.$lang_password.$lang_unmatch;
                }
            }
            else
            {
                $procede = false;
                $login_error_message = $login_error_message.$lang_password.$lang_fillEmpty;
            }

            
            
    if($procede == true && $login_error_message==null)
    {
        //Config the database and check the patient and physician 
        $model = new CI_model();
        $table = "patient";
        $columns = "patient_id, username, password";
        $content = "username = '$user_name_login'";
        $result = $model->dbSelect($table, $columns, $content);
        if($result != null)
        {
             if($password_login==$result['password'])
             {
                 //Go to home page
                 @session_start();
                 @session_register('patient_id');
                 $_SESSION['patient_id']    = $result['patient_id'];
                 unset($_SESSION['physician_id']);
                 header('Location: home.php');
             }
             else
             {
                 $procede = false;
                 $login_error_message = $login_error_message.$lang_username_not_match_password;
             }
        }
        else 
        {
             $table = "physician";
             $columns = "physician_id, username, password";
             $content = "username = '$user_name_login'";
             $result = $model->dbSelect($table, $columns, $content);
             if($result != null)
             {
                if($password_login==$result['password'])
                {
                    //Go to home page
                    @session_start();
                    @session_register('patient_id');
                    $_SESSION['physician_id']    = $result['physician_id'];
                    unset($_SESSION['patient_id']);
                    header('Location: home.php');
                }
                else
                {
                    $procede = false;
                    $login_error_message = $login_error_message.$lang_username_not_match_password;
                }
             }
             else
             {       
                    $procede = false;
                    $login_error_message = $login_error_message.$lang_user_null_existed;
             }
        }
        
            $template 		= "views/main_0.htm";        
            $inner_template2 	= "views/login_fails_form.html";
            
            $TBS 			= new clsTinyButStrong;
            $TBS->NoErr 	= true;

            $TBS->LoadTemplate("$template");

            $TBS->Render 	= TBS_OUTPUT;
            $TBS->Show();
            
            @mysql_close();
            die();
        
    }
    else{
            $template 		= "views/main_0.htm";
            $inner_template2 	= "views/login_fails_form.html";
            
            $TBS 			= new clsTinyButStrong;
            $TBS->NoErr 	= true;

            $TBS->LoadTemplate("$template");

            $TBS->Render 	= TBS_OUTPUT;
            $TBS->Show();
            
            @mysql_close();
            die();
    } 

}
else
{
    header('Location: index.php');
}
?>
