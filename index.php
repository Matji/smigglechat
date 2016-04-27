<?php
session_start();
if((isset($_SESSION['pid']) && !empty($_SESSION['pid'])) && (isset($_SESSION['email']) && !empty($_SESSION['email'])))
{
   header("Location:views/users.html?action=getusers&loggedas=".$_SESSION['email']);
}
else
{
   header("Location:views/login.html");  
}

?>