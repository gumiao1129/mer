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




if(isset($_SESSION['patient_id']))
{
       $patient_id=$_SESSION['patient_id'];

       $firstname       = trim(mysql_real_escape_string($_POST["firstname"]));
       $middlename      = trim(mysql_real_escape_string($_POST["middlename"]));
       $lastname        = trim(mysql_real_escape_string($_POST["lastname"]));
       $dob_month       = (int)(mysql_real_escape_string($_POST["dob_month"]));
       $dob_day         = (int)(mysql_real_escape_string($_POST["dob_day"]));
       $dob_year        = (int)(mysql_real_escape_string($_POST["dob_year"]));
       $gender          = trim(mysql_real_escape_string($_POST["gender"]));
       $introduction    = trim(mysql_real_escape_string($_POST["introduction"]));
       $email           = trim(mysql_real_escape_string($_POST["email"]));
       $city            = trim(mysql_real_escape_string($_POST["city"]));
       $state           = trim(mysql_real_escape_string($_POST["state"]));
       $country         = trim(mysql_real_escape_string($_POST["country"]));
       $homenumber      = trim(mysql_real_escape_string($_POST["homenumber"]));
       $businessnumber  = trim(mysql_real_escape_string($_POST["businessnumber"]));
       $mobilenumber    = trim(mysql_real_escape_string($_POST["mobilenumber"]));
       
       $introduction    = str_replace("<br />","",$introduction);
       $introduction    = str_replace("<br>","",$introduction);
       
           foreach($_POST as $name => $value) 
           { 
               if($name == "dob_month" || $name == "dob_day" || $name == "dob_year" )
               {
                   $value = (int)(mysql_real_escape_string($value));
               }
               else
               {
                   $value = trim(mysql_real_escape_string($value));
               }  
           }  
           $birthday = $dob_year .'-'. $dob_month .'-'. $dob_day;
           
            $model = new CI_model();
            $table = "patient";
            $updateContent = "firstname = '$firstname', lastname = '$lastname', middlename = '$middlename', DOB = '$birthday', sex = '$gender', introduction = '$introduction', email = '$email', city = '$city', state = '$state', country_code = '$country', phone_home = '$homenumber', phone_biz = '$businessnumber', phone_cell = '$mobilenumber' 
                                WHERE patient_id = $patient_id";
            $model->dbUpdate($table, $updateContent);
           
            echo "good";
       
}

if(isset($_SESSION['physician_id']))
{
       $physician_id=$_SESSION['physician_id'];    
       
       $firstname       = trim(mysql_real_escape_string($_POST["firstname"]));
       $middlename      = trim(mysql_real_escape_string($_POST["middlename"]));
       $lastname        = trim(mysql_real_escape_string($_POST["lastname"]));
       $dob_month       = (int)(mysql_real_escape_string($_POST["dob_month"]));
       $dob_day         = (int)(mysql_real_escape_string($_POST["dob_day"]));
       $dob_year        = (int)(mysql_real_escape_string($_POST["dob_year"]));
       $gender          = trim(mysql_real_escape_string($_POST["gender"]));
       $specialty       = trim(mysql_real_escape_string($_POST["specialties"]));
       $introduction    = trim(mysql_real_escape_string($_POST["introduction"]));
       $email           = trim(mysql_real_escape_string($_POST["email"]));
       $city            = trim(mysql_real_escape_string($_POST["city"]));
       $state           = trim(mysql_real_escape_string($_POST["state"]));
       $country         = trim(mysql_real_escape_string($_POST["country"]));
       $homenumber      = trim(mysql_real_escape_string($_POST["homenumber"]));
       $businessnumber  = trim(mysql_real_escape_string($_POST["businessnumber"]));
       $mobilenumber    = trim(mysql_real_escape_string($_POST["mobilenumber"]));
       
       $introduction    = str_replace("<br />","",$introduction);
       $introduction    = str_replace("<br>","",$introduction);
       
           foreach($_POST as $name => $value) 
           { 
               if($name == "dob_month" || $name == "dob_day" || $name == "dob_year" )
               {
                   $value = (int)(mysql_real_escape_string($value));
               }
               else
               {
                   $value = trim(mysql_real_escape_string($value));
               }  
           }  
           $birthday = $dob_year .'-'. $dob_month .'-'. $dob_day;
           
            $model = new CI_model();
            $table = "physician";
            $updateContent = "firstname = '$firstname', lastname = '$lastname', middlename = '$middlename', DOB = '$birthday', sex = '$gender', specialty = '$specialty', introduction = '$introduction', email = '$email', city = '$city', state = '$state', country_code = '$country', phonew1 = '$homenumber', phonew2 = '$businessnumber', phonecell = '$mobilenumber' 
                                WHERE physician_id = $physician_id";
            $model->dbUpdate($table, $updateContent);
           
            echo "good";
}

?>
