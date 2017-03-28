<?php

include_once('classes/validation.php');      
include_once('models/CI_model.php'); 
include_once('lang/en.php');
include_once('configData.php');   


$login_error_message  ="";
$category = "";
$category_id = "";


$handle = fopen('php://input','r');
$jsonInput = fgets($handle);
$decoded_post = json_decode($jsonInput,true);

//var_dump($decoded_post);

//if(isset($_GET['submitted']) && isset($_GET["user_name_login"]) && isset($_GET["password_login"]) && isset($_GET["secure_code"]))
if(isset($decoded_post['submitted']) && isset($decoded_post["user_name_login"]) && isset($decoded_post["password_login"]) && isset($decoded_post["secure_code"]))
{
    $user_name_login	= trim($decoded_post['user_name_login']);
    $password_login	= trim($decoded_post['password_login']);
    //$password_login	= md5($password_login);
    
    

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
                 $category_id    = $result['patient_id'];
                 $category = "patient";
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
                    $category_id    = $result['physician_id'];
                    $category = "physician";
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
       
    }
}
else
{
    $login_error_message = $error404;
}

if($login_error_message=="") 
{
    if($category!=null&&$category_id!=null)
    {
        //print $category."_".$category_id;
        print md5("YES");
    }
    else
    {
       print $error404;
    }
}
else
{
   print $login_error_message;
}


?>
