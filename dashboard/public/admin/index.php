<?php
// Include Config
include_once "/var/www/dashboard/private/content/pistar_functions.php";
include_once "/var/www/dashboard/private/content/pistar_dashversion.php";
include_once "/var/www/dashboard/public/lang/language.php";

$pageTitle = "Pi-Star ".$lang['digital_voice']." ".$lang['dashboard_for']." ".$config_mmdvmhost['General']['Callsign'];

// Start the page output
include_once "/var/www/dashboard/private/content/pistar_head.php";		// Page Headder

// Main Page Output
include_once "/var/www/dashboard/private/content/pistar_repeaterinfo.php";	// Repeater Info Pannel
include_once "/var/www/dashboard/private/content/pistar_content.php";		// Main Page Content

// Finnish the page output
include_once "/var/www/dashboard/private/content/pistar_foot.php";		// End page output
?>
