<?php
// Main Menu Code

// Basic Non-Admin Menu
if (substr($_SERVER["PHP_SELF"], 0, 7 ) !== "/admin/") {
  if ($_SERVER["PHP_SELF"] == "/index.php") { $homeAct = ' class="active"'; } else { $homeAct = ''; }
  if ($_SERVER["PHP_SELF"] == "/admin/index.php") { $adminAct = ' class="active"'; } else { $adminAct = ''; }
  if ($_SERVER["PHP_SELF"] == "/admin/configure.php") { $configAct = ' class="active"'; } else { $configAct = ''; }

  echo "      <ul class=\"tabs group\">\n";
  echo "        <li".$homeAct."><a href=\"/\">Dashboard</a></li>\n";
  echo "        <li".$adminAct."><a href=\"/admin/\">Admin</a></li>\n";
  echo "        <li".$configAct."><a href=\"/admin/configure.php\">Configure</a></li>\n";
  echo "      </ul>\n";
}

// Admin Menu
if (substr($_SERVER["PHP_SELF"], 0, 7 ) === "/admin/") {
  if ($_SERVER["PHP_SELF"] == "/index.php") { $homeAct = ' class="active"'; } else { $homeAct = ''; }
  if ($_SERVER["PHP_SELF"] == "/admin/index.php") { $adminAct = ' class="active"'; } else { $adminAct = ''; }  
  if ($_SERVER["PHP_SELF"] == "/admin/live_modem_log.php") { $adminAct = ' class="active"'; } else { $liveLogsAct = ''; }
  if ($_SERVER["PHP_SELF"] == "/admin/power.php") { $adminAct = ' class="active"'; } else { $powerAct = ''; }
  if ($_SERVER["PHP_SELF"] == "/admin/update.php") { $adminAct = ' class="active"'; } else { $updateAct = ''; }
  if ($_SERVER["PHP_SELF"] == "/admin/configure.php") { $configAct = ' class="active"'; } else { $configAct = ''; }

  echo "      <ul class=\"tabs group\">\n";
  echo "        <li".$homeAct."><a href=\"/\">Dashboard</a></li>\n";
  echo "        <li".$adminAct."><a href=\"/admin/index.php\">Admin</a></li>\n";
  echo "        <li".$liveLogsAct."><a href=\"/admin/live_modem_log.php\">Live Logs</a></li>\n";
  echo "        <li".$powerAct."><a href=\"/admin/power.php\">Power</a></li>\n";
  echo "        <li".$updateAct."><a href=\"/admin/update.php\">Update</a></li>\n";  
  echo "        <li".$configAct."><a href=\"/admin/configure.php\">Configure</a></li>\n";
  echo "      </ul>\n";
}
?>
