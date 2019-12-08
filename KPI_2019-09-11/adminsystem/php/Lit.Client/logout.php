<?php
	session_start();

    $_SESSION['valid_user'] = "";    
  
	header( 'Location: /Php/Lit.Client/index.php' ) ;
?>