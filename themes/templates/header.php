<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) PHP-Fusion Inc
| https://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: header.php
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


$google_Manager = "</head>\n<body>\n";  
$google_Manager.= "<!-- Google Tag Manager -->\n";
$google_Manager.= "<noscript><iframe src='//www.googletagmanager.com/ns.html?id=GTM-TB8QZV' height='0' width='0' style='display:none;visibility:hidden'>";
$google_Manager.= "</iframe></noscript>\n";
$google_Manager.= "<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':";
$google_Manager.= "new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],";
$google_Manager.= "j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=";
$google_Manager.= "'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);";
$google_Manager.= "})(window,document,'script','dataLayer','GTM-TB8QZV');</script>\n";
$google_Manager.= "<!-- End Google Tag Manager -->\n";
$google_Manager.= "\n\n";




$google_analitic = "<!-- Google Analytics Start -->\n";
$google_analitic.= "<script>";
$google_analitic.= "(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){";
$google_analitic.= "(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),";
$google_analitic.= "m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)";
$google_analitic.= "})(window,document,'script','//www.google-analytics.com/analytics.js','ga');\n";

$google_analitic.= "ga('create', 'UA-74769589-1', 'auto');\n";
$google_analitic.= "ga('send', 'pageview');\n";
$google_analitic.= "</script>";
$google_analitic.= "<!-- Google Analytics End -->\n";
$google_analitic.= "\n";


add_to_head("<link rel='stylesheet' type='text/css' href='".INCLUDES."jscripts/tinymce/plugins/codesample/css/prism.css'>");
add_to_head("<script src='".INCLUDES."jscripts/tinymce/plugins/codesample/css/prism.js'></script>");

add_to_head($google_Manager.$google_analitic);


// Check if Maintenance is Enabled
if (fusion_get_settings("maintenance") == "1" &&
    ((iMEMBER && fusion_get_settings("maintenance_level") == USER_LEVEL_MEMBER && fusion_get_userdata("user_id") != "1") ||
        (fusion_get_settings("maintenance_level") < fusion_get_userdata("user_level")))) {
    if (fusion_get_settings("site_seo")) {
        redirect(FUSION_ROOT.BASEDIR."maintenance.php");
    } else {
        redirect(BASEDIR."maintenance.php");
    }
}

if (fusion_get_settings("site_seo") == 1) {
    $permalink = \PHPFusion\Rewrite\Permalinks::getInstance();
}

require_once INCLUDES."breadcrumbs.php";

require_once INCLUDES."header_includes.php";

require_once THEME."theme.php";

require_once THEMES."templates/render_functions.php";

if (iMEMBER) {
	dbquery("UPDATE ".DB_USERS." SET user_lastvisit=UNIX_TIMESTAMP(NOW()), user_ip='".USER_IP."', user_ip_type='".USER_IP_TYPE."' WHERE user_id='".fusion_get_userdata("user_id")."'");
}
ob_start();

require_once THEMES."templates/panels.php";
