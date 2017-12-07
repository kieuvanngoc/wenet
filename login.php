<?php
$page = "login";
$page_title = "Đăng nhập quản trị";
include "header.php";

$task = PGRequest::getVar('task', '');

if ($task=='logout'){
	$admin->admin_logout();
	cheader("login.php");
}
if ($admin->admin_exists) {
	cheader("home.php");
}

$failed_login_count = $session->get('failed_login_count', 0);

$captchaShow = ($failed_login_count>3)?true:false;

if ($captchaShow){
	 require_once 'include/recaptcha/recaptchalib.php';
	// Get a key from https://www.google.com/recaptcha/admin/create
	$publickey = "6LdjkcISAAAAAESKLpjMzEQrZhtaC9hrK44bZdOB";
	$privatekey = "6LdjkcISAAAAANr34Pzhsd_nHmLJs50SFbH-DaYL";
}

# the response from reCAPTCHA
$resp = null;
$errorTxt = null;

// TRY TO LOGIN
if($task == "dologin") {
	// Check captcha
	if ($captchaShow){
		$resp = recaptcha_check_answer($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
		if (!$resp->is_valid) $errorTxt = $resp->error;
	}
	
    if (!$errorTxt) {
	  	// pasting username/password sometimes contains linebreak character
	  	// so gracefully trim before attempting to log in.
	  	$_POST['username'] = trim($_POST['username']);
	  	$_POST['password'] = trim($_POST['password']);
	  
	 	  $admin->admin_login();
	
	  	// IF ADMIN IS LOGGED IN SUCCESSFULLY, FORWARD THEM TO HOMEPAGE
	  	if(!$admin->is_error) {
	  		$session->set('failed_login_count', 0);
        $failed_login_count = 0;
	    	cheader("home.php");
	  	}
	  	// IF THERE WAS AN ERROR, SET ERROR MESSAGE
	  	else {
	  		$failed_login_count = $failed_login_count+1;
	  		$session->set('failed_login_count', $failed_login_count);
	    	$errorTxt = $admin->is_error;
	  	}
    }
}

if ($captchaShow){
	$showCaptcha = recaptcha_get_html($publickey, $errorTxt);
}
else $showCaptcha = false;

// INCLUDE FOOTER
$smarty->assign('errorTxt', $errorTxt);
$smarty->assign('captcha', $showCaptcha);
include "footer.php";
?>