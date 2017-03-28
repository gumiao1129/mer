<?php
/**
 * Description of sessions
 *
 * @author Gu Miao
 */

//error_reporting(0);
//error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );

$currentFile = $_SERVER["PHP_SELF"];
$parts = Explode('/', $currentFile);
$fileName = $parts[count($parts) - 1];
if( (!isset($_SESSION['patient_id'])) && (!isset($_SESSION['physician_id'])))
{
    if($fileName != "index.php" && $fileName != "login.php")
    {
        header("Location: ./index.php");
    }   
}
else
{
    
    if($fileName == "index.php" || $fileName == "login.php")
    {
        header("Location: ./home.php");
    }
}

//$link = mysql_connect('meddgocom.ipagemysql.com', 'meddgo_nlrt_2012', 'Gcx94f23_nlrt') OR die(mysql_error());

?>
