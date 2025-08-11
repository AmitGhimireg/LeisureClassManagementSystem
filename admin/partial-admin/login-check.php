<?php
//authorization - access control
//check whether the user is login or not
if (!isset($_SESSION['logged'])) //if logged is false
{
    //user is not login
    //redirect to login page with message
    $_SESSION['no-login-message'] = "<div class='alert alert-danger'> Please Login to Proceed... </div>";
    //redirect to login page
    header('location:' . SITEURL . 'login.php');
}
