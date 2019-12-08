<?php
session_start();

if (isset($_POST['user_id']) && isset($_POST['user_pass']))
{
  // if the user has just tried to log in
  $userid = $_POST['user_id'];
  $password = $_POST['user_pass'];
  $result = "N";
  
  if($userid == "lemontimeit" && $password == "siteadmin"){
	$result = "Y";
  }

  if ($result == "Y")
  {
    // if they are in the database register the user id
    $_SESSION['valid_user'] = $userid;    
  }

	header( 'Location: ./page/main/main.php' ) ;
}
?>