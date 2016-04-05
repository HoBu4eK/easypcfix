<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: sendmail_include.php
| Author: PHP-Fusion Development Team
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }


function sendemail($toname, $toemail, $fromname, $fromemail, $subject, $message, $type = "html", $cc = "", $bcc = ""){
	global $settings;

//PEAR
require_once CLASSES."PHPPEAR/Mail.php";
require_once CLASSES."PHPPEAR/Mail/mime.php";
  
//HEADERS
$from = $fromname." <".$fromemail.">";
$to = $toname." <".$toemail.">";

 
 




//CONNECTION SETTINGS
if($settings['smtp_ssl']==1){
	$host = "ssl://".$settings['smtp_host'].":".$settings['smtp_port'];			
}else{
$port = $settings['smtp_port'];
$host = $settings['smtp_host'];
}


$auth = $settings['smtp_auth'] ? true : false;
$crlf = "\n";
if($auth==true){
	$username = $settings['smtp_username'];
	$password = $settings['smtp_password'];
}else{
	$username = "";
	$password = "";	
}

$headers = array ('From' => $from, 'To' => $to, 'Subject' => $subject);

//connecting to smtp
$smtp = Mail::factory('smtp', array ('host' => $host, 'auth' => $auth, 'username' => $username, 'password' => $password, 'port' => $port));
 
//processing html
$mime = new Mail_mime($crlf);
$mime->setHTMLBody($message);
$body = $mime->get();
$headers = $mime->headers($headers);


 
 
 
 //sendmail
 $mail = $smtp->send($to, $headers, $body);
 
 if (PEAR::isError($mail)) {
   echo("<p>" . $mail->getMessage() . "</p>");
   return FALSE;
  } else {

   return TRUE;
  }
  
  
}

function sendemail_template($template_key, $subject, $message, $user, $receiver, $thread_url = "", $toemail, $sender = "", $fromemail = "") {
	global $settings;

	$data = dbarray(dbquery("SELECT * FROM ".DB_EMAIL_TEMPLATES." WHERE template_key='".$template_key."' LIMIT 1"));
	$message_subject = $data['template_subject'];
	$message_content = $data['template_content'];
	$template_format = $data['template_format'];
	$sender_name = ($sender != "" ? $sender : $data['template_sender_name']);
	$sender_email = ($fromemail != "" ? $fromemail : $data['template_sender_email']);
	$subject_search_replace = array("[SUBJECT]" => $subject, "[SITENAME]" => $settings['sitename'],
									"[SITEURL]" => $settings['siteurl'], "[USER]" => $user, "[SENDER]" => $sender_name,
									"[RECEIVER]" => $receiver);
	$message_search_replace = array("[SUBJECT]" => $subject, "[SITENAME]" => $settings['sitename'],
									"[SITEURL]" => $settings['siteurl'], "[MESSAGE]" => $message, "[USER]" => $user,
									"[SENDER]" => $sender_name, "[RECEIVER]" => $receiver,
									"[THREAD_URL]" => $thread_url);
	foreach ($subject_search_replace as $search => $replace) {
		$message_subject = str_replace($search, $replace, $message_subject);
	}
	foreach ($message_search_replace as $search => $replace) {
		$message_content = str_replace($search, $replace, $message_content);
	}
	if ($template_format == "html") {
		$message_content = nl2br($message_content);
	}
	if (sendemail($receiver, $toemail, $sender_name, $sender_email, $message_subject, $message_content, $template_format)) {
		return TRUE;
	} else {
		return FALSE;
	}
}

