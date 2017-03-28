<?php
session_start();
//error_reporting (0);
include_once('classes/config.php');
include_once('classes/sessions.php');
include_once('classes/tbs_class_php5.php');
include_once('lang/en.php');
include_once('configData.php');     
include_once('classes/validation.php');     
include_once('models/CI_model.php'); 
include_once('classes/random_code.php'); 
include_once('classes/mkdir_chmod.php'); 


$first_name		= '';
$last_name 		= '';
$email 	= '';
$confirm_email 	= '';
$user_name 		= '';
$password 		= '';
$confirm_password = '';
$gender ='';
$country_list	= '';
$dob_month		= '';
$dob_day		= '';
$dob_year		= '';
$zip_code		= '';
$captext                ='';


$specialties = '';

$birthday		= '';

$action		='';
$new_user_name	='';

$error_message  ='';
if(isset ($_GET['action']))
{
    $action=mysql_real_escape_string( $_GET['action'] );
}
if(isset ($_GET['new_user_name']))
{
    $new_user_name=mysql_real_escape_string( $_GET['new_user_name'] );
}

if($action == 'check_user')
{
    if ( $new_user_name == '' ) {
		echo "<font color=\"#EE0000\" size=\"2\"><b>".$lang_fillin."</b></font>";
		die();
	}
	if ( strlen($new_user_name) < 4 ) {
		echo "<font color=\"#EE0000\" size=\"2\"><b>".$lang_lessFourCharacter."</b></font>";
		die();
	}
	if ( strlen($new_user_name) > $max_username_length ) {
		echo "<font color=\"#EE0000\" size=\"2\"><b>".$lang_morethan12Character."</b></font>";
		die();
	}
    
}

//procede the registration validation process
if(isset($_POST['form_submitted']))
{
        //define all the variables
        $first_name		= trim(mysql_real_escape_string( $_POST['first_name'] ));
        $last_name 		= trim(mysql_real_escape_string( $_POST['last_name'] ));
        $email                  = trim(mysql_real_escape_string( $_POST['email'] ));
        $confirm_email          = trim(mysql_real_escape_string( $_POST['email_address2'] ));
        $user_name 		= trim(mysql_real_escape_string( $_POST['user_name'] ));
        $password 		= trim(mysql_real_escape_string( $_POST['password'] ));
        $confirm_password       = trim(mysql_real_escape_string( $_POST['confirm_password'] ));
        $gender                 = trim(mysql_real_escape_string( $_POST['gender'] ));
        $country_list           = trim(mysql_real_escape_string( $_POST['country_list'] ));
        $dob_month		= (int) mysql_real_escape_string( $_POST['dob_month'] );
        $dob_day		= (int) mysql_real_escape_string( $_POST['dob_day'] );
        $dob_year		= (int) mysql_real_escape_string( $_POST['dob_year'] );
        $zip_code		= trim(mysql_real_escape_string( $_POST['zip_code'] ));
        $captext                = trim(mysql_real_escape_string( $_POST['captext'] ));
        
        
        //check whether option "Terms" selected
        if (isset($_POST['terms'])&& $_POST['terms'] == 'yes' ) 
        {
            $checked = 'checked=\"checked\"';
            $procede = true;

	} 
        else 
        {
            $procede = false;
            $error_message = $error_message.$lang_agree_to_terms;
	}
        
        //start validate all the fields
        $regValidation = new validation();
        
        
        //check username
        $check_username_empty = $regValidation->checkEmpty($user_name);
        $check_username_valid= $regValidation->checkValid($user_name);
        $check_username_length=$regValidation->username($user_name, $max_username_length);
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
        //check confirmed password
        if($confirm_password==$password)
        {
            $procede = true;
        }
        else
        {
            $procede = false;
            $error_message = $error_message.$lang_unconfirmedPassword;
        }
        
        
        //check firstname
        $check_first_name_empty = $regValidation->checkEmpty($first_name);
        $check_first_name_valid= $regValidation->checkValid($first_name);
               
        if($check_first_name_empty == 'pass')
        {
            if($check_first_name_valid == 'pass')
            {
                $procede = true;
            }
            else
            {
                $procede = false;
                $error_message = $error_message.$lang_first_name.$lang_unmatch;
            }
        }
        else
        {
            $procede = false;
            $error_message = $error_message.$lang_first_name.$lang_fillEmpty;
        }
        
        
        //check lastname
        $check_last_name_empty = $regValidation->checkEmpty($last_name);
        $check_last_name_valid= $regValidation->checkValid($last_name);
        if($check_last_name_empty == 'pass')
        {
            if($check_last_name_valid == 'pass')
            {
                $procede = true;
            }
            else
            {
                $procede = false;
                $error_message = $error_message.$lang_last_name.$lang_unmatch;
            }
        }
        else
        {
            $procede = false;
            $error_message = $error_message.$lang_last_name.$lang_fillEmpty;
        }
        
        //check gender seleted nor not
        if($gender == '-1')
        {
            $procede = false;
            $error_message = $error_message.$lang_genderMiss;
        }
        else
        {
            $procede = true;
        }
        
        //check email
        $check_email_empty = $regValidation->checkEmpty($email);
        $check_email_valid= $regValidation->email($email);
        if($check_email_empty == 'pass')
        {
            if($check_email_valid == 'pass')
            {
                $procede = true;
            }
            else
            {
                $procede = false;
                $error_message = $error_message.$lang_email.$lang_unmatchEmail;
            }
        }
        else
        {
            $procede = false;
            $error_message = $error_message.$lang_email.$lang_fillEmpty;
        }
        
        if($confirm_email==$email)
        {
            $procede = true;
        }
        else
        {
            $procede = false;
            $error_message = $error_message.$lang_unconfirmedemail;
        }
        
        //check DOB
        if($dob_month!='-1'&& $dob_day != '-1' && $dob_year != '-1')
        {
            $procede = true;
            $birthday = $dob_year .'-'. $dob_month .'-'. $dob_day;
        }
        else
        {
            $procede = false;
            $error_message = $error_message.$lang_unmatchbirthday;
        }
        
        //check zipcode
        $check_zip_code_empty = $regValidation->checkEmpty($zip_code);
        $check_zip_code_valid= $regValidation->checkValid($zip_code);
        if($check_zip_code_empty == 'pass')
        {
            if($check_zip_code_valid == 'pass')
            {
                $procede = true;
            }
            else
            {
                $procede = false;
                $error_message = $error_message.$lang_zip_code.$lang_unmatch;
            }
        }
        else
        {
            $procede = false;
            $error_message = $error_message.$lang_zip_code.$lang_fillEmpty;
        }
        
        //check country list
        if($country_list!='-1')
        {
            $procede = true;
        }
        else
        {
            $procede = false;
            $error_message = $error_message.$lang_countrylistmiss;
        }
        
        //check security code match or not
        $check_security_code_empty = $regValidation->checkEmpty($captext);
        $check_security_code_valid= $regValidation->checkValid($captext);
        if($check_security_code_empty == 'pass')
        {
            if($check_security_code_valid == 'pass')
            {
                if ( strtolower($_POST['captext']) != strtolower($_SESSION['security_code']) )
                {
                    $procede = false;
                    $error_message = $error_message.$lang_securitycodeunmatch;
                }
                else
                {
                    $procede = true;
                }
            }
            else
            {
                $procede = false;
                $error_message = $error_message.$lang_enter_security.$lang_unmatch;
            }
        }
        else
        {
            $procede = false;
            $error_message = $error_message.$lang_enter_security.$lang_fillEmpty;
        }
        
        if($procede != true || $error_message!=null)
        {
            $template 		= "views/main_0.htm";
            $inner_template1 	= "views/login_form.html";
            $inner_template2 	= "views/registration_form.html";
            $inner_template3 	= "views/screeningtools_index_page.html";
            $TBS 			= new clsTinyButStrong;
            $TBS->NoErr 	= true;

            $TBS->LoadTemplate("$template");

            $TBS->Render 	= TBS_OUTPUT;
            $TBS->Show();
            
            @mysql_close();
            die();
        }
        else
        {
            $characters         = 15;
            $random_code	= new random_code($characters);
            $generateCode       = $random_code->generateCode();
            $password_email	= $password;
            $password		= md5($password);
            $passwordSalt 	= substr(md5(rand()), 0, 4);
            
            $image_dir          = "patient_photo/".md5($user_name);
            if($gender == "male")
            {
                 $profile_pic_name   = "lib/img/male.gif";
                 $thumbnail_pic_name = "lib/img/male.gif";
            }
            
            if($gender == "female")
            {
                $profile_pic_name   = "lib/img/female.gif";
                $thumbnail_pic_name = "lib/img/female.gif";
            }
            
            
            //Config the database and insert the registration
            $model = new CI_model();
            $table = "patient";
            $columns = "username, password, passwordSalt, firstname, lastname, DOB, email, sex, postal_code, country_code, account_status, date_created, random_code, image_dir, profile_pic_name, thumbnail_pic_name";
            $content = "'$user_name', '$password', '$passwordSalt', '$first_name', '$last_name', '$birthday', '$email','$gender', '$zip_code', '$country_list', 'new', NOW(), '$generateCode', '$image_dir', '$profile_pic_name', '$thumbnail_pic_name'";
            $model->dbinsert($table, $columns, $content);
            
            //TODO email validation
            
            
            //Go to home page
            $columns = "patient_id, username, password, image_dir";
            $content = "username = '$user_name'";
            $result = $model->dbSelect($table, $columns, $content);
            
            if($result!= null)
            {
                 @session_start();
                 @session_register('patient_id');
                 $_SESSION['patient_id']    = $result['patient_id'];
                 mkdir_chmod777($result['image_dir']);
                 header('Location: home.php');
            }
            else
            {
                //TODO list the DB error and write into log file
            }
            /**
            $template 		= "views/main_1.htm";
            $inner_template1 	= "views/home.html";
            $TBS 			= new clsTinyButStrong;
            $TBS->NoErr 	= true;

            $TBS->LoadTemplate("$template");

            $TBS->Render 	= TBS_OUTPUT;
            $TBS->Show();
             * */
           
            @mysql_close();
            die();
            
        }
}
else if(isset($_POST['physician_form_submitted']))
{
        //define all the variables
        $first_name		= trim(mysql_real_escape_string( $_POST['first_name'] ));
        $last_name 		= trim(mysql_real_escape_string( $_POST['last_name'] ));
        $email                  = trim(mysql_real_escape_string( $_POST['email'] ));
        $confirm_email          = trim(mysql_real_escape_string( $_POST['email_address2'] ));
        $user_name 		= trim(mysql_real_escape_string( $_POST['user_name'] ));
        $password 		= trim(mysql_real_escape_string( $_POST['password'] ));
        $confirm_password       = trim(mysql_real_escape_string( $_POST['confirm_password'] ));
        $gender                 = trim(mysql_real_escape_string( $_POST['gender'] ));
        $country_list           = trim(mysql_real_escape_string( $_POST['country_list'] ));
        $dob_month		= (int) mysql_real_escape_string( $_POST['dob_month'] );
        $dob_day		= (int) mysql_real_escape_string( $_POST['dob_day'] );
        $dob_year		= (int) mysql_real_escape_string( $_POST['dob_year'] );
        $zip_code		= trim(mysql_real_escape_string( $_POST['zip_code'] ));
        $captext                = trim(mysql_real_escape_string( $_POST['captext'] ));
        
        $specialties            = trim(mysql_real_escape_string( $_POST['specialties'] ));
        
        //check whether option "Terms" selected
        if (isset($_POST['terms'])&& $_POST['terms'] == 'yes' ) 
        {
            $checked = 'checked=\"checked\"';
            $procede = true;

	} 
        else 
        {
            $procede = false;
            $error_message = $error_message.$lang_agree_to_terms;
	}
        
        //start validate all the fields
        $regValidation = new validation();
        
        
        //check username
        $check_username_empty = $regValidation->checkEmpty($user_name);
        $check_username_valid= $regValidation->checkValid($user_name);
        $check_username_length=$regValidation->username($user_name, $max_username_length);
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
        //check confirmed password
        if($confirm_password==$password)
        {
            $procede = true;
        }
        else
        {
            $procede = false;
            $error_message = $error_message.$lang_unconfirmedPassword;
        }
        
        
        //check firstname
        $check_first_name_empty = $regValidation->checkEmpty($first_name);
        $check_first_name_valid= $regValidation->checkValid($first_name);
               
        if($check_first_name_empty == 'pass')
        {
            if($check_first_name_valid == 'pass')
            {
                $procede = true;
            }
            else
            {
                $procede = false;
                $error_message = $error_message.$lang_first_name.$lang_unmatch;
            }
        }
        else
        {
            $procede = false;
            $error_message = $error_message.$lang_first_name.$lang_fillEmpty;
        }
        
        
        //check lastname
        $check_last_name_empty = $regValidation->checkEmpty($last_name);
        $check_last_name_valid= $regValidation->checkValid($last_name);
        if($check_last_name_empty == 'pass')
        {
            if($check_last_name_valid == 'pass')
            {
                $procede = true;
            }
            else
            {
                $procede = false;
                $error_message = $error_message.$lang_last_name.$lang_unmatch;
            }
        }
        else
        {
            $procede = false;
            $error_message = $error_message.$lang_last_name.$lang_fillEmpty;
        }
        
        //check gender seleted nor not
        if($gender == '-1')
        {
            $procede = false;
            $error_message = $error_message.$lang_genderMiss;
        }
        else
        {
            $procede = true;
        }
        
        if($specialties == "-1")
        {
            $procede = false;
            $error_message = $error_message.$lang_specialtiesMiss;
        }
        else
        {
             $procede = true;
        }
        //check email
        $check_email_empty = $regValidation->checkEmpty($email);
        $check_email_valid= $regValidation->email($email);
        if($check_email_empty == 'pass')
        {
            if($check_email_valid == 'pass')
            {
                $procede = true;
            }
            else
            {
                $procede = false;
                $error_message = $error_message.$lang_email.$lang_unmatchEmail;
            }
        }
        else
        {
            $procede = false;
            $error_message = $error_message.$lang_email.$lang_fillEmpty;
        }
        
        if($confirm_email==$email)
        {
            $procede = true;
        }
        else
        {
            $procede = false;
            $error_message = $error_message.$lang_unconfirmedemail;
        }
        
        //check DOB
        if($dob_month!='-1'&& $dob_day != '-1' && $dob_year != '-1')
        {
            $procede = true;
            $birthday = $dob_year .'-'. $dob_month .'-'. $dob_day;
        }
        else
        {
            $procede = false;
            $error_message = $error_message.$lang_unmatchbirthday;
        }
        
        //check zipcode
        $check_zip_code_empty = $regValidation->checkEmpty($zip_code);
        $check_zip_code_valid= $regValidation->checkValid($zip_code);
        if($check_zip_code_empty == 'pass')
        {
            if($check_zip_code_valid == 'pass')
            {
                $procede = true;
            }
            else
            {
                $procede = false;
                $error_message = $error_message.$lang_zip_code.$lang_unmatch;
            }
        }
        else
        {
            $procede = false;
            $error_message = $error_message.$lang_zip_code.$lang_fillEmpty;
        }
        
        //check country list
        if($country_list!='-1')
        {
            $procede = true;
        }
        else
        {
            $procede = false;
            $error_message = $error_message.$lang_countrylistmiss;
        }
        
        //check security code match or not
        $check_security_code_empty = $regValidation->checkEmpty($captext);
        $check_security_code_valid= $regValidation->checkValid($captext);
        if($check_security_code_empty == 'pass')
        {
            if($check_security_code_valid == 'pass')
            {
                if ( strtolower($_POST['captext']) != strtolower($_SESSION['security_code']) )
                {
                    $procede = false;
                    $error_message = $error_message.$lang_securitycodeunmatch;
                }
                else
                {
                    $procede = true;
                }
            }
            else
            {
                $procede = false;
                $error_message = $error_message.$lang_enter_security.$lang_unmatch;
            }
        }
        else
        {
            $procede = false;
            $error_message = $error_message.$lang_enter_security.$lang_fillEmpty;
        }
        
        if($procede != true || $error_message!=null)
        {
            $template 		= "views/main_0.htm";
            $inner_template1 	= "views/login_form.html";
            $inner_template2 	= "views/physician_registration_form.html";
            $inner_template3 	= "views/screeningtools_index_page.html";
            $TBS 			= new clsTinyButStrong;
            $TBS->NoErr 	= true;

            $TBS->LoadTemplate("$template");

            $TBS->Render 	= TBS_OUTPUT;
            $TBS->Show();
            
            @mysql_close();
            die();
        }
        else
        {
            $characters         = 15;
            $random_code	= new random_code($characters);
            $generateCode       = $random_code->generateCode();
            $password_email	= $password;
            $password		= md5($password);
            $passwordSalt 	= substr(md5(rand()), 0, 4);
            
            $image_dir          = "physician_photo/".md5($user_name);
            if($gender == "male")
            {
                 $profile_pic_name   = "lib/img/male.gif";
                 $thumbnail_pic_name = "lib/img/male.gif";
            }
            
            if($gender == "female")
            {
                $profile_pic_name   = "lib/img/female.gif";
                $thumbnail_pic_name = "lib/img/female.gif";
            }
            
            //Config the database and insert the registration
            $model = new CI_model();
            $table = "physician";
            $columns = "username, password, passwordSalt, firstname, lastname, DOB, email, sex, postal_code, country_code, account_status, date_created, random_code, specialty, image_dir, profile_pic_name, thumbnail_pic_name";
            $content = "'$user_name', '$password', '$passwordSalt', '$first_name', '$last_name', '$birthday', '$email','$gender', '$zip_code', '$country_list', 'new', NOW(), '$generateCode', '$specialties', '$image_dir', '$profile_pic_name', '$thumbnail_pic_name'";
            $model->dbinsert($table, $columns, $content);
            
            //TODO email validation
            
            
            //Go to home page
            $columns = "physician_id, username, password, image_dir";
            $content = "username = '$user_name'";
            $result = $model->dbSelect($table, $columns, $content);
            
            if($result!= null)
            {
                 @session_start();
                 @session_register('patient_id');
                 $_SESSION['physician_id']    = $result['physician_id'];
                 mkdir_chmod777($result['image_dir']);
                 header('Location: home.php');
            }
            else
            {
                //TODO list the DB error and write into log file
            }
            /**
            $template 		= "views/main_1.htm";
            $inner_template1 	= "views/home.html";
            $TBS 			= new clsTinyButStrong;
            $TBS->NoErr 	= true;

            $TBS->LoadTemplate("$template");

            $TBS->Render 	= TBS_OUTPUT;
            $TBS->Show();
             * */
           
            @mysql_close();
            die();
            
        }
}
else
{
            $template 		= "views/main_0.htm";
            $inner_template1 	= "views/login_form.html";
            if(isset($_GET['dr'])&& $_GET['dr']=='yes')
            {
                $inner_template2 	= "views/physician_registration_form.html";
            }
            else
            {
                $inner_template2 	= "views/registration_form.html";
            }
            $inner_template3 	= "views/screeningtools_index_page.html";
            
            $TBS 			= new clsTinyButStrong;
            $TBS->NoErr 	= true;

            $TBS->LoadTemplate("$template");

            $TBS->Render 	= TBS_OUTPUT;
            $TBS->Show();
            
            @mysql_close();
            die();
}
   


function safe_copy($src, $dest) {
    if (link($src, $dest)) {
        // Link succeeded, remove old name
        unlink($filename);
        return true;
    } else {
        // Link failed; filesystem has not been altered
        return false;
    }
}

?>