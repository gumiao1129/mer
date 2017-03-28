<?php
error_reporting (0);
/**
 * Description of logout
 *
 * @author Gu Miao
 */
session_start();
session_destroy();

foreach ( $_COOKIE as $key => $value ) {
     		$logout = '';
    		setcookie($key, $logout);
    	}
    	foreach ($_SESSION as $key => $value) {
   		$_SESSION[$key] 	= NULL;
		unset($_SESSION[$key]);
	}
        
header("Location: " . "index.php");
?>
