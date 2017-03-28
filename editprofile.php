<?php
session_start();
error_reporting (0);
include_once('classes/config.php');
include_once('classes/sessions.php');
include_once('classes/tbs_class_php5.php');
include_once('lang/en.php');
include_once('configData.php');     
include_once('classes/validation.php');     
include_once('models/CI_model.php'); 
include_once('models/CI_model_TBS.php'); 
include_once('models/CI_model_search_engine.php'); 
include_once('classes/random_code.php'); 
include_once('classes/image_upload.php'); 
include_once('classes/mkdir_chmod.php'); 

if(isset($_SESSION['patient_id']))
{
    $patient_id = $_SESSION['patient_id'];
    $patientInfo = new CI_model();
    $table = "patient";
    $columns = "*";
    $content = "patient_id = '$patient_id'";
    $result = $patientInfo->dbSelect($table, $columns, $content);
    
    if($result != null)
    {
        $name = $result['lastname']." ".$result['firstname'];
    }
}

if(isset($_SESSION['physician_id']))
{
    $physician_id = $_SESSION['physician_id'];
    $physicianInfo = new CI_model();
    $table = "physician";
    $columns = "*";
    $content = "physician_id = '$physician_id'";
    $result = $physicianInfo->dbSelect($table, $columns, $content);
    if($result != null)
    {
        $name = $result['lastname']." ".$result['firstname'];
    }
}

    $template 		= "views/main_1.htm";
    $inner_template1 	= "views/nav_main_manu.html";             
    $inner_template2 	= "views/profile_side_nav_form.html";
    

if(isset($_GET['edit']))
{
    $edit = $_GET['edit'];
    if($edit == 'basicinfo')
    {
       $inner_template3 	= "views/profile_form.html";
       //$inner_template3 	= "views/profile_basicinfo_form.html";
    }
    else if($edit == 'changepic')
    {
        $profile_image_error = '';
        $image_category = "profile_image";
        
        mkdir_chmod777($result['image_dir']);
        $upload_dir = $result['image_dir']."/".$image_category;			// The directory for the images to be saved in
        mkdir_chmod777($upload_dir);
        $upload_path = $upload_dir."/";				// The path to where the image will be saved
           
        $large_image_name = $result['profile_pic_name']; 		// New name of the large image
        $thumb_image_name = $result['thumbnail_pic_name']; 	// New name of the thumbnail image
       // $photo_image_name = md5($result['username'].$result['patient_id'].date("d/m/y : H:i:s", time())).".jpg";
        
        $large_image_location = $large_image_name;
        $thumb_image_location = $thumb_image_name;
        //$photo_image_location = $upload_path.$photo_image_name;
        
        //Check to see if any images with the same names already exist
        if (file_exists($large_image_location)){
                if(file_exists($thumb_image_location)){
                        $thumb_photo_exists = "<img src=\"".$upload_path.$thumb_image_name."\" alt=\"Thumbnail Image\"/>";
                }else{
                        $thumb_photo_exists = "";
                }
                $large_photo_exists = "<img src=\"".$upload_path.$large_image_name."\" alt=\"Large Image\"/>";
        } else {
                $large_photo_exists = "";
                $thumb_photo_exists = "";
        }
        
        
        
        
        
        if(isset($_SESSION['patient_id']))
        {
            $photo_name_tag = md5($result['username'].$result['patient_id'].date("d/m/y : H:i:s", time()));
        }
        
        if(isset($_SESSION['physician_id']))
        {
            $photo_name_tag = md5($result['username'].$result['physician_id'].date("d/m/y : H:i:s", time()));
        }
        
        
        
        if (isset($_POST["upload_r"])) {
            
                
                $large_image_name = $photo_name_tag."_profile.jpg"; 		// New name of the large image
                $thumb_image_name = $photo_name_tag."_thumbnail.jpg"; 	// New name of the thumbnail image
                $photo_image_name = $photo_name_tag.".jpg";
                
                $large_image_location = $upload_path.$large_image_name;
                $thumb_image_location = $upload_path.$thumb_image_name;
                $photo_image_location = $upload_path.$photo_image_name;
                
                //Get the file information
                $userfile_name = $_FILES['image']['name'];
                $userfile_tmp = $_FILES['image']['tmp_name'];
                $userfile_size = $_FILES['image']['size'];
                $filename = basename($_FILES['image']['name']);
                $file_ext = substr($filename, strrpos($filename, '.') + 1);

                //Only process if the file is a JPG and below the allowed limit
                if((!empty($_FILES["image"])) && ($_FILES['image']['error'] == 0)) 
                {
                    $upload_profile_pic = new image_upload($upload_dir, $upload_path, $large_image_name, $thumb_image_name, $max_file, $max_width, $thumb_width, $thumb_height);
                    $pre_check_image = $upload_profile_pic->image_pre_check($file_ext, $userfile_size);
                    
                    if ($pre_check_image == "pass") 
                    {
                        if (isset($_FILES['image']['name']))
                        {                       
                                move_uploaded_file($userfile_tmp, $large_image_location);
                                chmod($large_image_location, 0777);

                                $width = $upload_profile_pic->getWidth($large_image_location);
                                $height = $upload_profile_pic->getHeight($large_image_location);
                                
                                
                                
                                if($width > $max_photo_width)
                                {
                                        $scale = $max_photo_width/$width;
                                        $uploaded = $upload_profile_pic->resizeNewImage($photo_image_location,$large_image_location,$file_ext, $width,$height,$scale);
                                }
                                else
                                {
                                        $scale = 1;
                                        $uploaded = $upload_profile_pic->resizeNewImage($photo_image_location,$large_image_location,$file_ext,$width,$height,$scale);
                                }
                                
                                
                                if(file_exists($photo_image_location))
                                {
                                    if(isset($_SESSION['patient_id']))
                                    {
                                        $table = "patient_photo";
                                        $columns = "patient_id, image_name, image_category,image_folder, date_created";
                                        $content = "'$patient_id', '$photo_image_name', '$image_category', '$upload_path', NOW()";
                                        $patientInfo->dbinsert($table, $columns, $content); 
                                    }
        
                                    if(isset($_SESSION['physician_id']))
                                    {
                                        $table = "physician_photo";
                                        $columns = "physician_id, image_name, image_category,image_folder, date_created";
                                        $content = "'$physician_id', '$photo_image_name', '$image_category', '$upload_path', NOW()";
                                        $physicianInfo->dbinsert($table, $columns, $content); 
                                    }
                                    
                                              
                                }                
                                //Scale the image if it is greater than the width set above
                                if ($width > $max_width){
                                        $scale = $max_width/$width;
                                        $uploaded = $upload_profile_pic->resizeImage($large_image_location,$file_ext, $width,$height,$scale);
                                }
                                else
                                {
                                        $scale = 1;
                                        $uploaded = $upload_profile_pic->resizeImage($large_image_location,$file_ext,$width,$height,$scale);
                                }
                                
                                if(file_exists($large_image_location))
                                {
                                    if (file_exists($result['profile_pic_name'])) {
                                            if($result['profile_pic_name'] != "lib/img/male.gif" && $result['profile_pic_name'] != "lib/img/female.gif" )
                                            {
                                                unlink($result['profile_pic_name']);
                                            }
                                        }
                                        
                                        if(isset($_SESSION['patient_id']))
                                        {
                                                $table = "patient";
                                                $content = "profile_pic_name = '$large_image_location' WHERE patient_id = $patient_id";
                                                $patientInfo->dbUpdate($table, $content); 
                                        }
        
                                        if(isset($_SESSION['physician_id']))
                                        {
                                                $table = "physician";
                                                $content = "profile_pic_name = '$large_image_location' WHERE physician_id = $physician_id";
                                                $physicianInfo->dbUpdate($table, $content); 
                                        }
                                        
                                              
                                }
                                
                                //Delete the thumbnail file so the user can create a new one
                                if (file_exists($result['thumbnail_pic_name'])) {
                                            if($result['thumbnail_pic_name'] != "lib/img/male.gif" && $result['thumbnail_pic_name'] != "lib/img/female.gif" )
                                            {
                                                unlink($result['thumbnail_pic_name']);
                                            }       
                                }
                        }
                    }
                    else
                    {
                        $profile_image_error= $profile_image_error.$lang_image_error;
                    }
                }
                else
                {
                        $profile_image_error= $profile_image_error.$lang_null_image;
                }
                
        }

        if (isset($_POST["upload_thumbnail"]) && strlen($large_photo_exists)>0) {
               $thumb_image_name = $photo_name_tag."_thumbnail.jpg"; 	// New name of the thumbnail image
               $thumb_image_location = $upload_path.$thumb_image_name;
                //Get the new coordinates to crop the image.
                $x1 = $_POST["x1"];
                $y1 = $_POST["y1"];
                $x2 = $_POST["x2"];
                $y2 = $_POST["y2"];
                $w = $_POST["w"];
                $h = $_POST["h"];
                //Scale the image to the thumb_width set above
                $scale = $thumb_width/$w;
                
                $upload_thumbnail_pic = new image_upload($upload_dir, $upload_path, $large_image_name, $thumb_image_name, $max_file, $max_width, $thumb_width, $thumb_height);
                    
                $cropped = $upload_thumbnail_pic->resizeThumbnailImage($thumb_image_location,$large_image_location,$w,$h,$x1,$y1,$scale);
                
                if(file_exists($thumb_image_location))
                {
                    if(isset($_SESSION['patient_id']))
                    {
                            $table = "patient";
                            $content = "thumbnail_pic_name = '$thumb_image_location' WHERE patient_id = $patient_id";
                            $patientInfo->dbUpdate($table, $content);  
                    }
        
                    if(isset($_SESSION['physician_id']))
                    {
                            $table = "physician";
                            $content = "thumbnail_pic_name = '$thumb_image_location' WHERE physician_id = $physician_id";
                            $physicianInfo->dbUpdate($table, $content);  
                    }
                 }
        }

        
        //Only display the javacript if an image has been uploaded
        if(strlen($large_photo_exists)>0)
        {
            $profile_image_existed = new image_upload($upload_dir, $upload_path, $large_image_name, $thumb_image_name, $max_file, $max_width, $thumb_width, $thumb_height);
                    
            $current_large_image_width =$profile_image_existed->getWidth($large_image_location);
            $current_large_image_height =$profile_image_existed->getHeight($large_image_location);
        }
        $inner_template3 	= "views/profile_pic_form.html";
    }
    else if($edit == 'doctors')
    {
        
             if(isset($_SESSION['patient_id']))
             {
                 $patient_id = $_SESSION['patient_id'];

                    //doctors gadget info
                    $dr_info_TBS = new CI_model_search_engine();
                    $dr_search_table = "physician";
                    $dr_search_table_left_join="physician_patient_relationships";
                    $dr_search_table_session_id_field = "patient_id_one";
                    $dr_search_table_left_join_id_field = "physician_id_one";
                    $dr_session_id = $_SESSION['patient_id'];
                    $dr_id_field = "physician_id";
                    $info = $dr_info_TBS->search_edited_diff_cate($dr_search_table, $dr_search_table_left_join, $dr_search_table_left_join_id_field, $dr_id_field, $dr_session_id,$dr_search_table_session_id_field); 
            }

            if(isset($_SESSION['physician_id']))
            {
                 $physician_id = $_SESSION['physician_id'];

                   //physician gadget info
                   $dr_info_TBS = new CI_model_search_engine();
                   $dr_search_table = "physician";
                   $dr_search_table_left_join="physician_physician_relationships";
                   $dr_search_table_session_id_field = "physician_id_one";
                   $dr_search_table_left_join_id_field = "physician_id_two";
                   $dr_session_id = $physician_id;
                   $dr_id_field = "physician_id";
                   $info = $dr_info_TBS->search_edited_same_cate($dr_search_table, $dr_search_table_left_join, $dr_search_table_left_join_id_field, $dr_id_field, $dr_session_id,$dr_search_table_session_id_field); 
            }

            $inner_template3 	= "views/profile_doctors_form.html";
        
    }
    else if($edit == 'friends')
    {
        if(isset($_SESSION['patient_id']))
        {
                 $patient_id = $_SESSION['patient_id'];
                   //patients gadget info
                   $pa_info_TBS = new CI_model_search_engine();
                   $pa_search_table = "patient";
                   $pa_search_table_left_join="patient_patient_relationships";
                   $pa_search_table_session_id_field = "patient_id_one";
                   $pa_search_table_left_join_id_field = "patient_id_two";
                   $pa_session_id = $_SESSION['patient_id'];
                   $pa_id_field = "patient_id";
                   $info = $pa_info_TBS->search_edited_same_cate($pa_search_table, $pa_search_table_left_join, $pa_search_table_left_join_id_field, $pa_id_field, $pa_session_id,$pa_search_table_session_id_field); 
        }

        if(isset($_SESSION['physician_id']))
        {
             $physician_id = $_SESSION['physician_id'];

                //patient gadget info
                $pa_info_TBS = new CI_model_search_engine();
                $pa_search_table = "patient";
                $pa_search_table_left_join="physician_patient_relationships";
                $pa_search_table_session_id_field = "physician_id_one";
                $pa_search_table_left_join_id_field = "patient_id_one";
                $pa_session_id = $physician_id;
                $pa_id_field = "patient_id";
                $info = $pa_info_TBS->search_edited_diff_cate($pa_search_table, $pa_search_table_left_join, $pa_search_table_left_join_id_field, $pa_id_field, $pa_session_id,$pa_search_table_session_id_field); 
        }  
        $inner_template3 	= "views/profile_patients_form.html";  
    }
    else
    {
        $inner_template3 	= "views/profile_basicinfo_form.html";
    }
}
else
{
    $inner_template3 	= "views/profile_basicinfo_form.html";   
}
    
    $TBS 		= new clsTinyButStrong;
    $TBS->NoErr 	= true;
    $TBS->Render 	= TBS_OUTPUT;
    $TBS->LoadTemplate("$template");
    
    if($edit == 'doctors'||$edit == 'friends')
    {
        $TBS->MergeBlock('info',$info);
    }
    $TBS->Show();
    
    
    function add_physicians_tag_widget($BlockName,&$CurrRec,$RecNum)
    {
            $ignore = "Delete";
            $CurrRec['level2'] = "<a style='color:black' href='ignoreConnection.php?height=180&width=350&modal=true&profile_img=$CurrRec[thumbnail_pic_name]&firstname=$CurrRec[firstname]&lastname=$CurrRec[lastname]&id=$CurrRec[physician_id]&add_cate=physician' class='thickbox' id='add_tag' title='Add as Doctor'>$ignore</a>";        
    }
                 
    function add_patients_tag_widget($BlockName,&$CurrRec,$RecNum)
    {  
           $ignore = "Delete";
           $CurrRec['level2'] = "<a style='color:black;' href='ignoreConnection.php?height=180&width=350&modal=true&profile_img=$CurrRec[thumbnail_pic_name]&firstname=$CurrRec[firstname]&lastname=$CurrRec[lastname]&id=$CurrRec[patient_id]&add_cate=patient' class='thickbox' id='add_tag' title='Add as Doctor'>$ignore</a>";  
    }
    
    @mysql_close();
    die();       


?>
