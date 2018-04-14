<?php
// Get the CPU temp and colour the box accordingly...
$cpuTempCRaw = exec('cat /sys/class/thermal/thermal_zone0/temp');
if ($cpuTempCRaw > 1000) { $cpuTempC = round($cpuTempCRaw / 1000, 1); } else { $cpuTempC = round($cpuTempCRaw, 1); }
$cpuTempF = round(+$cpuTempC * 9 / 5 + 32, 1);
if ($cpuTempC < 50)  { $cpuTempHTML = "<td style=\"background: #1d1\">".$cpuTempC."&deg;C / ".$cpuTempF."&deg;F</td>\n"; }
if ($cpuTempC >= 50) { $cpuTempHTML = "<td style=\"background: #fa0\">".$cpuTempC."&deg;C / ".$cpuTempF."&deg;F</td>\n"; }
if ($cpuTempC >= 69) { $cpuTempHTML = "<td style=\"background: #f00\">".$cpuTempC."&deg;C / ".$cpuTempF."&deg;F</td>\n"; }

// Load the pistar-release file
$pistarReleaseConfig = '/etc/pistar-release';
if (file_exists($pistarReleaseConfig)) {
	if (fopen($pistarReleaseConfig,'r')) { $configPistarRelease = parse_ini_file($pistarReleaseConfig, true); }
}

// Load the Pi-Star System Config
$pistarSystemConfigFile = '/usr/local/etc/pi-star/pi-star.ini';
if (file_exists($pistarSystemConfigFile)) {
	if (fopen($pistarSystemConfigFile,'r')) { $configPistarSystem = parse_ini_file($pistarSystemConfigFile, true); }
}

// Load the dstarrepeater config file
$dstarrepeaterConfigFile = '/usr/local/etc/config/dstarrepeater';
if (file_exists($dstarrepeaterConfigFile)) {
  $config_dstarrepeater = array();
  if ($config_dstarrepeaterfile = fopen($dstarrepeaterConfigFile,'r')) {
    while ($line1 = fgets($config_dstarrepeaterfile)) {
      if (strpos($line1, '=') !== false) {
       	list($key1,$value1) = preg_split('/=/', $line1, 2);
        $value1 = trim(str_replace('"','',$value1));
        if (strlen($value1) > 0)
        $config_dstarrepeater[$key1] = $value1;
      }
    }
    fclose($config_dstarrepeaterfile);
  }
}

// Load the ircDDBGateway config file
$ircddbgatewayConfigFile = '/usr/local/etc/config/ircddbgateway';
if (file_exists($ircddbgatewayConfigFile)) {
  $config_ircddbgateway = array();
  if ($configfile = fopen($ircddbgatewayConfigFile,'r')) {
    while ($line = fgets($configfile)) {
      if (strpos($line, '=') !== false) {
        list($key,$value) = preg_split('/=/', $line, 2);
        $value = trim(str_replace('"','',$value));
        if ($key != 'ircddbPassword' && strlen($value) > 0)
        $config_ircddbgateway[$key] = $value;
      }
    }
    fclose($configfile);
  }
}

// Load the mmdvmhost config file
$mmdvmConfigFile = '/usr/local/etc/config/mmdvmhost';
if (file_exists($mmdvmConfigFile)) {
	if (fopen($mmdvmConfigFile,'r')) { $config_mmdvmhost = parse_ini_file($mmdvmConfigFile, true); }
}

// Load the ysfgateway config file
$ysfgatewayConfigFile = '/usr/local/etc/config/ysfgateway';
if (file_exists($ysfgatewayConfigFile)) {
	if (fopen($ysfgatewayConfigFile,'r')) { $config_ysfgateway = parse_ini_file($ysfgatewayConfigFile, true); }
}

// Load the ysf2dmr config file
$ysf2dmrConfigFile = '/usr/local/etc/config/ysf2dmr';
if (file_exists($ysf2dmrConfigFile)) {
	if (fopen($ysf2dmrConfigFile,'r')) { $config_ysf2dmr = parse_ini_file($ysf2dmrConfigFile, true); }
}

// Load the p25gateway config file
$p25gatewayConfigFile = '/usr/local/etc/config/p25gateway';
if (file_exists($p25gatewayConfigFile)) {
	if (fopen($p25gatewayConfigFile,'r')) { $config_p25gateway = parse_ini_file($p25gatewayConfigFile, true); }
}

// Load the nxdngateway config file
$nxdngatewayConfigFile = '/usr/local/etc/config/nxdngateway';
if (file_exists($nxdngatewayConfigFile)) {
	if (fopen($nxdngatewayConfigFile,'r')) { $config_nxdngateway = parse_ini_file($nxdngatewayConfigFile, true); }
}

// Load the dmrgateway config file
$dmrGatewayConfigFile = '/usr/local/etc/config/dmrgateway';
if (file_exists($dmrGatewayConfigFile)) {
	if (fopen($dmrGatewayConfigFile,'r')) { $config_dmrgateway = parse_ini_file($dmrGatewayConfigFile, true); }
}

$progname = basename($_SERVER['SCRIPT_FILENAME'],".php");
$rev=$dashVersion;
$MYCALL=strtoupper($config_mmdvmhost['General']['Callsign']);
?>
<div class="contentwide">
<script type="text/javascript">
  function submitform()
  {
    document.getElementById("config").submit();
  }
  function submitPassform()
  {
    document.getElementById("adminPassForm").submit();
  }
  function factoryReset()
  {
    if (confirm('WARNING: This will set all your settings back to factory defaults. WiFi setup will be retained to maintain network access to this Pi.\n\nAre you SURE you want to do this?\n\nPress OK to restore the factory configuration\nPress Cancel to go back.')) {
      document.getElementById("factoryReset").submit();
    } else {
      return false;
    }
  }
  function resizeIframe(obj) {
    var numpix = parseInt(obj.contentWindow.document.body.scrollHeight, 10);
    obj.style.height = numpix + 'px';
  }
</script>

<?php
// Hardware Detail
if ($_SERVER["PHP_SELF"] == "/admin/configure.php") {
//HTML output starts here
?>
    <b><?php echo $lang['hardware_info'];?></b>
    <table style="table-layout: fixed;">
    <tr>
    <th><a class="tooltip" href="#"><?php echo $lang['hostname'];?><span><b>Hostname</b>The name of host<br />running the Pi-Star Software.</span></a></th>
    <th><a class="tooltip" href="#"><?php echo $lang['kernel'];?><span><b>Release</b>This is the version<br />number of the Linux Kernel running<br />on this Raspberry Pi.</span></a></th>
    <th colspan="2"><a class="tooltip" href="#"><?php echo $lang['platform'];?><span><b>Uptime:<br /><?php echo str_replace(',', ',<br />', exec('uptime -p'));?></b></span></a></th>
    <th><a class="tooltip" href="#"><?php echo $lang['cpu_load'];?><span><b>CPU Load</b>This is the standard Linux<br />system load indicator.</span></a></th>
    <th><a class="tooltip" href="#"><?php echo $lang['cpu_temp'];?><span><b>CPU Temp</b></span></a></th>
    </tr>
    <tr>
    <td><?php echo php_uname('n');?></td>
    <td><?php echo php_uname('r');?></td>
    <td colspan="2"><?php echo exec('platformDetect.sh');?></td>
    <td><?php echo $cpuLoad[0];?> / <?php echo $cpuLoad[1];?> / <?php echo $cpuLoad[2];?></td>
    <?php echo $cpuTempHTML; ?>
    </tr>
    </table>
<br />
<?php if (!empty($_POST)):
	// Make the root filesystem writable
	system('sudo mount -o remount,rw /');

	// Stop Cron (occasionally remounts root as RO - would be bad if it did this at the wrong time....)
	//system('sudo systemctl stop cron.service > /dev/null 2>/dev/null &');			//Cron

	// Stop the DV Services
	//system('sudo systemctl stop dstarrepeater.service > /dev/null 2>/dev/null &');		// D-Star Radio Service
	//system('sudo systemctl stop mmdvmhost.service > /dev/null 2>/dev/null &');		// MMDVMHost Radio Service
	//system('sudo systemctl stop ircddbgateway.service > /dev/null 2>/dev/null &');		// ircDDBGateway Service
	//system('sudo systemctl stop timeserver.service > /dev/null 2>/dev/null &');		// Time Server Service
	//system('sudo systemctl stop pistar-watchdog.service > /dev/null 2>/dev/null &');	// PiStar-Watchdog Service
	//system('sudo systemctl stop pistar-remote.service > /dev/null 2>/dev/null &');		// PiStar-Remote Service
	//system('sudo systemctl stop ysfgateway.service > /dev/null 2>/dev/null &');		// YSFGateway
	//system('sudo systemctl stop ysf2dmr.service > /dev/null 2>/dev/null &');		// YSF2DMR
	//system('sudo systemctl stop ysfparrot.service > /dev/null 2>/dev/null &');		// YSFParrot
	//system('sudo systemctl stop p25gateway.service > /dev/null 2>/dev/null &');		// P25Gateway
	//system('sudo systemctl stop p25parrot.service > /dev/null 2>/dev/null &');		// P25Parrot
	//system('sudo systemctl stop nxdngateway.service > /dev/null 2>/dev/null &');		// NXDNGateway
	//system('sudo systemctl stop nxdnparrot.service > /dev/null 2>/dev/null &');		// NXDNParrot
	//system('sudo systemctl stop dmrgateway.service > /dev/null 2>/dev/null &');		// DMRGateway

	echo "<table>\n";
	echo "<tr><th>Working...</th></tr>\n";
	echo "<tr><td>Stopping services and applying your configuration changes...</td></tr>\n";
	echo "</table>\n";

	// Let the services actualy stop
	sleep(1);


	// Factory Reset Handler Here
	if (empty($_POST['factoryReset']) != TRUE ) {
	  echo "<br />\n";
          echo "<table>\n";
          echo "<tr><th>Factory Reset Config</th></tr>\n";
          echo "<tr><td>Loading fresh configuration file(s)...</td><tr>\n";
          echo "</table>\n";
          unset($_POST);

	  // Over-write the config files with the clean copies
	  exec('sudo unzip -o /usr/local/bin/config_clean.zip -d /usr/local/etc/config/');
          echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
	  // Make the root filesystem read-only
          system('sudo mount -o remount,ro /');
	  echo "<br />\n</div>\n";
          echo "<div class=\"footer\">\nPi-Star web config, &copy; Andy Taylor (MW0MWZ) 2014-".date("Y").".<br />\n";
          echo "Need help? Click <a style=\"color: #ffffff;\" href=\"https://www.facebook.com/groups/pistar/\" target=\"_new\">here for the Support Group</a><br />\n";
          echo "Get your copy of Pi-Star from <a style=\"color: #ffffff;\" href=\"http://www.pistar.uk/downloads/\" target=\"_blank\">here</a>.<br />\n";
          echo "<br />\n</div>\n</div>\n</body>\n</html>\n";
	  die();
	  }

	// Handle the case where the config is not read correctly
	if (count($config_mmdvmhost) <= 18) {
	  echo "<br />\n";
	  echo "<table>\n";
	  echo "<tr><th>ERROR</th></tr>\n";
	  echo "<tr><td>Unable to read source configuration file(s)...</td><tr>\n";
	  echo "<tr><td>Please wait a few seconds and retry...</td></tr>\n";
	  echo "</table>\n";
	  unset($_POST);
	  echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
	  die();
	}

	// Change Radio Control Software
	if (empty($_POST['controllerSoft']) != TRUE ) {
	  if (escapeshellcmd($_POST['controllerSoft']) == 'DSTAR') { $configPistarSystem['software']['modemControlSoftware'] = "dstarrepeater"; }
	  if (escapeshellcmd($_POST['controllerSoft']) == 'MMDVM') { $configPistarSystem['software']['modemControlSoftware'] = "mmdvmhost"; }
	  }

	// HostAP
	if (empty($_POST['autoAP']) != TRUE ) {
	  if (escapeshellcmd($_POST['autoAP']) == 'OFF') { $configPistarSystem['wifi']['wifiAutoAP'] = "0"; }
	  if (escapeshellcmd($_POST['autoAP']) == 'ON') { $configPistarSystem['wifi']['wifiAutoAP'] = "1"; }
	}

	// Change Dashboard Language
	if (empty($_POST['dashboardLanguage']) != TRUE ) {
	  $rollDashLang = 'sudo sed -i "/pistarLanguage=/c\\$pistarLanguage=\''.escapeshellcmd($_POST['dashboardLanguage']).'\';" /var/www/dashboard/config/language.php';
	  system($rollDashLang);
	  }

	// Admin Password Change
	if (empty($_POST['adminPassword']) != TRUE ) {
	  $rollAdminPass0 = 'htpasswd -b /var/www/.htpasswd pi-star '.escapeshellcmd($_POST['adminPassword']);
	  system($rollAdminPass0);
	  $rollAdminPass2 = 'sudo echo -e "'.escapeshellcmd($_POST['adminPassword']).'\n'.escapeshellcmd($_POST['adminPassword']).'" | sudo passwd pi-star';
	  system($rollAdminPass2);
	  }

	// Set the ircDDBGAteway Remote Password and Port
	if (empty($_POST['confPassword']) != TRUE ) {
	  $rollConfPassword0 = 'sudo sed -i "/remotePassword=/c\\remotePassword='.escapeshellcmd($_POST['confPassword']).'" /usr/local/etc/config/ircddbgateway';
	  $rollConfPassword1 = 'sudo sed -i "/password=/c\\password='.escapeshellcmd($_POST['confPassword']).'" /root/.Remote\ Control';
	  $rollConfRemotePort = 'sudo sed -i "/port=/c\\port='.$config_ircddbgateway['remotePort'].'" /root/.Remote\ Control';
	  system($rollConfPassword0);
	  system($rollConfPassword1);
	  system($rollConfRemotePort);
	  }

	// Set the ircDDBGateway Defaut Reflector
	if (empty($_POST['confDefRef']) != TRUE ) {
	  if (stristr(strtoupper(escapeshellcmd($_POST['confDefRef'])), strtoupper(escapeshellcmd($_POST['confCallsign']))) != TRUE ) {
	    if (strlen($_POST['confDefRef']) != 7) {
		$targetRef = strtoupper(escapeshellcmd(str_pad($_POST['confDefRef'], 7, " ")));
	        } else {
		$targetRef = strtoupper(escapeshellcmd($_POST['confDefRef']));
	        }
	    $rollconfDefRef = 'sudo sed -i "/reflector1=/c\\reflector1='.$targetRef.escapeshellcmd($_POST['confDefRefLtr']).'" /usr/local/etc/config/ircddbgateway';
	    system($rollconfDefRef);
	    }
	  }

	// Set the ircDDBGAteway Defaut Reflector Autostart
	if (empty($_POST['confDefRefAuto']) != TRUE ) {
	  if (escapeshellcmd($_POST['confDefRefAuto']) == 'ON') {
	    $rollconfDefRefAuto = 'sudo sed -i "/atStartup1=/c\\atStartup1=1" /usr/local/etc/config/ircddbgateway';
	  }
	  if (escapeshellcmd($_POST['confDefRefAuto']) == 'OFF') {
	    $rollconfDefRefAuto = 'sudo sed -i "/atStartup1=/c\\atStartup1=0" /usr/local/etc/config/ircddbgateway';
	  }
	  system($rollconfDefRefAuto);
	  }

	// Set the Latitude
	if (empty($_POST['confLatitude']) != TRUE ) {
	  $newConfLatitude = preg_replace('/[^0-9\.\-]/', '', $_POST['confLatitude']);
	  $rollConfLat0 = 'sudo sed -i "/latitude=/c\\latitude='.$newConfLatitude.'" /usr/local/etc/config/ircddbgateway';
	  $rollConfLat1 = 'sudo sed -i "/latitude1=/c\\latitude1='.$newConfLatitude.'" /usr/local/etc/config/ircddbgateway';
	  $config_mmdvmhost['Info']['Latitude'] = $newConfLatitude;
	  $config_ysfgateway['Info']['Latitude'] = $newConfLatitude;
	  $config_ysf2dmr['Info']['Latitude'] = $newConfLatitude;
	  $config_dmrgateway['Info']['Latitude'] = $newConfLatitude;
	  system($rollConfLat0);
	  system($rollConfLat1);
	  }

	// Set the Longitude
	if (empty($_POST['confLongitude']) != TRUE ) {
	  $newConfLongitude = preg_replace('/[^0-9\.\-]/', '', $_POST['confLongitude']);
	  $rollConfLon0 = 'sudo sed -i "/longitude=/c\\longitude='.$newConfLongitude.'" /usr/local/etc/config/ircddbgateway';
	  $rollConfLon1 = 'sudo sed -i "/longitude1=/c\\longitude1='.$newConfLongitude.'" /usr/local/etc/config/ircddbgateway';
	  $config_mmdvmhost['Info']['Longitude'] = $newConfLongitude;
	  $config_ysfgateway['Info']['Longitude'] = $newConfLongitude;
	  $config_ysf2dmr['Info']['Longitude'] = $newConfLongitude;
	  $config_dmrgateway['Info']['Longitude'] = $newConfLongitude;
	  system($rollConfLon0);
	  system($rollConfLon1);
	  }

	// Set the Town
	if (empty($_POST['confDesc1']) != TRUE ) {
	  $newConfDesc1 = preg_replace('/[^A-Za-z0-9\.\s\,\-]/', '', $_POST['confDesc1']);
	  $rollDesc1 = 'sudo sed -i "/description1=/c\\description1='.$newConfDesc1.'" /usr/local/etc/config/ircddbgateway';
	  $rollDesc11 = 'sudo sed -i "/description1_1=/c\\description1_1='.$newConfDesc1.'" /usr/local/etc/config/ircddbgateway';
	  $config_mmdvmhost['Info']['Location'] = '"'.$newConfDesc1.'"';
	  $config_dmrgateway['Info']['Location'] = '"'.$newConfDesc1.'"';
          $config_ysfgateway['Info']['Name'] = '"'.$newConfDesc1.'"';
	  $config_ysf2dmr['Info']['Location'] = '"'.$newConfDesc1.'"';
	  system($rollDesc1);
	  system($rollDesc11);
	  }

	// Set the Country
	if (empty($_POST['confDesc2']) != TRUE ) {
	  $newConfDesc2 = preg_replace('/[^A-Za-z0-9\.\s\,\-]/', '', $_POST['confDesc2']);
	  $rollDesc2 = 'sudo sed -i "/description2=/c\\description2='.$newConfDesc2.'" /usr/local/etc/config/ircddbgateway';
	  $rollDesc22 = 'sudo sed -i "/description1_2=/c\\description1_2='.$newConfDesc2.'" /usr/local/etc/config/ircddbgateway';
          $config_mmdvmhost['Info']['Description'] = '"'.$newConfDesc2.'"';
	  $config_dmrgateway['Info']['Description'] = '"'.$newConfDesc2.'"';
          $config_ysfgateway['Info']['Description'] = '"'.$newConfDesc2.'"';
	  $config_ysf2dmr['Info']['Description'] = '"'.$newConfDesc2.'"';
	  system($rollDesc2);
	  system($rollDesc22);
	  }

	// Set the URL
	if (empty($_POST['confURL']) != TRUE ) {
	  $newConfURL = strtolower(preg_replace('/[^A-Za-z0-9\.\s\,\-\/\:]/', '', $_POST['confURL']));
	  if (escapeshellcmd($_POST['urlAuto']) == 'auto') { $txtURL = "http://www.qrz.com/db/".strtoupper(escapeshellcmd($_POST['confCallsign'])); }
	  if (escapeshellcmd($_POST['urlAuto']) == 'man')  { $txtURL = $newConfURL; }
	  if (escapeshellcmd($_POST['urlAuto']) == 'auto') { $rollURL0 = 'sudo sed -i "/url=/c\\url=http://www.qrz.com/db/'.strtoupper(escapeshellcmd($_POST['confCallsign'])).'" /usr/local/etc/config/ircddbgateway';  }
	  if (escapeshellcmd($_POST['urlAuto']) == 'man') { $rollURL0 = 'sudo sed -i "/url=/c\\url='.$newConfURL.'" /usr/local/etc/config/ircddbgateway'; }
          $config_mmdvmhost['Info']['URL'] = $txtURL;
	  $config_ysf2dmr['Info']['URL'] = $txtURL;
	  $config_dmrgateway['Info']['URL'] = $txtURL;
	  system($rollURL0);
	  }

	// Set the APRS Host for ircDDBGateway
	if (empty($_POST['selectedAPRSHost']) != TRUE ) {
	  $rollAPRSHost = 'sudo sed -i "/aprsHostname=/c\\aprsHostname='.escapeshellcmd($_POST['selectedAPRSHost']).'" /usr/local/etc/config/ircddbgateway';
	  system($rollAPRSHost);
	  $config_ysfgateway['aprs.fi']['Server'] = escapeshellcmd($_POST['selectedAPRSHost']);
	  $config_ysf2dmr['aprs.fi']['Server'] = escapeshellcmd($_POST['selectedAPRSHost']);
	  }

	// Set ircDDBGateway and TimeServer language
	if (empty($_POST['ircDDBGatewayAnnounceLanguage']) != TRUE) {
	  $ircDDBGatewayAnnounceLanguageArr = explode(',', escapeshellcmd($_POST['ircDDBGatewayAnnounceLanguage']));
	  $rollIrcDDBGatewayLang = 'sudo sed -i "/language=/c\\language='.escapeshellcmd($ircDDBGatewayAnnounceLanguageArr[0]).'" /usr/local/etc/config/ircddbgateway';
	  $rollTimeserverLang = 'sudo sed -i "/language=/c\\language='.escapeshellcmd($ircDDBGatewayAnnounceLanguageArr[1]).'" /usr/local/etc/config/timeserver';
	  system($rollIrcDDBGatewayLang);
	  system($rollTimeserverLang);
	}

	// Clear timeserver modules
	$rollTimeserverBandA = 'sudo sed -i "/sendA=/c\\sendA=0" /usr/local/etc/config/timeserver';
	$rollTimeserverBandB = 'sudo sed -i "/sendB=/c\\sendB=0" /usr/local/etc/config/timeserver';
	$rollTimeserverBandC = 'sudo sed -i "/sendC=/c\\sendC=0" /usr/local/etc/config/timeserver';
	$rollTimeserverBandD = 'sudo sed -i "/sendD=/c\\sendD=0" /usr/local/etc/config/timeserver';
	$rollTimeserverBandE = 'sudo sed -i "/sendE=/c\\sendE=0" /usr/local/etc/config/timeserver';
	system($rollTimeserverBandA);
	system($rollTimeserverBandB);
	system($rollTimeserverBandC);
	system($rollTimeserverBandD);
	system($rollTimeserverBandE);

	// Set the Frequency for Duplex
	if (empty($_POST['confFREQtx']) != TRUE && empty($_POST['confFREQrx']) != TRUE ) {
	  if (empty($_POST['confHardware']) != TRUE ) { $confHardware = escapeshellcmd($_POST['confHardware']); }
	  $newConfFREQtx = preg_replace('/[^0-9\.]/', '', $_POST['confFREQtx']);
	  $newConfFREQrx = preg_replace('/[^0-9\.]/', '', $_POST['confFREQrx']);
	  $newFREQtx = str_pad(str_replace(".", "", $newConfFREQtx), 9, "0");
	  $newFREQtx = mb_strimwidth($newFREQtx, 0, 9);
	  $newFREQrx = str_pad(str_replace(".", "", $newConfFREQrx), 9, "0");
	  $newFREQrx = mb_strimwidth($newFREQrx, 0, 9);
	  $newFREQirc = substr_replace($newFREQtx, '.', '3', 0);
	  $newFREQirc = mb_strimwidth($newFREQirc, 0, 9);
	  $rollFREQirc = 'sudo sed -i "/frequency1=/c\\frequency1='.$newFREQirc.'" /usr/local/etc/config/ircddbgateway';
	  $rollFREQdvap = 'sudo sed -i "/dvapFrequency=/c\\dvapFrequency='.$newFREQrx.'" /usr/local/etc/config/dstarrepeater';
	  $rollFREQdvmegaRx = 'sudo sed -i "/dvmegaRXFrequency=/c\\dvmegaRXFrequency='.$newFREQrx.'" /usr/local/etc/config/dstarrepeater';
	  $rollFREQdvmegaTx = 'sudo sed -i "/dvmegaTXFrequency=/c\\dvmegaTXFrequency='.$newFREQtx.'" /usr/local/etc/config/dstarrepeater';
	  $rollModeDuplex = 'sudo sed -i "/mode=/c\\mode=0" /usr/local/etc/config/dstarrepeater';
	  $config_mmdvmhost['Info']['RXFrequency'] = $newFREQrx;
	  $config_mmdvmhost['Info']['TXFrequency'] = $newFREQtx;
	  $config_dmrgateway['Info']['RXFrequency'] = $newFREQrx;
	  $config_dmrgateway['Info']['TXFrequency'] = $newFREQtx;
	  $config_ysfgateway['Info']['RXFrequency'] = $newFREQrx;
	  $config_ysfgateway['Info']['TXFrequency'] = $newFREQtx;
	  $config_ysf2dmr['Info']['RXFrequency'] = $newFREQrx;
	  $config_ysf2dmr['Info']['TXFrequency'] = $newFREQtx;

	  system($rollFREQirc);
	  system($rollFREQdvap);
	  system($rollFREQdvmegaRx);
	  system($rollFREQdvmegaTx);
	  system($rollModeDuplex);

	// Set RPT1 and RPT2
	  if (empty($_POST['confDStarModuleSuffix'])) {
	    if ($newFREQtx >= 1240000000 && $newFREQtx <= 1300000000) {
		$confRPT1 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ")."A";
		$confIRCrepeaterBand1 = "A";
		$config_mmdvmhost['D-Star']['Module'] = "A";
		$rollTimeserverBand = 'sudo sed -i "/sendA=/c\\sendA=1" /usr/local/etc/config/timeserver';
		system($rollTimeserverBand);
		}
	    if ($newFREQtx >= 420000000 && $newFREQtx <= 450000000) {
		$confRPT1 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ")."B";
		$confIRCrepeaterBand1 = "B";
		$config_mmdvmhost['D-Star']['Module'] = "B";
		$rollTimeserverBand = 'sudo sed -i "/sendB=/c\\sendB=1" /usr/local/etc/config/timeserver';
		system($rollTimeserverBand);
		}
	    if ($newFREQtx >= 218000000 && $newFREQtx <= 226000000) {
		$confRPT1 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ")."A";
		$confIRCrepeaterBand1 = "A";
		$config_mmdvmhost['D-Star']['Module'] = "A";
		$rollTimeserverBand = 'sudo sed -i "/sendA=/c\\sendA=1" /usr/local/etc/config/timeserver';
		system($rollTimeserverBand);
		}
	    if ($newFREQtx >= 144000000 && $newFREQtx <= 148000000) {
		$confRPT1 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ")."C";
		$confIRCrepeaterBand1 = "C";
		$config_mmdvmhost['D-Star']['Module'] = "C";
		$rollTimeserverBand = 'sudo sed -i "/sendC=/c\\sendC=1" /usr/local/etc/config/timeserver';
		system($rollTimeserverBand);
		}
	  }
	  else {
	     $confRPT1 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ").strtoupper(escapeshellcmd($_POST['confDStarModuleSuffix']));
	     $confIRCrepeaterBand1 = strtoupper(escapeshellcmd($_POST['confDStarModuleSuffix']));
	     $config_mmdvmhost['D-Star']['Module'] = strtoupper(escapeshellcmd($_POST['confDStarModuleSuffix']));
	     $rollTimeserverBand = 'sudo sed -i "/send'.strtoupper(escapeshellcmd($_POST['confDStarModuleSuffix'])).'=/c\\send'.strtoupper(escapeshellcmd($_POST['confDStarModuleSuffix'])).'=1" /usr/local/etc/config/timeserver';
	     system($rollTimeserverBand);
	  }

	  $newCallsignUpper = strtoupper(escapeshellcmd($_POST['confCallsign']));
	  $confRPT2 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ")."G";

	  $confRPT1 = strtoupper($confRPT1);
	  $confRPT2 = strtoupper($confRPT2);

	  $rollRPT1 = 'sudo sed -i "/callsign=/c\\callsign='.$confRPT1.'" /usr/local/etc/config/dstarrepeater';
	  $rollRPT2 = 'sudo sed -i "/gateway=/c\\gateway='.$confRPT2.'" /usr/local/etc/config/dstarrepeater';
	  $rollBEACONTEXT = 'sudo sed -i "/beaconText=/c\\beaconText='.$confRPT1.'" /usr/local/etc/config/dstarrepeater';
	  $rollIRCrepeaterBand1 = 'sudo sed -i "/repeaterBand1=/c\\repeaterBand1='.$confIRCrepeaterBand1.'" /usr/local/etc/config/ircddbgateway';
	  $rollIRCrepeaterCall1 = 'sudo sed -i "/repeaterCall1=/c\\repeaterCall1='.$newCallsignUpper.'" /usr/local/etc/config/ircddbgateway';

	  system($rollRPT1);
	  system($rollRPT2);
	  system($rollBEACONTEXT);
	  system($rollIRCrepeaterBand1);
	  system($rollIRCrepeaterCall1);
	}

	// Set the Frequency for Simplex
	if (empty($_POST['confFREQ']) != TRUE ) {
	  if (empty($_POST['confHardware']) != TRUE ) { $confHardware = escapeshellcmd($_POST['confHardware']); }
	  $newConfFREQ = preg_replace('/[^0-9\.]/', '', $_POST['confFREQ']);
	  $newFREQ = str_pad(str_replace(".", "", $newConfFREQ), 9, "0");
	  $newFREQ = mb_strimwidth($newFREQ, 0, 9);
	  $newFREQirc = substr_replace($newFREQ, '.', '3', 0);
	  $newFREQirc = mb_strimwidth($newFREQirc, 0, 9);
	  $rollFREQirc = 'sudo sed -i "/frequency1=/c\\frequency1='.$newFREQirc.'" /usr/local/etc/config/ircddbgateway';
	  $rollFREQdvap = 'sudo sed -i "/dvapFrequency=/c\\dvapFrequency='.$newFREQ.'" /usr/local/etc/config/dstarrepeater';
	  $rollFREQdvmegaRx = 'sudo sed -i "/dvmegaRXFrequency=/c\\dvmegaRXFrequency='.$newFREQ.'" /usr/local/etc/config/dstarrepeater';
	  $rollFREQdvmegaTx = 'sudo sed -i "/dvmegaTXFrequency=/c\\dvmegaTXFrequency='.$newFREQ.'" /usr/local/etc/config/dstarrepeater';
	  $rollModeSimplex = 'sudo sed -i "/mode=/c\\mode=1" /usr/local/etc/config/dstarrepeater';
	  $config_mmdvmhost['Info']['RXFrequency'] = $newFREQ;
	  $config_mmdvmhost['Info']['TXFrequency'] = $newFREQ;
	  $config_dmrgateway['Info']['RXFrequency'] = $newFREQ;
	  $config_dmrgateway['Info']['TXFrequency'] = $newFREQ;
	  $config_ysfgateway['Info']['RXFrequency'] = $newFREQ;
	  $config_ysfgateway['Info']['TXFrequency'] = $newFREQ;
	  $config_ysf2dmr['Info']['RXFrequency'] = $newFREQ;
	  $config_ysf2dmr['Info']['TXFrequency'] = $newFREQ;

	  system($rollFREQirc);
	  system($rollFREQdvap);
	  system($rollFREQdvmegaRx);
	  system($rollFREQdvmegaTx);
	  system($rollModeSimplex);

	// Set RPT1 and RPT2
	  if (empty($_POST['confDStarModuleSuffix'])) {
	    if ($newFREQ >= 1240000000 && $newFREQ <= 1300000000) {
		$confRPT1 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ")."A";
		$confIRCrepeaterBand1 = "A";
		$config_mmdvmhost['D-Star']['Module'] = "A";
		$rollTimeserverBand = 'sudo sed -i "/sendA=/c\\sendA=1" /usr/local/etc/config/timeserver';
		system($rollTimeserverBand);
		}
	    if ($newFREQ >= 420000000 && $newFREQ <= 450000000) {
		$confRPT1 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ")."B";
		$confIRCrepeaterBand1 = "B";
		$config_mmdvmhost['D-Star']['Module'] = "B";
		$rollTimeserverBand = 'sudo sed -i "/sendB=/c\\sendB=1" /usr/local/etc/config/timeserver';
		system($rollTimeserverBand);
		}
	    if ($newFREQ >= 218000000 && $newFREQ <= 226000000) {
		$confRPT1 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ")."A";
		$confIRCrepeaterBand1 = "A";
		$config_mmdvmhost['D-Star']['Module'] = "A";
		$rollTimeserverBand = 'sudo sed -i "/sendA=/c\\sendA=1" /usr/local/etc/config/timeserver';
		system($rollTimeserverBand);
		}
	    if ($newFREQ >= 144000000 && $newFREQ <= 148000000) {
		$confRPT1 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ")."C";
		$confIRCrepeaterBand1 = "C";
		$config_mmdvmhost['D-Star']['Module'] = "C";
		$rollTimeserverBand = 'sudo sed -i "/sendA=/c\\sendA=1" /usr/local/etc/config/timeserver';
		system($rollTimeserverBand);
		}
	  }
	  else {
	     $confRPT1 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ").strtoupper(escapeshellcmd($_POST['confDStarModuleSuffix']));
	     $confIRCrepeaterBand1 = strtoupper(escapeshellcmd($_POST['confDStarModuleSuffix']));
	     $config_mmdvmhost['D-Star']['Module'] = strtoupper(escapeshellcmd($_POST['confDStarModuleSuffix']));
	     $rollTimeserverBand = 'sudo sed -i "/send'.strtoupper(escapeshellcmd($_POST['confDStarModuleSuffix'])).'=/c\\send'.strtoupper(escapeshellcmd($_POST['confDStarModuleSuffix'])).'=1" /usr/local/etc/config/timeserver';
	     system($rollTimeserverBand);
	  }

	  $newCallsignUpper = strtoupper(escapeshellcmd($_POST['confCallsign']));
	  $confRPT2 = str_pad(escapeshellcmd($_POST['confCallsign']), 7, " ")."G";

	  $confRPT1 = strtoupper($confRPT1);
	  $confRPT2 = strtoupper($confRPT2);

	  $rollRPT1 = 'sudo sed -i "/callsign=/c\\callsign='.$confRPT1.'" /usr/local/etc/config/dstarrepeater';
	  $rollRPT2 = 'sudo sed -i "/gateway=/c\\gateway='.$confRPT2.'" /usr/local/etc/config/dstarrepeater';
	  $rollBEACONTEXT = 'sudo sed -i "/beaconText=/c\\beaconText='.$confRPT1.'" /usr/local/etc/config/dstarrepeater';
	  $rollIRCrepeaterBand1 = 'sudo sed -i "/repeaterBand1=/c\\repeaterBand1='.$confIRCrepeaterBand1.'" /usr/local/etc/config/ircddbgateway';
	  $rollIRCrepeaterCall1 = 'sudo sed -i "/repeaterCall1=/c\\repeaterCall1='.$newCallsignUpper.'" /usr/local/etc/config/ircddbgateway';

	  system($rollRPT1);
	  system($rollRPT2);
	  system($rollBEACONTEXT);
	  system($rollIRCrepeaterBand1);
	  system($rollIRCrepeaterCall1);
	  }

	// Set Callsign
	if (empty($_POST['confCallsign']) != TRUE ) {
	  $newCallsignUpper = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $_POST['confCallsign']));
	  if (preg_match("/^[0-9]/", $newCallsignUpper)) { $newCallsignUpperIRC = 'r'.$newCallsignUpper; } else { $newCallsignUpperIRC = $newCallsignUpper; }

	  $rollGATECALL = 'sudo sed -i "/gatewayCallsign=/c\\gatewayCallsign='.$newCallsignUpper.'" /usr/local/etc/config/ircddbgateway';
	  $rollDPLUSLOGIN = 'sudo sed -i "/dplusLogin=/c\\dplusLogin='.$newCallsignUpper.'" /usr/local/etc/config/ircddbgateway';
	  $rollDASHBOARDcall = 'sudo sed -i "/callsign=/c\\$callsign=\''.$newCallsignUpper.'\';" /var/www/dashboard/config/ircddblocal.php';
	  $rollTIMESERVERcall = 'sudo sed -i "/callsign=/c\\callsign='.$newCallsignUpper.'" /usr/local/etc/config/timeserver';
	  $rollSTARNETSERVERcall = 'sudo sed -i "/callsign=/c\\callsign='.$newCallsignUpper.'" /etc/starnetserver';
	  $rollSTARNETSERVERirc = 'sudo sed -i "/ircddbUsername=/c\\ircddbUsername='.$newCallsignUpperIRC.'" /etc/starnetserver';
	  $rollP25GATEWAY = 'sudo sed -i "/Callsign=/c\\Callsign='.$newCallsignUpper.'" /usr/local/etc/config/p25gateway';
	  $rollNXDNGATEWAY = 'sudo sed -i "/Callsign=/c\\Callsign='.$newCallsignUpper.'" /usr/local/etc/config/nxdngateway';

	  // Only roll ircDDBGateway Username if using OpenQuad
	  if ($config_ircddbgateway['ircddbHostname'] == "rr.openquad.net") {
		  $rollIRCUSER = 'sudo sed -i "/ircddbUsername=/c\\ircddbUsername='.$newCallsignUpperIRC.'" /usr/local/etc/config/ircddbgateway';
		  system($rollIRCUSER);
	  }

	  $config_mmdvmhost['General']['Callsign'] = $newCallsignUpper;
	  $config_ysfgateway['General']['Callsign'] = $newCallsignUpper;
	  $config_ysfgateway['aprs.fi']['Password'] = aprspass($newCallsignUpper);
	  $config_ysfgateway['aprs.fi']['Description'] = $newCallsignUpper."_Pi-Star";
	  $config_ysf2dmr['aprs.fi']['Password'] = aprspass($newCallsignUpper);
	  $config_ysf2dmr['aprs.fi']['Description'] = $newCallsignUpper."_Pi-Star";
	  $config_ysf2dmr['YSF Network']['Callsign'] = $newCallsignUpper;

	  system($rollGATECALL);
	  system($rollIRCUSER);
	  system($rollDPLUSLOGIN);
	  system($rollDASHBOARDcall);
	  system($rollTIMESERVERcall);
	  system($rollSTARNETSERVERcall);
	  system($rollSTARNETSERVERirc);
	  system($rollP25GATEWAY);
	  if (file_exists('/usr/local/etc/config/nxdngateway')) { system($rollNXDNGATEWAY); }

	}

	// Set the P25 Startup Host
	if (empty($_POST['p25StartupHost']) != TRUE ) {
          $newP25StartupHost = strtoupper(escapeshellcmd($_POST['p25StartupHost']));
          if ($newP25StartupHost === "NONE") { $rollP25Startup = 'sudo sed -i "/Startup=/c\\#Startup=" /usr/local/etc/config/p25gateway'; }
          else {
		  if (!isset($config_p25gateway['Network']['Startup'])) { $rollP25Startup = 'sudo echo "Startup='.$newP25StartupHost.'" >> /usr/local/etc/config/p25gateway'; }
		  else { $rollP25Startup = 'sudo sed -i "/Startup=/c\\Startup='.$newP25StartupHost.'" /usr/local/etc/config/p25gateway'; }
	  }
	  system($rollP25Startup);
	}

	// Set P25 NAC
	if (empty($_POST['p25nac']) != TRUE ) {
	  $p25nacNew = strtolower(escapeshellcmd($_POST['p25nac']));
	  if (preg_match('/[a-f0-9]{3}/', $p25nacNew)) {
	    $config_mmdvmhost['P25']['NAC'] = $p25nacNew;
	  }
	}

	// Set the NXDN Startup Host
	if (empty($_POST['nxdnStartupHost']) != TRUE ) {
	  $newNXDNStartupHost = strtoupper(escapeshellcmd($_POST['nxdnStartupHost']));
	  if (file_exists('/usr/local/etc/config/nxdngateway')) {
		if ($newNXDNStartupHost === "NONE") { $rollNXDNStartup = 'sudo sed -i "/Startup=/c\\#Startup=" /usr/local/etc/config/nxdngateway'; }
		else {
		if (!isset($config_nxdngateway['Network']['Startup'])) { $rollNXDNStartup = 'sudo echo "Startup='.$newNXDNStartupHost.'" >> /usr/local/etc/config/nxdngateway'; }
		else { $rollNXDNStartup = 'sudo sed -i "/Startup=/c\\Startup='.$newNXDNStartupHost.'" /usr/local/etc/config/nxdngateway'; }
	  	}
	  	system($rollNXDNStartup);
	  } else {
		$config_mmdvmhost['NXDN Network']['GatewayAddress'] = $newNXDNStartupHost;
		$config_mmdvmhost['NXDN Network']['GatewayPort'] = "41007";
	  }
	}

	// Set NXDN RAN
	if (empty($_POST['nxdnran']) != TRUE ) {
	  $nxdnranNew = strtolower(escapeshellcmd($_POST['nxdnran']));
	  if (preg_match('/[a-f0-9]{1}/', $nxdnranNew)) {
	    $config_mmdvmhost['NXDN']['RAN'] = $nxdnranNew;
	  }
	}

	// Set the YSF Startup Host
	if (empty($_POST['ysfStartupHost']) != TRUE ) {
	  $newYSFStartupHost = strtoupper(escapeshellcmd($_POST['ysfStartupHost']));
	  if ($newYSFStartupHost == "NONE") { unset($config_ysfgateway['Network']['Startup']); }
	  else { $config_ysfgateway['Network']['Startup'] = $newYSFStartupHost; }
	}

	// Set the YSF2DMR Master
	if (empty($_POST['ysf2dmrMasterHost']) != TRUE ) {
	  $ysf2dmrMasterHostArr = explode(',', escapeshellcmd($_POST['ysf2dmrMasterHost']));
	  $config_ysf2dmr['DMR Network']['Address'] = $ysf2dmrMasterHostArr[0];
	  $config_ysf2dmr['DMR Network']['Password'] = $ysf2dmrMasterHostArr[1];
	  $config_ysf2dmr['DMR Network']['Port'] = $ysf2dmrMasterHostArr[2];
	}

	// Set the YSF2DMR Starting TG
	if (empty($_POST['ysf2dmrTg']) != TRUE ) {
	  $ysf2dmrStartupDstId = preg_replace('/[^0-9]/', '', $_POST['ysf2dmrTg']);
	  $config_ysf2dmr['DMR Network']['StartupDstId'] = $ysf2dmrStartupDstId;
	}

	// Set Duplex
	if (empty($_POST['trxMode']) != TRUE ) {
	  if ($config_mmdvmhost['Info']['RXFrequency'] === $config_mmdvmhost['Info']['TXFrequency'] && $_POST['trxMode'] == "DUPLEX" ) {
	    $config_mmdvmhost['Info']['RXFrequency'] = $config_mmdvmhost['Info']['TXFrequency'] - 1;
	    }
	  if ($config_mmdvmhost['Info']['RXFrequency'] !== $config_mmdvmhost['Info']['TXFrequency'] && $_POST['trxMode'] == "SIMPLEX" ) {
	    $config_mmdvmhost['Info']['RXFrequency'] = $config_mmdvmhost['Info']['TXFrequency'];
	    }
	  if ($_POST['trxMode'] == "DUPLEX") {
	    $config_mmdvmhost['General']['Duplex'] = 1;
	    $config_mmdvmhost['DMR Network']['Slot1'] = '1';
	    $config_mmdvmhost['DMR Network']['Slot2'] = '1';
	  }
	  if ($_POST['trxMode'] == "SIMPLEX") {
	    $config_mmdvmhost['General']['Duplex'] = 0;
	    $config_mmdvmhost['DMR Network']['Slot1'] = '0';
	    $config_mmdvmhost['DMR Network']['Slot2'] = '1';
	  }
	}

	// Set DMR / CCS7 ID
	if (empty($_POST['dmrId']) != TRUE ) {
	  $newPostDmrId = preg_replace('/[^0-9]/', '', $_POST['dmrId']);
	  //$config_mmdvmhost['DMR']['Id'] = $newPostDmrId;
	  unset($config_mmdvmhost['DMR']['Id']);
	  if (empty($_POST['dmrMasterHost']) != TRUE ) {
		  $dmrMasterHostArrTest = explode(',', escapeshellcmd($_POST['dmrMasterHost']));
		  if (substr($dmrMasterHostArrTest[3], 0, 4) == 'DMR+') { $newPostDmrId = substr($newPostDmrId, 0, 7); }
	  }
	  $config_mmdvmhost['General']['Id'] = $newPostDmrId;
	  $config_dmrgateway['XLX Network']['Id'] = substr($newPostDmrId,0,7);
	  $config_dmrgateway['XLX Network 1']['Id'] = substr($newPostDmrId,0,7);
	  $config_dmrgateway['DMR Network 2']['Id'] = substr($newPostDmrId,0,7);
	}

	// Set YSF2DMR ID
	if (empty($_POST['ysf2dmrId']) != TRUE ) {
	  $newPostYsf2DmrId = preg_replace('/[^0-9]/', '', $_POST['ysf2dmrId']);	
	  $config_ysf2dmr['DMR Network']['Id'] = $newPostYsf2DmrId;
	}

	// Set NXDN ID
	if (empty($_POST['nxdnId']) != TRUE ) {
	  $newPostNxdnId = preg_replace('/[^0-9]/', '', $_POST['nxdnId']);
	  $config_mmdvmhost['NXDN']['Id'] = $newPostNxdnId;
	  if ($config_mmdvmhost['NXDN']['Id'] > 65535) { unset($config_mmdvmhost['NXDN']['Id']); }
	}

	// Set DMR Master Server
	if (empty($_POST['dmrMasterHost']) != TRUE ) {
	  $dmrMasterHostArr = explode(',', escapeshellcmd($_POST['dmrMasterHost']));
	  $config_mmdvmhost['DMR Network']['Address'] = $dmrMasterHostArr[0];
	  $config_mmdvmhost['DMR Network']['Password'] = '"'.$dmrMasterHostArr[1].'"';
	  $config_mmdvmhost['DMR Network']['Port'] = $dmrMasterHostArr[2];

		if (substr($dmrMasterHostArr[3], 0, 2) == "BM") {
			unset ($config_mmdvmhost['DMR Network']['Options']);
			unset ($config_dmrgateway['DMR Network 2']['Options']);
			unset ($config_mmdvmhost['DMR Network']['Local']);
			unset ($config_ysf2dmr['DMR Network']['Options']);
			unset ($config_ysf2dmr['DMR Network']['Local']);
		}

		if ($dmrMasterHostArr[0] == '127.0.0.1') {
			unset ($config_mmdvmhost['DMR Network']['Options']);
			unset ($config_dmrgateway['DMR Network 2']['Options']);
			$config_mmdvmhost['DMR Network']['Local'] = "62032";
			unset ($config_ysf2dmr['DMR Network']['Options']);
			$config_ysf2dmr['DMR Network']['Local'] = "62032";
		}

		// Set the DMR+ Options= line
		if (substr($dmrMasterHostArr[3], 0, 4) == "DMR+") {
			unset ($config_mmdvmhost['DMR Network']['Local']);
			unset ($config_ysf2dmr['DMR Network']['Local']);
			if (empty($_POST['dmrNetworkOptions']) != TRUE ) {
				$dmrOptionsLineStripped = str_replace('"', "", $_POST['dmrNetworkOptions']);
				$config_mmdvmhost['DMR Network']['Options'] = '"'.$dmrOptionsLineStripped.'"';
				$config_dmrgateway['DMR Network 2']['Options'] = '"'.$dmrOptionsLineStripped.'"';
			}
			else {
				unset ($config_mmdvmhost['DMR Network']['Options']);
				unset ($config_dmrgateway['DMR Network 2']['Options']);
				unset ($config_ysf2dmr['DMR Network']['Options']);
			}
		}
	}
	if (empty($_POST['dmrMasterHost']) == TRUE ) {
		unset ($config_mmdvmhost['DMR Network']['Options']);
		unset ($config_dmrgateway['DMR Network 2']['Options']);
	}
	if (empty($_POST['dmrMasterHost1']) != TRUE ) {
	  $dmrMasterHostArr1 = explode(',', escapeshellcmd($_POST['dmrMasterHost1']));
	  $config_dmrgateway['DMR Network 1']['Address'] = $dmrMasterHostArr1[0];
	  $config_dmrgateway['DMR Network 1']['Password'] = '"'.$dmrMasterHostArr1[1].'"';
	  $config_dmrgateway['DMR Network 1']['Port'] = $dmrMasterHostArr1[2];
	  $config_dmrgateway['DMR Network 1']['Name'] = $dmrMasterHostArr1[3];
	}
	if (empty($_POST['dmrMasterHost2']) != TRUE ) {
	  $dmrMasterHostArr2 = explode(',', escapeshellcmd($_POST['dmrMasterHost2']));
	  $config_dmrgateway['DMR Network 2']['Address'] = $dmrMasterHostArr2[0];
	  $config_dmrgateway['DMR Network 2']['Password'] = '"'.$dmrMasterHostArr2[1].'"';
	  $config_dmrgateway['DMR Network 2']['Port'] = $dmrMasterHostArr2[2];
	  $config_dmrgateway['DMR Network 2']['Name'] = $dmrMasterHostArr2[3];
	  if (empty($_POST['dmrNetworkOptions']) != TRUE ) {
	    $dmrOptionsLineStripped = str_replace('"', "", $_POST['dmrNetworkOptions']);
	    unset ($config_mmdvmhost['DMR Network']['Options']);
	    $config_dmrgateway['DMR Network 2']['Options'] = '"'.$dmrOptionsLineStripped.'"';
	  }
	  else {
		unset ($config_dmrgateway['DMR Network 2']['Options']);
	       }
	}
	if (empty($_POST['dmrMasterHost3']) != TRUE ) {
	  $dmrMasterHostArr3 = explode(',', escapeshellcmd($_POST['dmrMasterHost3']));
	  $config_dmrgateway['XLX Network 1']['Address'] = $dmrMasterHostArr3[0];
	  $config_dmrgateway['XLX Network 1']['Password'] = '"'.$dmrMasterHostArr3[1].'"';
	  $config_dmrgateway['XLX Network 1']['Port'] = $dmrMasterHostArr3[2];
	  $config_dmrgateway['XLX Network 1']['Name'] = $dmrMasterHostArr3[3];
	  $config_dmrgateway['XLX Network']['Startup'] = substr($dmrMasterHostArr3[3], 4);
	}

	// XLX StartUp TG
	if (empty($_POST['dmrMasterHost3Startup']) != TRUE ) {
	  $dmrMasterHost3Startup = escapeshellcmd($_POST['dmrMasterHost3Startup']);
	  if ($dmrMasterHost3Startup != "None") {
	    $config_dmrgateway['XLX Network 1']['Startup'] = $dmrMasterHost3Startup;
	  }
	  else { unset($config_dmrgateway['XLX Network 1']['Startup']); }
	}

	// Set JutterBuffer Option
	//if (empty($_POST['dmrDMRnetJitterBufer']) != TRUE ) {
	//  if (escapeshellcmd($_POST['dmrDMRnetJitterBufer']) == 'ON' ) { $config_mmdvmhost['DMR Network']['JitterEnabled'] = "1"; $config_ysf2dmr['DMR Network']['JitterEnabled'] = "1"; }
	//  if (escapeshellcmd($_POST['dmrDMRnetJitterBufer']) == 'OFF' ) { $config_mmdvmhost['DMR Network']['JitterEnabled'] = "0"; $config_ysf2dmr['DMR Network']['JitterEnabled'] = "0"; }
	//}
	unset($config_mmdvmhost['DMR Network']['JitterEnabled']);

	// Set Talker Alias Option
	if (empty($_POST['dmrEmbeddedLCOnly']) != TRUE ) {
	  if (escapeshellcmd($_POST['dmrEmbeddedLCOnly']) == 'ON' ) { $config_mmdvmhost['DMR']['EmbeddedLCOnly'] = "1"; }
	  if (escapeshellcmd($_POST['dmrEmbeddedLCOnly']) == 'OFF' ) { $config_mmdvmhost['DMR']['EmbeddedLCOnly'] = "0"; }
	}

	// Set Dump TA Data Option for GPS support
	if (empty($_POST['dmrDumpTAData']) != TRUE ) {
	  if (escapeshellcmd($_POST['dmrDumpTAData']) == 'ON' ) { $config_mmdvmhost['DMR']['DumpTAData'] = "1"; }
	  if (escapeshellcmd($_POST['dmrDumpTAData']) == 'OFF' ) { $config_mmdvmhost['DMR']['DumpTAData'] = "0"; }
	}

	// Set the XLX DMRGateway Master On or Off
	if (empty($_POST['dmrGatewayXlxEn']) != TRUE ) {
	  if (escapeshellcmd($_POST['dmrGatewayXlxEn']) == 'ON' ) { $config_dmrgateway['XLX Network 1']['Enabled'] = "1"; $config_dmrgateway['XLX Network']['Enabled'] = "1"; }
	  if (escapeshellcmd($_POST['dmrGatewayXlxEn']) == 'OFF' ) { $config_dmrgateway['XLX Network 1']['Enabled'] = "0"; $config_dmrgateway['XLX Network']['Enabled'] = "0"; }
	}

	// Remove old settings
	if (isset($config_mmdvmhost['General']['ModeHang'])) { unset($config_mmdvmhost['General']['ModeHang']); }
	if (isset($config_dmrgateway['General']['Timeout'])) { unset($config_dmrgateway['General']['Timeout']); }
	if (isset($config_mmdvmhost['General']['RFModeHang'])) { $config_mmdvmhost['General']['RFModeHang'] = 300; }
	if (isset($config_mmdvmhost['General']['NetModeHang'])) { $config_mmdvmhost['General']['NetModeHang'] = 300; }

	// Set DMR Hang Timers
	if (empty($_POST['dmrRfHangTime']) != TRUE ) {
	  $config_mmdvmhost['DMR']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['dmrRfHangTime']);
	  $config_dmrgateway['General']['RFTimeout'] = preg_replace('/[^0-9]/', '', $_POST['dmrRfHangTime']);
	}
	if (empty($_POST['dmrNetHangTime']) != TRUE ) {
	  $config_mmdvmhost['DMR Network']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['dmrNetHangTime']);
	  $config_dmrgateway['General']['NetTimeout'] = preg_replace('/[^0-9]/', '', $_POST['dmrNetHangTime']);
	}
	// Set D-Star Hang Timers
	if (empty($_POST['dstarRfHangTime']) != TRUE ) {
	  $config_mmdvmhost['D-Star']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['dstarRfHangTime']);
	}
	if (empty($_POST['dstarNetHangTime']) != TRUE ) {
	  $config_mmdvmhost['D-Star Network']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['dstarNetHangTime']);
	}
	// Set YSF Hang Timers
	if (empty($_POST['ysfRfHangTime']) != TRUE ) {
	  $config_mmdvmhost['System Fusion']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['ysfRfHangTime']);
	}
	if (empty($_POST['ysfNetHangTime']) != TRUE ) {
	  $config_mmdvmhost['System Fusion Network']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['ysfNetHangTime']);
	}
	// Set P25 Hang Timers
	if (empty($_POST['p25RfHangTime']) != TRUE ) {
	  $config_mmdvmhost['P25']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['p25RfHangTime']);
	}
	if (empty($_POST['p25NetHangTime']) != TRUE ) {
	  $config_mmdvmhost['P25 Network']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['p25NetHangTime']);
	}
	// Set NXDN Hang Timers
	if (empty($_POST['nxdnRfHangTime']) != TRUE ) {
	  $config_mmdvmhost['NXDN']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['nxdnRfHangTime']);
	}
	if (empty($_POST['nxdnNetHangTime']) != TRUE ) {
	  $config_mmdvmhost['NXDN Network']['ModeHang'] = preg_replace('/[^0-9]/', '', $_POST['nxdnNetHangTime']);
	}

	// Set the hardware type
	if (empty($_POST['confHardware']) != TRUE ) {
	$confHardware = escapeshellcmd($_POST['confHardware']);
	$configModem['Modem']['Hardware'] = $confHardware;

	  if ( $confHardware == 'dvmega-pi-single' ) {
	    $configPistarSystem['modem']['modemHardware'] = "dvmega-pi-single";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=DVMEGA" /usr/local/etc/config/dstarrepeater';
	    $rollDVMegaPort = 'sudo sed -i "/dvmegaPort=/c\\dvmegaPort=/dev/ttyAMA0" /usr/local/etc/config/dstarrepeater';
	    $rollDVMegaVariant = 'sudo sed -i "/dvmegaVariant=/c\\dvmegaVariant=2" /usr/local/etc/config/dstarrepeater';
	    $configmmdvm['Modem']['Port'] = "/dev/ttyAMA0";
	    system($rollModemType);
	    system($rollDVMegaPort);
	    system($rollDVMegaVariant);
	    $configmmdvm['General']['Duplex'] = 0;
	  }
	  if ( $confHardware == 'dvmega-pi-dual' ) {
	    $configPistarSystem['modem']['modemHardware'] = "dvmega-pi-dual";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=DVMEGA" /usr/local/etc/config/dstarrepeater';
	    $rollDVMegaPort = 'sudo sed -i "/dvmegaPort=/c\\dvmegaPort=/dev/ttyAMA0" /usr/local/etc/config/dstarrepeater';
	    $rollDVMegaVariant = 'sudo sed -i "/dvmegaVariant=/c\\dvmegaVariant=3" /usr/local/etc/config/dstarrepeater';
	    $configmmdvm['Modem']['Port'] = "/dev/ttyAMA0";
	    system($rollModemType);
	    system($rollDVMegaPort);
	    system($rollDVMegaVariant);
	    $configmmdvm['General']['Duplex'] = 0;
	  }
	  if ( $confHardware == 'dvmega-ardruino-dual' ) {
	    $configPistarSystem['modem']['modemHardware'] = "dvmega-ardruino-dual";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=DVMEGA" /usr/local/etc/config/dstarrepeater';
	    $rollDVMegaPort = 'sudo sed -i "/dvmegaPort=/c\\dvmegaPort=/dev/ttyUSB0" /usr/local/etc/config/dstarrepeater';
	    $rollDVMegaVariant = 'sudo sed -i "/dvmegaVariant=/c\\dvmegaVariant=3" /usr/local/etc/config/dstarrepeater';
            $configmmdvm['Modem']['Port'] = "/dev/ttyUSB0";
	    system($rollModemType);
	    system($rollDVMegaPort);
	    system($rollDVMegaVariant);
	    $configmmdvm['General']['Duplex'] = 0;
	  }
	  if ( $confHardware == 'dvmega-ardruino-dual-alt' ) {
	    $configPistarSystem['modem']['modemHardware'] = "dvmega-ardruino-dual-alt";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=DVMEGA" /usr/local/etc/config/dstarrepeater';
	    $rollDVMegaPort = 'sudo sed -i "/dvmegaPort=/c\\dvmegaPort=/dev/ttyACM0" /usr/local/etc/config/dstarrepeater';
	    $rollDVMegaVariant = 'sudo sed -i "/dvmegaVariant=/c\\dvmegaVariant=3" /usr/local/etc/config/dstarrepeater';
            $configmmdvm['Modem']['Port'] = "/dev/ttyACM0";
	    system($rollModemType);
	    system($rollDVMegaPort);
	    system($rollDVMegaVariant);
	    $configmmdvm['General']['Duplex'] = 0;
	  }
	  if ( $confHardware == 'dvmega-bluestack-single' ) {
	    $configPistarSystem['modem']['modemHardware'] = "dvmega-bluestack-single";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=DVMEGA" /usr/local/etc/config/dstarrepeater';
	    $rollDVMegaPort = 'sudo sed -i "/dvmegaPort=/c\\dvmegaPort=/dev/ttyUSB0" /usr/local/etc/config/dstarrepeater';
	    $rollDVMegaVariant = 'sudo sed -i "/dvmegaVariant=/c\\dvmegaVariant=2" /usr/local/etc/config/dstarrepeater';
            $configmmdvm['Modem']['Port'] = "/dev/ttyUSB0";
	    system($rollModemType);
	    system($rollDVMegaPort);
	    system($rollDVMegaVariant);
	    $configmmdvm['General']['Duplex'] = 0;
	  }
	  if ( $confHardware == 'dvmega-bluestack-dual' ) {
	    $configPistarSystem['modem']['modemHardware'] = "dvmega-bluestack-dual";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=DVMEGA" /usr/local/etc/config/dstarrepeater';
	    $rollDVMegaPort = 'sudo sed -i "/dvmegaPort=/c\\dvmegaPort=/dev/ttyUSB0" /usr/local/etc/config/dstarrepeater';
	    $rollDVMegaVariant = 'sudo sed -i "/dvmegaVariant=/c\\dvmegaVariant=3" /usr/local/etc/config/dstarrepeater';
            $configmmdvm['Modem']['Port'] = "/dev/ttyUSB0";
	    system($rollModemType);
	    system($rollDVMegaPort);
	    system($rollDVMegaVariant);
	    $configmmdvm['General']['Duplex'] = 0;
	  }
	  if ( $confHardware == 'dvmega-ardruino-gmsk' ) {
	    $configPistarSystem['modem']['modemHardware'] = "dvmega-ardruino-gmsk";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=DVMEGA" /usr/local/etc/config/dstarrepeater';
	    $rollDVMegaPort = 'sudo sed -i "/dvmegaPort=/c\\dvmegaPort=/dev/ttyUSB0" /usr/local/etc/config/dstarrepeater';
	    $rollDVMegaVariant = 'sudo sed -i "/dvmegaVariant=/c\\dvmegaVariant=0" /usr/local/etc/config/dstarrepeater';
            $configmmdvm['Modem']['Port'] = "/dev/ttyUSB0";
	    system($rollModemType);
	    system($rollDVMegaPort);
	    system($rollDVMegaVariant);
	  }
	  if ( $confHardware == 'dvmega-ardruino-gmsk-alt' ) {
	    $configPistarSystem['modem']['modemHardware'] = "dvmega-ardruino-gmsk-alt";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=DVMEGA" /usr/local/etc/config/dstarrepeater';
	    $rollDVMegaPort = 'sudo sed -i "/dvmegaPort=/c\\dvmegaPort=/dev/ttyACM0" /usr/local/etc/config/dstarrepeater';
	    $rollDVMegaVariant = 'sudo sed -i "/dvmegaVariant=/c\\dvmegaVariant=0" /usr/local/etc/config/dstarrepeater';
            $configmmdvm['Modem']['Port'] = "/dev/ttyACM0";
	    system($rollModemType);
	    system($rollDVMegaPort);
	    system($rollDVMegaVariant);
	  }
	  if ( $confHardware == 'dvr-ptr-v1' ) {
	    $configPistarSystem['modem']['modemHardware'] = "dvr-ptr-v1";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=DV-RPTR V1" /usr/local/etc/config/dstarrepeater';
	    $rollDVRPTRPort = 'sudo sed -i "/dvrptr1Port=/c\\dvrptr1Port=/dev/ttyACM0" /usr/local/etc/config/dstarrepeater';
            $configmmdvm['Modem']['Port'] = "/dev/ttyACM0";
	    system($rollModemType);
	    system($rollDVRPTRPort);
	  }
	  if ( $confHardware == 'dvr-ptr-v2' ) {
	    $configPistarSystem['modem']['modemHardware'] = "dvr-ptr-v2";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=DV-RPTR V2" /usr/local/etc/config/dstarrepeater';
	    $rollDVRPTRPort = 'sudo sed -i "/dvrptr1Port=/c\\dvrptr1Port=/dev/ttyACM0" /usr/local/etc/config/dstarrepeater';
            $configmmdvm['Modem']['Port'] = "/dev/ttyACM0";
	    system($rollModemType);
	    system($rollDVRPTRPort);
	  }
	  if ( $confHardware == 'dvr-ptr-v3' ) {
	    $configPistarSystem['modem']['modemHardware'] = "dvr-ptr-v3";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=DV-RPTR V3" /usr/local/etc/config/dstarrepeater';
	    $rollDVRPTRPort = 'sudo sed -i "/dvrptr1Port=/c\\dvrptr1Port=/dev/ttyACM0" /usr/local/etc/config/dstarrepeater';
            $configmmdvm['Modem']['Port'] = "/dev/ttyACM0";
	    system($rollModemType);
	    system($rollDVRPTRPort);
	  }
	  if ( $confHardware == 'gmsk-modem' ) {
	    $configPistarSystem['modem']['modemHardware'] = "gmsk-modem";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=GMSK Modem" /usr/local/etc/config/dstarrepeater';
	    system($rollModemType);
	  }
	  if ( $confHardware == 'dvap' ) {
	    $configPistarSystem['modem']['modemHardware'] = "dvap";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=DVAP" /usr/local/etc/config/dstarrepeater';
            $configmmdvm['Modem']['Port'] = "/dev/ttyUSB0";
	    system($rollModemType);
	  }
	  if ( $confHardware == 'zumspot-libre' ) {
	    $configPistarSystem['modem']['modemHardware'] = "zumspot-libre";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=MMDVM" /usr/local/etc/config/dstarrepeater';
	    system($rollModemType);
	    $configmmdvm['Modem']['Port'] = "/dev/ttyACM0";
	    $configmmdvm['General']['Duplex'] = 0;
	  }
	  if ( $confHardware == 'zumspot-usb' ) {
	    $configPistarSystem['modem']['modemHardware'] = "zumspot-usb";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=MMDVM" /usr/local/etc/config/dstarrepeater';
	    system($rollModemType);
	    $configmmdvm['Modem']['Port'] = "/dev/ttyACM0";
	    $configmmdvm['General']['Duplex'] = 0;
	  }
	  if ( $confHardware == 'zumspot-gpio' ) {
	    $configPistarSystem['modem']['modemHardware'] = "zumspot-gpio";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=MMDVM" /usr/local/etc/config/dstarrepeater';
	    system($rollModemType);
	    $configmmdvm['Modem']['Port'] = "/dev/ttyAMA0";
	    $configmmdvm['General']['Duplex'] = 0;
	  }
	  if ( $confHardware == 'zumradio-gpio' ) {
	    $configPistarSystem['modem']['modemHardware'] = "zumradio-usb";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=MMDVM" /usr/local/etc/config/dstarrepeater';
	    system($rollModemType);
	    $configmmdvm['Modem']['Port'] = "/dev/ttyAMA0";
	  }
	  if ( $confHardware == 'mmdvm-generic' ) {
	    $configPistarSystem['modem']['modemHardware'] = "mmdvm-generic";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=MMDVM" /usr/local/etc/config/dstarrepeater';
	    system($rollModemType);
            $configmmdvm['Modem']['Port'] = "/dev/ttyACM0";
	  }
	  if ( $confHardware == 'stm32dvm-gpio' ) {
	    $configPistarSystem['modem']['modemHardware'] = "stm32dvm-gpio";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=MMDVM" /usr/local/etc/config/dstarrepeater';
	    system($rollModemType);
	    $configmmdvm['Modem']['Port'] = "/dev/ttyAMA0";
	  }
	  if ( $confHardware == 'stm32dvm-usb' ) {
	    $configPistarSystem['modem']['modemHardware'] = "stm32dvm-usb";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=MMDVM" /usr/local/etc/config/dstarrepeater';
	    system($rollModemType);
	    $configmmdvm['Modem']['Port'] = "/dev/ttyUSB0";
	  }
	  if ( $confHardware == 'mmdvm-f4m-gpio' ) {
	    $configPistarSystem['modem']['modemHardware'] = "mmdvm-f4m-gpio";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=MMDVM" /usr/local/etc/config/dstarrepeater';
	    system($rollModemType);
	    $configmmdvm['Modem']['Port'] = "/dev/ttyAMA0";
	    $configmmdvm['General']['Duplex'] = 0;
	  }
	  if ( $confHardware == 'mmdvm-hshat-gpio' ) {
	    $configPistarSystem['modem']['modemHardware'] = "mmdvm-hshat-gpio";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=MMDVM" /usr/local/etc/config/dstarrepeater';
	    system($rollModemType);
	    $configmmdvm['Modem']['Port'] = "/dev/ttyAMA0";
	    $configmmdvm['General']['Duplex'] = 0;
	  }
	  if ( $confHardware == 'mmdvm-hshat-dual-gpio' ) {
	    $configPistarSystem['modem']['modemHardware'] = "mmdvm-hshat-dual-gpio";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=MMDVM" /usr/local/etc/config/dstarrepeater';
	    system($rollModemType);
	    $configmmdvm['Modem']['Port'] = "/dev/ttyAMA0";
	    $configmmdvm['General']['Duplex'] = 1;
	  }
	  if ( $confHardware == 'mmdvm-mdo-gpio' ) {
	    $configPistarSystem['modem']['modemHardware'] = "mmdvm-mdo-gpio";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=MMDVM" /usr/local/etc/config/dstarrepeater';
	    system($rollModemType);
	    $configmmdvm['Modem']['Port'] = "/dev/ttyAMA0";
	    $configmmdvm['General']['Duplex'] = 0;
	  }
	  if ( $confHardware == 'mmdvm-vye-gpio' ) {
	    $configPistarSystem['modem']['modemHardware'] = "mmdvm-vye-gpio";
	    $rollModemType = 'sudo sed -i "/modemType=/c\\modemType=MMDVM" /usr/local/etc/config/dstarrepeater';
	    system($rollModemType);
	    $configmmdvm['Modem']['Port'] = "/dev/ttyAMA0";
	    $configmmdvm['General']['Duplex'] = 0;
	  }
	}

	// Set the Dashboard Public
	if (empty($_POST['dashAccess']) != TRUE ) {
	  $publicDashboard = 'sudo sed -i \'/$DAEMON -a $ipVar 80/c\\\t\t$DAEMON -a $ipVar 80 80 TCP > /dev/null 2>&1 &\' /usr/local/sbin/pistar-upnp.service';
	  $privateDashboard = 'sudo sed -i \'/$DAEMON -a $ipVar 80/ s/^#*/#/\' /usr/local/sbin/pistar-upnp.service';

	  if (escapeshellcmd($_POST['dashAccess']) == 'PUB' ) { system($publicDashboard); }
	  if (escapeshellcmd($_POST['dashAccess']) == 'PRV' ) { system($privateDashboard); }
	}

	// Set the ircDDBGateway Remote Public
	if (empty($_POST['ircRCAccess']) != TRUE ) {
	  $publicRCirc = 'sudo sed -i \'/$DAEMON -a $ipVar 10022/c\\\t\t$DAEMON -a $ipVar 10022 10022 UDP > /dev/null 2>&1 &\' /usr/local/sbin/pistar-upnp.service';
	  $privateRCirc = 'sudo sed -i \'/$DAEMON -a $ipVar 10022/ s/^#*/#/\' /usr/local/sbin/pistar-upnp.service';

	  if (escapeshellcmd($_POST['ircRCAccess']) == 'PUB' ) { system($publicRCirc); }
	  if (escapeshellcmd($_POST['ircRCAccess']) == 'PRV' ) { system($privateRCirc); }
	}

	// Set SSH Access Public
	if (empty($_POST['sshAccess']) != TRUE ) {
	  $publicSSH = 'sudo sed -i \'/$DAEMON -a $ipVar 22/c\\\t\t$DAEMON -a $ipVar 22 22 TCP > /dev/null 2>&1 &\' /usr/local/sbin/pistar-upnp.service';
	  $privateSSH = 'sudo sed -i \'/$DAEMON -a $ipVar 22/ s/^#*/#/\' /usr/local/sbin/pistar-upnp.service';

	  if (escapeshellcmd($_POST['sshAccess']) == 'PUB' ) { system($publicSSH); }
	  if (escapeshellcmd($_POST['sshAccess']) == 'PRV' ) { system($privateSSH); }
	}

	// D-Star Time Announce
	if (empty($_POST['confTimeAnnounce']) != TRUE ) {
	  if (escapeshellcmd($_POST['confTimeAnnounce']) == 'ON' )  { $configPistarSystem['d-star']['dstarTimeAnnounce'] = "1"; }
	  if (escapeshellcmd($_POST['confTimeAnnounce']) == 'OFF' )  { $configPistarSystem['d-star']['dstarTimeAnnounce'] = "0"; }
	}

	// Set MMDVMHost DMR Mode
	if (empty($_POST['MMDVMModeDMR']) != TRUE ) {
	  if (escapeshellcmd($_POST['MMDVMModeDMR']) == 'ON' )  { $config_mmdvmhost['DMR']['Enable'] = "1"; $config_mmdvmhost['DMR Network']['Enable'] = "1"; $configPistarSystem['modes']['modeDmrEnable'] = "1"; }
	  if (escapeshellcmd($_POST['MMDVMModeDMR']) == 'OFF' ) { $config_mmdvmhost['DMR']['Enable'] = "0"; $config_mmdvmhost['DMR Network']['Enable'] = "0"; $configPistarSystem['modes']['modeDmrEnable'] = "0"; }
	}

	// Set MMDVMHost D-Star Mode
	if (empty($_POST['MMDVMModeDSTAR']) != TRUE ) {
          if (escapeshellcmd($_POST['MMDVMModeDSTAR']) == 'ON' )  { $config_mmdvmhost['D-Star']['Enable'] = "1"; $config_mmdvmhost['D-Star Network']['Enable'] = "1"; $configPistarSystem['modes']['modeDStarEnable'] = "1"; }
          if (escapeshellcmd($_POST['MMDVMModeDSTAR']) == 'OFF' ) { $config_mmdvmhost['D-Star']['Enable'] = "0"; $config_mmdvmhost['D-Star Network']['Enable'] = "0"; $configPistarSystem['modes']['modeDStarEnable'] = "0"; }
	}

	// Set MMDVMHost Fusion Mode
	if (empty($_POST['MMDVMModeFUSION']) != TRUE ) {
          if (escapeshellcmd($_POST['MMDVMModeFUSION']) == 'ON' )  { $config_mmdvmhost['System Fusion']['Enable'] = "1"; $config_mmdvmhost['System Fusion Network']['Enable'] = "1"; $configPistarSystem['modes']['modeYSFEnable'] = "1"; }
          if (escapeshellcmd($_POST['MMDVMModeFUSION']) == 'OFF' ) { $config_mmdvmhost['System Fusion']['Enable'] = "0"; $config_mmdvmhost['System Fusion Network']['Enable'] = "0"; $configPistarSystem['modes']['modeYSFEnable'] = "0"; }
	}

	// Set MMDVMHost P25 Mode
	if (empty($_POST['MMDVMModeP25']) != TRUE ) {
          if (escapeshellcmd($_POST['MMDVMModeP25']) == 'ON' )  { $config_mmdvmhost['P25']['Enable'] = "1"; $config_mmdvmhost['P25 Network']['Enable'] = "1"; $configPistarSystem['modes']['modeP25Enable'] = "1"; }
          if (escapeshellcmd($_POST['MMDVMModeP25']) == 'OFF' ) { $config_mmdvmhost['P25']['Enable'] = "0"; $config_mmdvmhost['P25 Network']['Enable'] = "0"; $configPistarSystem['modes']['modeP25Enable'] = "0"; }
	}
	
	// Set MMDVMHost NXDN Mode
	if (empty($_POST['MMDVMModeNXDN']) != TRUE ) {
          if (escapeshellcmd($_POST['MMDVMModeNXDN']) == 'ON' )  { $config_mmdvmhost['NXDN']['Enable'] = "1"; $config_mmdvmhost['NXDN Network']['Enable'] = "1"; $configPistarSystem['modes']['modeNXDNEnable'] = "1"; }
          if (escapeshellcmd($_POST['MMDVMModeNXDN']) == 'OFF' ) { $config_mmdvmhost['NXDN']['Enable'] = "0"; $config_mmdvmhost['NXDN Network']['Enable'] = "0"; $configPistarSystem['modes']['modeNXDNEnable'] = "0"; }
	}

	// Set YSF2DMR Mode
	if (empty($_POST['MMDVMModeYSF2DMR']) != TRUE ) {
          if (escapeshellcmd($_POST['MMDVMModeYSF2DMR']) == 'ON' )  { $config_ysf2dmr['Enabled']['Enabled'] = "1"; $configPistarSystem['modes']['modeYSF2DMREnable'] = "1"; }
          if (escapeshellcmd($_POST['MMDVMModeYSF2DMR']) == 'OFF' ) { $config_ysf2dmr['Enabled']['Enabled'] = "0"; $configPistarSystem['modes']['modeYSF2DMREnable'] = "0"; }
	}

	// Set the MMDVMHost Display Type
	if  (empty($_POST['mmdvmDisplayType']) != TRUE ) {
	  $config_mmdvmhost['General']['Display'] = escapeshellcmd($_POST['mmdvmDisplayType']);
	}

	// Set the MMDVMHost Display Type
	if  (empty($_POST['mmdvmDisplayPort']) != TRUE ) {
	  $config_mmdvmhost['TFT Serial']['Port'] = $_POST['mmdvmDisplayPort'];
	  $config_mmdvmhost['Nextion']['Port'] = $_POST['mmdvmDisplayPort'];
	}

	// Set the Nextion Display Layout
	if (empty($_POST['mmdvmNextionDisplayType']) != TRUE ) {
	  if (escapeshellcmd($_POST['mmdvmNextionDisplayType']) == "G4KLX") { $config_mmdvmhost['Nextion']['ScreenLayout'] = "0"; }
	  if (escapeshellcmd($_POST['mmdvmNextionDisplayType']) == "ON7LDS") { $config_mmdvmhost['Nextion']['ScreenLayout'] = "2"; }
	}

	// Set MMDVMHost DMR Colour Code
	if (empty($_POST['dmrColorCode']) != TRUE ) {
          $config_mmdvmhost['DMR']['ColorCode'] = escapeshellcmd($_POST['dmrColorCode']);
	}

	// Set Node Lock Status
	if (empty($_POST['nodeMode']) != TRUE ) {
	  if (escapeshellcmd($_POST['nodeMode']) == 'prv' ) {
            $config_mmdvmhost['DMR']['SelfOnly'] = 1;
            $config_mmdvmhost['D-Star']['SelfOnly'] = 1;
	    $config_mmdvmhost['System Fusion']['SelfOnly'] = 1;
	    $config_mmdvmhost['P25']['SelfOnly'] = 1;
	    $config_mmdvmhost['NXDN']['SelfOnly'] = 1;
            system('sudo sed -i "/restriction=/c\\restriction=1" /usr/local/etc/config/dstarrepeater');
          }
	  if (escapeshellcmd($_POST['nodeMode']) == 'pub' ) {
            $config_mmdvmhost['DMR']['SelfOnly'] = 0;
            $config_mmdvmhost['D-Star']['SelfOnly'] = 0;
	    $config_mmdvmhost['System Fusion']['SelfOnly'] = 0;
	    $config_mmdvmhost['P25']['SelfOnly'] = 0;
	    $config_mmdvmhost['NXDN']['SelfOnly'] = 0;
            system('sudo sed -i "/restriction=/c\\restriction=0" /usr/local/etc/config/dstarrepeater');
          }
	}

	// Set the Hostname
	if (empty($_POST['confHostame']) != TRUE ) {
	  $newHostnameLower = strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', $_POST['confHostame']));
	  $currHostname = exec('cat /etc/hostname');
	  $rollHostname = 'sudo sed -i "s/'.$currHostname.'/'.$newHostnameLower.'/" /etc/hostname';
	  $rollHosts = 'sudo sed -i "s/'.$currHostname.'/'.$newHostnameLower.'/" /etc/hosts';
	  $rollMotd = 'sudo sed -i "s/'.$currHostname.'/'.$newHostnameLower.'/" /etc/motd';
	  system($rollHostname);
	  system($rollHosts);
	  system($rollMotd);
	  if (file_exists('/etc/hostapd/hostapd.conf')) {
		  // Update the Hotspot name to the Hostname
		  $rollApSsid = 'sudo sed -i "/ssid=/c\\ssid='.$newHostnameLower.'" /etc/hostapd/hostapd.conf';
		  system($rollApSsid);
	  }
	}
	
	// NoDextra config
	if (empty($_POST['confHostFilesNoDExtra']) != TRUE ) {
		if (escapeshellcmd($_POST['confHostFilesNoDExtra']) == 'ON' )  {
			$configPistarSystem['d-star']['dstarNoDExtra'] = "1";
		}
		if (escapeshellcmd($_POST['confHostFilesNoDExtra']) == 'OFF' )  {
			$configPistarSystem['d-star']['dstarNoDExtra'] = "0";
		}
	}

	// Continue Page Output
	echo "<br />";
	echo "<table>\n";
	echo "<tr><th>Done...</th></tr>\n";
	echo "<tr><td>Changes applied, starting services...</td></tr>\n";
	echo "</table>\n";

	// MMDVMHost config file wrangling
	$mmdvmContent = "";
	foreach($config_mmdvmhost as $mmdvmSection=>$mmdvmValues) {
		// UnBreak special cases
		$mmdvmSection = str_replace("_", " ", $mmdvmSection);
		$mmdvmContent .= "[".$mmdvmSection."]\n";
                // append the values
                foreach($mmdvmValues as $mmdvmKey=>$mmdvmValue) {
			$mmdvmContent .= $mmdvmKey."=".$mmdvmValue."\n";
			}
			$mmdvmContent .= "\n";
		}

	if (!$handleMMDVMHostConfig = fopen('/tmp/bW1kdm1ob3N0DQo.tmp', 'w')) {
		return false;
	}
	if (!is_writable('/tmp/bW1kdm1ob3N0DQo.tmp')) {
          echo "<br />\n";
          echo "<table>\n";
          echo "<tr><th>ERROR</th></tr>\n";
          echo "<tr><td>Unable to write configuration file(s)...</td><tr>\n";
          echo "<tr><td>Please wait a few seconds and retry...</td></tr>\n";
          echo "</table>\n";
          unset($_POST);
          echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
          die();
	}
	else {
		$success = fwrite($handleMMDVMHostConfig, $mmdvmContent);
		fclose($handleMMDVMHostConfig);
		if (intval(exec('cat /tmp/bW1kdm1ob3N0DQo.tmp | wc -l')) > 140 ) {
			exec('sudo mv /tmp/bW1kdm1ob3N0DQo.tmp /usr/local/etc/config/mmdvmhost');		// Move the file back
			exec('sudo chmod 644 /usr/local/etc/config/mmdvmhost');					// Set the correct runtime permissions
			exec('sudo chown root:root /usr/local/etc/config/mmdvmhost');				// Set the owner
		}
	}

        // ysfgateway config file wrangling
	$ysfgwContent = "";
        foreach($config_ysfgateway as $ysfgwSection=>$ysfgwValues) {
                // UnBreak special cases
                $ysfgwSection = str_replace("_", " ", $ysfgwSection);
                $ysfgwContent .= "[".$ysfgwSection."]\n";
                // append the values
                foreach($ysfgwValues as $ysfgwKey=>$ysfgwValue) {
                        $ysfgwContent .= $ysfgwKey."=".$ysfgwValue."\n";
                        }
                        $ysfgwContent .= "\n";
                }

        if (!$handleYSFGWconfig = fopen('/tmp/eXNmZ2F0ZXdheQ.tmp', 'w')) {
                return false;
        }

	if (!is_writable('/tmp/eXNmZ2F0ZXdheQ.tmp')) {
          echo "<br />\n";
          echo "<table>\n";
          echo "<tr><th>ERROR</th></tr>\n";
          echo "<tr><td>Unable to write configuration file(s)...</td><tr>\n";
          echo "<tr><td>Please wait a few seconds and retry...</td></tr>\n";
          echo "</table>\n";
          unset($_POST);
          echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
          die();
	}
	else {
	        $success = fwrite($handleYSFGWconfig, $ysfgwContent);
	        fclose($handleYSFGWconfig);
		if (intval(exec('cat /tmp/eXNmZ2F0ZXdheQ.tmp | wc -l')) > 35 ) {
			exec('sudo mv /tmp/eXNmZ2F0ZXdheQ.tmp /usr/local/etc/config/ysfgateway');		// Move the file back
			exec('sudo chmod 644 /usr/local/etc/config/ysfgateway');					// Set the correct runtime permissions
			exec('sudo chown root:root /usr/local/etc/config/ysfgateway');				// Set the owner
		}
	}

        // ysf2dmr config file wrangling
        $ysf2dmrContent = "";
        foreach($config_ysf2dmr as $ysf2dmrSection=>$ysf2dmrValues) {
                // UnBreak special cases
                $ysf2dmrSection = str_replace("_", " ", $ysf2dmrSection);
                $ysf2dmrContent .= "[".$ysf2dmrSection."]\n";
                // append the values
                foreach($ysf2dmrValues as $ysf2dmrKey=>$ysf2dmrValue) {
                        $ysf2dmrContent .= $ysf2dmrKey."=".$ysf2dmrValue."\n";
                        }
                        $ysf2dmrContent .= "\n";
                }

        if (!$handleYSF2DMRconfig = fopen('/tmp/dsWGR34tHRrSFFGA.tmp', 'w')) {
                return false;
        }

        if (!is_writable('/tmp/dsWGR34tHRrSFFGA.tmp')) {
          echo "<br />\n";
          echo "<table>\n";
          echo "<tr><th>ERROR</th></tr>\n";
          echo "<tr><td>Unable to write configuration file(s)...</td><tr>\n";
          echo "<tr><td>Please wait a few seconds and retry...</td></tr>\n";
          echo "</table>\n";
          unset($_POST);
          echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
          die();
        }
        else {
                $success = fwrite($handleYSF2DMRconfig, $ysf2dmrContent);
                fclose($handleYSF2DMRconfig);
                if (intval(exec('cat /tmp/dsWGR34tHRrSFFGA.tmp | wc -l')) > 35 ) {
                        exec('sudo mv /tmp/dsWGR34tHRrSFFGA.tmp /usr/local/etc/config/ysf2dmr');                 // Move the file back
                        exec('sudo chmod 644 /usr/local/etc/config/ysf2dmr');                                    // Set the correct runtime permissions
                        exec('sudo chown root:root /usr/local/etc/config/ysf2dmr');                              // Set the owner
                }
        }

	// dmrgateway config file wrangling
	$dmrgwContent = "";
        foreach($config_dmrgateway as $dmrgwSection=>$dmrgwValues) {
                // UnBreak special cases
                $dmrgwSection = str_replace("_", " ", $dmrgwSection);
                $dmrgwContent .= "[".$dmrgwSection."]\n";
                // append the values
                foreach($dmrgwValues as $dmrgwKey=>$dmrgwValue) {
                        $dmrgwContent .= $dmrgwKey."=".$dmrgwValue."\n";
                        }
                        $dmrgwContent .= "\n";
                }
        if (!$handledmrGWconfig = fopen('/tmp/k4jhdd34jeFr8f.tmp', 'w')) {
                return false;
        }
	if (!is_writable('/tmp/k4jhdd34jeFr8f.tmp')) {
          echo "<br />\n";
          echo "<table>\n";
          echo "<tr><th>ERROR</th></tr>\n";
          echo "<tr><td>Unable to write configuration file(s)...</td><tr>\n";
          echo "<tr><td>Please wait a few seconds and retry...</td></tr>\n";
          echo "</table>\n";
          unset($_POST);
          echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
          die();
	}
	else {
	        $success = fwrite($handledmrGWconfig, $dmrgwContent);
	        fclose($handledmrGWconfig);
		if (fopen($dmrGatewayConfigFile,'r')) {
			if (intval(exec('cat /tmp/k4jhdd34jeFr8f.tmp | wc -l')) > 55 ) {
          			exec('sudo mv /tmp/k4jhdd34jeFr8f.tmp /usr/local/etc/config/dmrgateway');	// Move the file back
          			exec('sudo chmod 644 /usr/local/etc/config/dmrgateway');				// Set the correct runtime permissions
	 			exec('sudo chown root:root /usr/local/etc/config/dmrgateway');			// Set the owner
			}
		}
	}

	// modem config file wrangling
        $configPistarSystemContent = "";
        foreach($configPistarSystem as $configPistarSystemSection=>$configPistarSystemValues) {
                // UnBreak special cases
                $configPistarSystemSection = str_replace("_", " ", $configPistarSystemSection);
                $configPistarSystemContent .= "[".$configPistarSystemSection."]\n";
                // append the values
                foreach($configPistarSystemValues as $modemKey=>$modemValue) {
                        $configPistarSystemContent .= $modemKey."=".$modemValue."\n";
                        }
                        $configPistarSystemContent .= "\n";
                }
        if (!$handleModemConfig = fopen('/tmp/sja7hFRkw4euG7.tmp', 'w')) {
                return false;
        }
        if (!is_writable('/tmp/sja7hFRkw4euG7.tmp')) {
          echo "<br />\n";
          echo "<table>\n";
          echo "<tr><th>ERROR</th></tr>\n";
          echo "<tr><td>Unable to write configuration file(s)...</td><tr>\n";
          echo "<tr><td>Please wait a few seconds and retry...</td></tr>\n";
          echo "</table>\n";
          unset($_POST);
          echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},5000);</script>';
          die();
        }
	else {
                $success = fwrite($handleModemConfig, $configPistarSystemContent);
                fclose($handleModemConfig);
                if (fopen('/usr/local/etc/pi-star/pi-star.ini','r')) {
                    exec('sudo mv /tmp/sja7hFRkw4euG7.tmp /usr/local/etc/pi-star/pi-star.ini');	// Move the file back
                    exec('sudo chmod 644 /usr/local/etc/pi-star/pi-star.ini');			// Set the correct runtime permissions
                    exec('sudo chown root:root /usr/local/etc/pi-star/pi-star.ini');		// Set the owner
                }
        }
	
	// Start the DV Services
	//system('sudo systemctl daemon-reload > /dev/null 2>/dev/null &');			// Restart Systemd to account for any service changes
	//system('sudo systemctl start dstarrepeater.service > /dev/null 2>/dev/null &');		// D-Star Radio Service
	//system('sudo systemctl start mmdvmhost.service > /dev/null 2>/dev/null &');		// MMDVMHost Radio Service
	//system('sudo systemctl start ircddbgateway.service > /dev/null 2>/dev/null &');		// ircDDBGateway Service
	//system('sudo systemctl start timeserver.service > /dev/null 2>/dev/null &');		// Time Server Service
	//system('sudo systemctl start pistar-watchdog.service > /dev/null 2>/dev/null &');	// PiStar-Watchdog Service
	//system('sudo systemctl start pistar-remote.service > /dev/null 2>/dev/null &');		// PiStar-Remote Service
	//system('sudo systemctl start pistar-upnp.service > /dev/null 2>/dev/null &');		// PiStar-UPnP Service
	//system('sudo systemctl start ysf2dmr.service > /dev/null 2>/dev/null &');		// YSF2DMR
	//system('sudo systemctl start ysfgateway.service > /dev/null 2>/dev/null &');		// YSFGateway
	//system('sudo systemctl start ysfparrot.service > /dev/null 2>/dev/null &');		// YSFParrot
	//system('sudo systemctl start p25gateway.service > /dev/null 2>/dev/null &');		// P25Gateway
	//system('sudo systemctl start p25parrot.service > /dev/null 2>/dev/null &');		// P25Parrot
	//system('sudo systemctl start nxdngateway.service > /dev/null 2>/dev/null &');		// NXDNGateway
	//system('sudo systemctl start nxdnparrot.service > /dev/null 2>/dev/null &');		// NXDNParrot
	//system('sudo systemctl start dmrgateway.service > /dev/null 2>/dev/null &');		// DMRGateway

	// Set the system timezone
	//$rollTimeZone = 'sudo timedatectl set-timezone '.escapeshellcmd($_POST['systemTimezone']);
	//system($rollTimeZone);
	//$rollTimeZoneConfig = 'sudo sed -i "/date_default_timezone_set/c\\date_default_timezone_set(\''.escapeshellcmd($_POST['systemTimezone']).'\')\;" /var/www/dashboard/config/config.php';
	//system($rollTimeZoneConfig);

	// Start Cron (occasionally remounts root as RO - would be bad if it did this at the wrong time....)
	//system('sudo systemctl start cron.service > /dev/null 2>/dev/null &');			//Cron

	unset($_POST);
	echo '<script type="text/javascript">setTimeout(function() { window.location=window.location;},1500);</script>';

	// Make the root filesystem read-only
	system('sudo mount -o remount,ro /');

else:
	// Output the HTML Form here
	//if ((file_exists('/etc/dstar-radio.mmdvmhost') || file_exists('/etc/dstar-radio.dstarrepeater')) && !$configModem['Modem']['Hardware']) { echo "<script type\"text/javascript\">\n\talert(\"WARNING:\\nThe Modem selection section has been updated,\\nPlease re-select your modem from the list.\")\n</script>\n"; }
?>
<form id="factoryReset" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	<div><input type="hidden" name="factoryReset" value="1" /></div>
</form>

<form id="config" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
	<div><b><?php echo $lang['control_software'];?></b></div>
    <table>
    <tr>
    <th width="200"><a class="tooltip" href="#"><?php echo $lang['setting'];?><span><b>Setting</b></span></a></th>
    <th><a class="tooltip" href="#"><?php echo $lang['value'];?><span><b>Value</b>The current value from<br />the configuration files</span></a></th>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['controller_software'];?>:<span><b>Radio Control Software</b>Choose the software used<br />to control the DV Radio Module<br />PLease note that DV Mega hardware<br />will require a firmware upgrade.</span></a></td>
    <?php
	if ($configPistarSystem['software']['modemControlSoftware'] == "mmdvmhost") {
		echo "   <td align=\"left\" colspan=\"2\"><input type=\"radio\" name=\"controllerSoft\" value=\"DSTAR\" onclick=\"alert('After applying your Configuration Settings, you will need to powercycle your Pi.');\" />DStarRepeater <input type=\"radio\" name=\"controllerSoft\" value=\"MMDVM\" checked=\"checked\" />MMDVMHost (DV-Mega Minimum Firmware 3.07 Required)</td>\n";
		}
	elseif ($configPistarSystem['software']['modemControlSoftware'] == "dstarrepeater") {
		echo "   <td align=\"left\" colspan=\"2\"><input type=\"radio\" name=\"controllerSoft\" value=\"DSTAR\" checked=\"checked\" />DStarRepeater <input type=\"radio\" name=\"controllerSoft\" value=\"MMDVM\" onclick=\"alert('After applying your Configuration Settings, you will need to powercycle your Pi.');\" />MMDVMHost (DV-Mega Minimum Firmware 3.07 Required)</td>\n";
	}
	else { // Not set - default to MMDVMHost
		echo "   <td align=\"left\" colspan=\"2\"><input type=\"radio\" name=\"controllerSoft\" value=\"DSTAR\" onclick=\"alert('After applying your Configuration Settings, you will need to powercycle your Pi.');\" />DStarRepeater <input type=\"radio\" name=\"controllerSoft\" value=\"MMDVM\" checked=\"checked\" />MMDVMHost (DV-Mega Minimum Firmware 3.07 Required)</td>\n";
	}
    ?>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['controller_mode'];?>:<span><b>TRX Mode</b>Choose the mode type<br />Simplex node or<br />Duplex repeater.</span></a></td>
    <?php
	if ($config_mmdvmhost['Info']['RXFrequency'] === $config_mmdvmhost['Info']['TXFrequency']) {
		echo "   <td align=\"left\" colspan=\"2\"><input type=\"radio\" name=\"trxMode\" value=\"SIMPLEX\" checked=\"checked\" />Simplex Node <input type=\"radio\" name=\"trxMode\" value=\"DUPLEX\" />Duplex Repeater (or Half-Duplex on Hotspots)</td>\n";
		}
	else {
		echo "   <td align=\"left\" colspan=\"2\"><input type=\"radio\" name=\"trxMode\" value=\"SIMPLEX\" />Simplex Node <input type=\"radio\" name=\"trxMode\" value=\"DUPLEX\" checked=\checked\" />Duplex Repeater (or Half-Duplex on Hotspots)</td>\n";
		}
    ?>
    </tr>
    </table>
	<div><input type="button" value="<?php echo $lang['apply'];?>" onclick="submitform()" /><br /><br /></div>
<?php if ($configPistarSystem['software']['modemControlSoftware'] == "mmdvmhost") { ?>
    <input type="hidden" name="MMDVMModeDMR" value="OFF" />
    <input type="hidden" name="MMDVMModeDSTAR" value="OFF" />
    <input type="hidden" name="MMDVMModeFUSION" value="OFF" />
    <input type="hidden" name="MMDVMModeP25" value="OFF" />
    <input type="hidden" name="MMDVMModeNXDN" value="OFF" />
    <input type="hidden" name="MMDVMModeYSF2DMR" value="OFF" />
	<div><b><?php echo $lang['mmdvmhost_config'];?></b></div>
    <table>
    <tr>
    <th width="200"><a class="tooltip" href="#"><?php echo $lang['setting'];?><span><b>Setting</b></span></a></th>
    <th colspan="2"><a class="tooltip" href="#"><?php echo $lang['value'];?><span><b>Value</b>The current value from<br />the configuration files</span></a></th>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['dmr_mode'];?>:<span><b>DMR Mode</b>Turn on DMR Features</span></a></td>
    <?php
	if ( $config_mmdvmhost['DMR']['Enable'] == 1 ) {
		echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-dmr\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeDMR\" value=\"ON\" checked=\"checked\" /><label for=\"toggle-dmr\"></label></div></td>\n";
		}
	else {
		echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-dmr\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeDMR\" value=\"ON\" /><label for=\"toggle-dmr\"></label></div></td>\n";
	}
    ?>
    <td>RF Hangtime: <input type="text" name="dmrRfHangTime" size="7" maxlength="3" value="<?php if (isset($config_mmdvmhost['DMR']['ModeHang'])) { echo $config_mmdvmhost['DMR']['ModeHang']; } else { echo "20"; } ?>" />
    Net Hangtime: <input type="text" name="dmrNetHangTime" size="7" maxlength="3" value="<?php if (isset($config_mmdvmhost['DMR Network']['ModeHang'])) { echo $config_mmdvmhost['DMR Network']['ModeHang']; } else { echo "20"; } ?>" />
    </td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['d-star_mode'];?>:<span><b>D-Star Mode</b>Turn on D-Star Features</span></a></td>
    <?php
	if ( $config_mmdvmhost['D-Star']['Enable'] == 1 ) {
		echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-dstar\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeDSTAR\" value=\"ON\" checked=\"checked\" /><label for=\"toggle-dstar\"></label></div></td>\n";
		}
	else {
		echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-dstar\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeDSTAR\" value=\"ON\" /><label for=\"toggle-dstar\"></label></div></td>\n";
	}
    ?>
    <td>RF Hangtime: <input type="text" name="dstarRfHangTime" size="7" maxlength="3" value="<?php if (isset($config_mmdvmhost['D-Star']['ModeHang'])) { echo $config_mmdvmhost['D-Star']['ModeHang']; } else { echo "20"; } ?>" />
    Net Hangtime: <input type="text" name="dstarNetHangTime" size="7" maxlength="3" value="<?php if (isset($config_mmdvmhost['D-Star Network']['ModeHang'])) { echo $config_mmdvmhost['D-Star Network']['ModeHang']; } else { echo "20"; } ?>" />
    </td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['ysf_mode'];?>:<span><b>YSF Mode</b>Turn on YSF Features</span></a></td>
    <?php
	if ( $config_mmdvmhost['System Fusion']['Enable'] == 1 ) {
		echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-ysf\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeFUSION\" value=\"ON\" checked=\"checked\" /><label for=\"toggle-ysf\"></label></div></td>\n";
		}
	else {
		echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-ysf\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeFUSION\" value=\"ON\" /><label for=\"toggle-ysf\"></label></div></td>\n";
	}
    ?>
    <td>RF Hangtime: <input type="text" name="ysfRfHangTime" size="7" maxlength="3" value="<?php if (isset($config_mmdvmhost['System Fusion']['ModeHang'])) { echo $config_mmdvmhost['System Fusion']['ModeHang']; } else { echo "20"; } ?>" />
    Net Hangtime: <input type="text" name="ysfNetHangTime" size="7" maxlength="3" value="<?php if (isset($config_mmdvmhost['System Fusion Network']['ModeHang'])) { echo $config_mmdvmhost['System Fusion Network']['ModeHang']; } else { echo "20"; } ?>" />
    </td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['p25_mode'];?>:<span><b>P25 Mode</b>Turn on P25 Features</span></a></td>
    <?php
	if ( $config_mmdvmhost['P25']['Enable'] == 1 ) {
		echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-p25\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeP25\" value=\"ON\" checked=\"checked\" /><label for=\"toggle-p25\"></label></div></td>\n";
		}
	else {
		echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-p25\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeP25\" value=\"ON\" /><label for=\"toggle-p25\"></label></div></td>\n";
	}
    ?>
    <td>RF Hangtime: <input type="text" name="p25RfHangTime" size="7" maxlength="3" value="<?php if (isset($config_mmdvmhost['P25']['ModeHang'])) { echo $config_mmdvmhost['P25']['ModeHang']; } else { echo "20"; } ?>" />
    Net Hangtime: <input type="text" name="p25NetHangTime" size="7" maxlength="3" value="<?php if (isset($config_mmdvmhost['P25 Network']['ModeHang'])) { echo $config_mmdvmhost['P25 Network']['ModeHang']; } else { echo "20"; } ?>" />
    </td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['nxdn_mode'];?>:<span><b>NXDN Mode</b>Turn on NXDN Features</span></a></td>
    <?php
	if ( $config_mmdvmhost['NXDN']['Enable'] == 1 ) {
		echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-nxdn\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeNXDN\" value=\"ON\" checked=\"checked\" /><label for=\"toggle-nxdn\"></label></div></td>\n";
		}
	else {
		echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-nxdn\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeNXDN\" value=\"ON\" /><label for=\"toggle-nxdn\"></label></div></td>\n";
	}
    ?>
    <td>RF Hangtime: <input type="text" name="nxdnRfHangTime" size="7" maxlength="3" value="<?php if (isset($config_mmdvmhost['NXDN']['ModeHang'])) { echo $config_mmdvmhost['NXDN']['ModeHang']; } else { echo "20"; } ?>" />
    Net Hangtime: <input type="text" name="nxdnNetHangTime" size="7" maxlength="3" value="<?php if (isset($config_mmdvmhost['NXDN Network']['ModeHang'])) { echo $config_mmdvmhost['NXDN Network']['ModeHang']; } else { echo "20"; } ?>" />
    </td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#">YSF2DMR:<span><b>YSF2DMR Mode</b>Turn on YSF2DMR Features</span></a></td>
    <?php
	if ( $config_ysf2dmr['Enabled']['Enabled'] == 1 ) {
		echo "<td colspan=\"2\" align=\"left\"><div class=\"switch\"><input id=\"toggle-ysf2dmr\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeYSF2DMR\" value=\"ON\" checked=\"checked\" /><label for=\"toggle-ysf2dmr\"></label></div></td>\n";
		}
	else {
		echo "<td colspan=\"2\" align=\"left\"><div class=\"switch\"><input id=\"toggle-ysf2dmr\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"MMDVMModeYSF2DMR\" value=\"ON\" /><label for=\"toggle-ysf2dmr\"></label></div></td>\n";
	}
    ?>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['mmdvm_display'];?>:<span><b>Display Type</b>Choose your display<br />type if you have one.</span></a></td>
    <td align="left" colspan="2"><select name="mmdvmDisplayType">
	    <option <?php if (($config_mmdvmhost['General']['Display'] == "None") || ($config_mmdvmhost['General']['Display'] == "") ) {echo 'selected="selected" ';}; ?>value="None">None</option>
	    <option <?php if ($config_mmdvmhost['General']['Display'] == "OLED") {echo 'selected="selected" ';}; ?>value="OLED">OLED</option>
	    <option <?php if ($config_mmdvmhost['General']['Display'] == "Nextion") {echo 'selected="selected" ';}; ?>value="Nextion">Nextion</option>
	    <option <?php if ($config_mmdvmhost['General']['Display'] == "HD44780") {echo 'selected="selected" ';}; ?>value="HD44780">HD44780</option>
	    <option <?php if ($config_mmdvmhost['General']['Display'] == "TFT Serial") {echo 'selected="selected" ';}; ?>value="TFT Serial">TFT Serial</option>
	    <option <?php if ($config_mmdvmhost['General']['Display'] == "LCDproc") {echo 'selected="selected" ';}; ?>value="LCDproc">LCDproc</option>
	    </select>
	    Port: <select name="mmdvmDisplayPort">
	    <option <?php if (($config_mmdvmhost['General']['Display'] == "None") || ($config_mmdvmhost['General']['Display'] == "") ) {echo 'selected="selected" ';}; ?>value="None">None</option>
	    <option <?php if ($config_mmdvmhost['Nextion']['Port'] == "modem") {echo 'selected="selected" ';}; ?>value="modem">Modem</option>
	    <option <?php if ($config_mmdvmhost['Nextion']['Port'] == "/dev/ttyAMA0") {echo 'selected="selected" ';}; ?>value="/dev/ttyAMA0">/dev/ttyAMA0</option>
	    <option <?php if ($config_mmdvmhost['Nextion']['Port'] == "/dev/ttyUSB0") {echo 'selected="selected" ';}; ?>value="/dev/ttyUSB0">/dev/ttyUSB0</option>
	    <?php if (file_exists('/dev/ttyS2')) { ?>
	    	<option <?php if ($config_mmdvmhost['Nextion']['Port'] == "/dev/ttyS2") {echo 'selected="selected" ';}; ?>value="/dev/ttyS2">/dev/ttyS2</option>
    	    <?php } ?>
	    <?php if (file_exists('/dev/ttyNextionDriver')) { ?>
	    	<option <?php if ($config_mmdvmhost['Nextion']['Port'] == "/dev/ttyNextionDriver") {echo 'selected="selected" ';}; ?>value="/dev/ttyNextionDriver">/dev/ttyNextionDriver</option>
    	    <?php } ?>
	    </select>
	    Nextion Layout: <select name="mmdvmNextionDisplayType">
	    <option <?php if ($config_mmdvmhost['Nextion']['ScreenLayout'] == "0") {echo 'selected="selected" ';}; ?>value="G4KLX">G4KLX</option>
	    <option <?php if ($config_mmdvmhost['Nextion']['ScreenLayout'] == "2") {echo 'selected="selected" ';}; ?>value="ON7LDS">ON7LDS</option>
	    </select>
    </td></tr>
    <!--<tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['mode_hangtime'];?>:<span><b>Net Hang Time</b>Stay in the last mode for<br />this many seconds</span></a></td>
    <td align="left" colspan="2"><input type="text" name="hangTime" size="13" maxlength="3" value="<?php echo $config_mmdvmhost['General']['RFModeHang']; ?>" /> in seconds (90 secs works well for Multi-Mode)</td>
    </tr>-->
    </table>
	<div><input type="button" value="<?php echo $lang['apply'];?>" onclick="submitform()" /><br /><br /></div>
    <?php } ?>
	<div><b><?php echo $lang['general_config'];?></b></div>
    <table>
    <tr>
    <th width="200"><a class="tooltip" href="#"><?php echo $lang['setting'];?><span><b>Setting</b></span></a></th>
    <th colspan="2"><a class="tooltip" href="#"><?php echo $lang['value'];?><span><b>Value</b>The current value from the<br />configuration files</span></a></th>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#">Hostname:<span><b>System Hostname</b>This is the system<br />hostname, used for access<br />to the dashboard etc.</span></a></td>
    <td align="left" colspan="2"><input type="text" name="confHostame" size="13" maxlength="15" value="<?php echo exec('cat /etc/hostname'); ?>" />Do not add suffixes such as .local</td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['node_call'];?>:<span><b>Gateway Callsign</b>This is your licenced callsign for use<br />on this gateway, do not append<br />the "G"</span></a></td>
    <td align="left" colspan="2"><input type="text" name="confCallsign" size="13" maxlength="7" value="<?php echo $config_ircddbgateway['gatewayCallsign'] ?>" /></td>
    </tr>
    <?php if (($configPistarSystem['software']['modemControlSoftware'] == "mmdvmhost") && (($config_mmdvmhost['DMR']['Enable'] == 1) || ($config_mmdvmhost['P25']['Enable'] == 1 ))) { ?>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['dmr_id'];?>:<span><b>CCS7/DMR ID</b>Enter your CCS7 / DMR ID here</span></a></td>
    <td align="left" colspan="2"><input type="text" name="dmrId" size="13" maxlength="9" value="<?php if (isset($config_mmdvmhost['General']['Id'])) { echo $config_mmdvmhost['General']['Id']; } else { echo $config_mmdvmhost['DMR']['Id']; } ?>" /></td>
    </tr><?php } ?>
    <?php if (($configPistarSystem['software']['modemControlSoftware'] == "mmdvmhost") && ($config_mmdvmhost['NXDN']['Enable'] == 1)) { ?>
    <tr>
      <td align="left"><a class="tooltip2" href="#">NXDN ID:<span><b>NXDN ID</b>Enter your NXDN ID here</span></a></td>
      <td align="left" colspan="2"><input type="text" name="nxdnId" size="13" maxlength="5" value="<?php if (isset($config_mmdvmhost['NXDN']['Id'])) { echo $config_mmdvmhost['NXDN']['Id']; } ?>" /></td>
    </tr><?php } ?>
<?php if ($config_mmdvmhost['Info']['TXFrequency'] === $config_mmdvmhost['Info']['RXFrequency']) {
	echo "    <tr>\n";
	echo "    <td align=\"left\"><a class=\"tooltip2\" href=\"#\">".$lang['radio_freq'].":<span><b>Radio Frequency</b>This is the Frequency your<br />Pi-Star is on</span></a></td>\n";
	echo "    <td align=\"left\" colspan=\"2\"><input type=\"text\" name=\"confFREQ\" size=\"13\" maxlength=\"12\" value=\"".number_format($config_mmdvmhost['Info']['RXFrequency'], 0, '.', '.')."\" />MHz</td>\n";
	echo "    </tr>\n";
	}
	else {
	echo "    <tr>\n";
	echo "    <td align=\"left\"><a class=\"tooltip2\" href=\"#\">".$lang['radio_freq']." RX:<span><b>Radio Frequency</b>This is the Frequency your<br />repeater will listen on</span></a></td>\n";
	echo "    <td align=\"left\" colspan=\"2\"><input type=\"text\" name=\"confFREQrx\" size=\"13\" maxlength=\"12\" value=\"".number_format($config_mmdvmhost['Info']['RXFrequency'], 0, '.', '.')."\" />MHz</td>\n";
	echo "    </tr>\n";
	echo "    <tr>\n";
	echo "    <td align=\"left\"><a class=\"tooltip2\" href=\"#\">".$lang['radio_freq']." TX:<span><b>Radio Frequency</b>This is the Frequency your<br />repeater will transmit on</span></a></td>\n";
	echo "    <td align=\"left\" colspan=\"2\"><input type=\"text\" name=\"confFREQtx\" size=\"13\" maxlength=\"12\" value=\"".number_format($config_mmdvmhost['Info']['TXFrequency'], 0, '.', '.')."\" />MHz</td>\n";
	echo "    </tr>\n";
	}
?>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['lattitude'];?>:<span><b>Gateway Latitude</b>This is the latitude where the<br />gateway is located (positive<br />number for North, negative<br />number for South)</span></a></td>
    <td align="left" colspan="2"><input type="text" name="confLatitude" size="13" maxlength="9" value="<?php echo $config_ircddbgateway['latitude'] ?>" />degrees (positive value for North, negative for South)</td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['longitude'];?>:<span><b>Gateway Longitude</b>This is the longitude where the<br />gateway is located (positive<br />number for East, negative<br />number for West)</span></a></td>
    <td align="left" colspan="2"><input type="text" name="confLongitude" size="13" maxlength="9" value="<?php echo $config_ircddbgateway['longitude'] ?>" />degrees (positive value for East, negative for West)</td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['town'];?>:<span><b>Gateway Town</b>The town where the gateway<br />is located</span></a></td>
    <td align="left" colspan="2"><input type="text" name="confDesc1" size="30" maxlength="30" value="<?php echo $config_ircddbgateway['description1'] ?>" /></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['country'];?>:<span><b>Gateway Country</b>The country where the gateway<br />is located</span></a></td>
    <td align="left" colspan="2"><input type="text" name="confDesc2" size="30" maxlength="30" value="<?php echo $config_ircddbgateway['description2'] ?>" /></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['url'];?>:<span><b>Gateway URL</b>The URL used to access<br />this dashboard</span></a></td>
    <td align="left"><input type="text" name="confURL" size="30" maxlength="30" value="<?php echo $config_ircddbgateway['url'] ?>" /></td>
    <td width="300">
    <input type="radio" name="urlAuto" value="auto"<?php if (strpos($config_ircddbgateway['url'], 'www.qrz.com/db/'.$config_mmdvmhost['General']['Callsign']) !== FALSE) {echo ' checked="checked"';} ?> />Auto
    <input type="radio" name="urlAuto" value="man"<?php if (strpos($config_ircddbgateway['url'], 'www.qrz.com/db/'.$config_mmdvmhost['General']['Callsign']) == FALSE) {echo ' checked="checked"';} ?> />Manual</td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['radio_type'];?>:<span><b>Radio/Modem</b>What kind of radio or modem<br />hardware do you have ?</span></a></td>
    <td align="left" colspan="2"><select name="confHardware">
		<option<?php if (!$configPistarSystem['modem']['modemHardware']) { echo ' selected="selected"';}?> value="">--</option>
		<option<?php if ($configPistarSystem['modem']['modemHardware'] === 'dvmega-pi-single')		{ echo ' selected="selected"';}?> value="dvmega-pi-single"		>DV-Mega Raspberry Pi Hat (GPIO) - Single Band (70cm)</option>
		<option<?php if ($configPistarSystem['modem']['modemHardware'] === 'dvmega-pi-dual')		{ echo ' selected="selected"';}?> value="dvmega-pi-dual"		>DV-Mega Raspberry Pi Hat (GPIO) - Dual Band</option>
		<option<?php if ($configPistarSystem['modem']['modemHardware'] === 'dvmega-ardruino-dual')	{ echo ' selected="selected"';}?> value="dvmega-ardruino-dual"		>DV-Mega on Arduino (USB - /dev/ttyUSB0) - Dual Band</option>
	        <option<?php if ($configPistarSystem['modem']['modemHardware'] === 'dvmega-ardruino-dual-alt')	{ echo ' selected="selected"';}?> value="dvmega-ardruino-dual-alt"	>DV-Mega on Arduino (USB - /dev/ttyACM0) - Dual Band</option>
		<option<?php if ($configPistarSystem['modem']['modemHardware'] === 'dvmega-ardruino-gmsk')	{ echo ' selected="selected"';}?> value="dvmega-ardruino-gmsk"		>DV-Mega on Arduino (USB - /dev/ttyUSB0) - GMSK Modem</option>
		<option<?php if ($configPistarSystem['modem']['modemHardware'] === 'dvmega-ardruino-gmsk-alt')	{ echo ' selected="selected"';}?> value="dvmega-ardruino-gmsk-alt"	>DV-Mega on Arduino (USB - /dev/ttyACM0) - GMSK Modem</option>
		<option<?php if ($configPistarSystem['modem']['modemHardware'] === 'dvmega-bluestack-single')	{ echo ' selected="selected"';}?> value="dvmega-bluestack-single"	>DV-Mega on Bluestack (USB) - Single Band (70cm)</option>
		<option<?php if ($configPistarSystem['modem']['modemHardware'] === 'dvmega-bluestack-dual')	{ echo ' selected="selected"';}?> value="dvmega-bluestack-dual"		>DV-Mega on Bluestack (USB) - Dual Band</option>
		<option<?php if ($configPistarSystem['modem']['modemHardware'] === 'gmsk-modem')		{ echo ' selected="selected"';}?> value="gmsk-modem"			>GMSK Modem (USB DStarRepeater Only)</option>
	        <option<?php if ($configPistarSystem['modem']['modemHardware'] === 'dvr-ptr-v1')		{ echo ' selected="selected"';}?> value="dvr-ptr-v1"			>DV-RPTR V1 (USB)</option>
		<option<?php if ($configPistarSystem['modem']['modemHardware'] === 'dvr-ptr-v2')		{ echo ' selected="selected"';}?> value="dvr-ptr-v2"			>DV-RPTR V2 (USB)</option>
		<option<?php if ($configPistarSystem['modem']['modemHardware'] === 'dvr-ptr-v3')		{ echo ' selected="selected"';}?> value="dvr-ptr-v3"			>DV-RPTR V3 (USB)</option>
		<option<?php if ($configPistarSystem['modem']['modemHardware'] === 'dvap')			{ echo ' selected="selected"';}?> value="dvap"				>DVAP (USB)</option>
		<option<?php if ($configPistarSystem['modem']['modemHardware'] === 'mmdvm-generic')		{ echo ' selected="selected"';}?> value="mmdvm-generic"			>MMDVM / MMDVM_HS / Teensy / ZUM (USB)</option>
		<option<?php if ($configPistarSystem['modem']['modemHardware'] === 'stm32dvm-gpio')		{ echo ' selected="selected"';}?> value="stm32dvm-gpio"			>STM32-DVM / MMDVM_HS - Raspberry Pi Hat (GPIO)</option>
		<option<?php if ($configPistarSystem['modem']['modemHardware'] === 'stm32dvm-usb')		{ echo ' selected="selected"';}?> value="stm32dvm-usb"			>STM32-DVM (USB)</option>
	        <option<?php if ($configPistarSystem['modem']['modemHardware'] === 'zumspot-libre')		{ echo ' selected="selected"';}?> value="zumspot-libre"			>ZumSpot - Libre (USB)</option>
		<option<?php if ($configPistarSystem['modem']['modemHardware'] === 'zumspot-usb')		{ echo ' selected="selected"';}?> value="zumspot-usb"			>ZumSpot - USB Stick</option>
		<option<?php if ($configPistarSystem['modem']['modemHardware'] === 'zumspot-gpio')		{ echo ' selected="selected"';}?> value="zumspot-gpio"			>ZumSpot - Raspberry Pi Hat (GPIO)</option>
	        <option<?php if ($configPistarSystem['modem']['modemHardware'] === 'zumradio-gpio')		{ echo ' selected="selected"';}?> value="zumradio-gpio"			>ZUM Radio-MMDVM for Pi (GPIO)</option>
	        <option<?php if ($configPistarSystem['modem']['modemHardware'] === 'micronode-nano-spot')	{ echo ' selected="selected"';}?> value="micronode-nano-spot"		>MicroNode Nano-Spot (Built In)</option>
	        <option<?php if ($configPistarSystem['modem']['modemHardware'] === 'micronode-teensy')		{ echo ' selected="selected"';}?> value="micronode-teensy"		>MicroNode Teensy (USB)</option>
	        <option<?php if ($configPistarSystem['modem']['modemHardware'] === 'mmdvm-f4m-gpio')		{ echo ' selected="selected"';}?> value="mmdvm-f4m-gpio"		>MMDVM F4M-GPIO (GPIO)</option>
	        <option<?php if ($configPistarSystem['modem']['modemHardware'] === 'mmdvm-hshat-gpio')		{ echo ' selected="selected"';}?> value="mmdvm-hshat-gpio"		>MMDVM_HS_Hat (DB9MAT &amp; DF2ET) for Pi (GPIO)</option>
	        <option<?php if ($configPistarSystem['modem']['modemHardware'] === 'mmdvm-hshat-dual-gpio')	{ echo ' selected="selected"';}?> value="mmdvm-hshat-dual-gpio"		>MMDVM_HS_Hat Dual (DB9MAT &amp; DF2ET) for Pi (GPIO)</option>
	        <option<?php if ($configPistarSystem['modem']['modemHardware'] === 'mmdvm-mdo-gpio')		{ echo ' selected="selected"';}?> value="mmdvm-mdo-gpio"		>MMDVM_HS_MDO Hat (BG3MDO) for Pi (GPIO)</option>
	    	<option<?php if ($configPistarSystem['modem']['modemHardware'] === 'mmdvm-vye-gpio')		{ echo ' selected="selected"';}?> value="mmdvm-vye-gpio"		>MMDVM_HS_NPi Hat (VR2VYE) for Nano Pi (GPIO)</option>
    </select></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['node_type'];?>:<span><b>Node Lock</b>Set the public / private<br />node type. Public should<br />only be used with the correct<br />licence.</span></a></td>
    <td align="left" colspan="2">
    <input type="radio" name="nodeMode" value="prv"<?php if ($config_mmdvmhost['DMR']['SelfOnly'] == 1) {echo ' checked="checked"';} ?> />Private
    <input type="radio" name="nodeMode" value="pub"<?php if ($config_mmdvmhost['DMR']['SelfOnly'] == 0) {echo ' checked="checked"';} ?> />Public</td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['timezone'];?>:<span><b>System TimeZone</b>Set the system timezone</span></a></td>
    <td style="text-align: left;" colspan="2"><select name="systemTimezone">
<?php
  exec('timedatectl list-timezones', $tzList);
  exec('cat /etc/timezone', $tzCurrent);
    foreach ($tzList as $timeZone) {
      if ($timeZone == $tzCurrent[0]) { echo "      <option selected=\"selected\" value=\"".$timeZone."\">".$timeZone."</option>\n"; }
      else { echo "      <option value=\"".$timeZone."\">".$timeZone."</option>\n"; }
    }
?>
    </select></td>
    </tr>
<?php
    $lang_dir = './lang';
    if (is_dir($lang_dir)) {
	echo '    <tr>'."\n";
	echo '    <td align="left"><a class="tooltip2" href="#">'.$lang['dash_lang'].':<span><b>Dashboard Language</b>Set the language for<br />the dashboard.</span></a></td>'."\n";
	echo '    <td align="left" colspan="2"><select name="dashboardLanguage">'."\n";

	if ($dh = opendir($lang_dir)) {
	while ($files[] = readdir($dh))
		sort($files); // Add sorting for the Language(s)
		foreach ($files as $file){
			if (($file != 'index.php') && ($file != '.') && ($file != '..') && ($file != '')) {
				$file = substr($file, 0, -4);
				if ($file == $pistarLanguage) { echo "      <option selected=\"selected\" value=\"".$file."\">".$file."</option>\n"; }
				else { echo "      <option value=\"".$file."\">".$file."</option>\n"; }
			}
		}
		closedir($dh);
	}
	echo '    </select></td></tr>'."\n";
    }
?>
    </table>
	<div><input type="button" value="<?php echo $lang['apply'];?>" onclick="submitform()" /><br /><br /></div>
    <?php if (($configPistarSystem['software']['modemControlSoftware'] == "mmdvmhost") && $config_mmdvmhost['DMR']['Enable'] == 1) {
    $dmrMasterFile = fopen("/usr/local/etc/MMDVMHost/DMR_Hosts.txt", "r"); ?>
	<div><b><?php echo $lang['dmr_config'];?></b></div>
    <input type="hidden" name="dmrEmbeddedLCOnly" value="OFF" />
    <input type="hidden" name="dmrDumpTAData" value="OFF" />
    <input type="hidden" name="dmrGatewayXlxEn" value="OFF" />
    <input type="hidden" name="dmrDMRnetJitterBufer" value="OFF" />
    <table>
    <tr>
    <th width="200"><a class="tooltip" href="#"><?php echo $lang['setting'];?><span><b>Setting</b></span></a></th>
    <th><a class="tooltip" href="#"><?php echo $lang['value'];?><span><b>Value</b>The current value from the<br />configuration files</span></a></th>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['dmr_master'];?>:<span><b>DMR Master (MMDVMHost)</b>Set your prefered DMR<br /> master here</span></a></td>
    <td style="text-align: left;"><select name="dmrMasterHost">
<?php
        $testMMDVMdmrMaster = $config_mmdvmhost['DMR Network']['Address'];
        while (!feof($dmrMasterFile)) {
                $dmrMasterLine = fgets($dmrMasterFile);
                $dmrMasterHost = preg_split('/\s+/', $dmrMasterLine);
                if ((strpos($dmrMasterHost[0], '#') === FALSE ) && (substr($dmrMasterHost[0], 0, 3) != "XLX") && ($dmrMasterHost[0] != '')) {
                        if ($testMMDVMdmrMaster == $dmrMasterHost[2]) { echo "      <option value=\"$dmrMasterHost[2],$dmrMasterHost[3],$dmrMasterHost[4],$dmrMasterHost[0]\" selected=\"selected\">$dmrMasterHost[0]</option>\n"; $dmrMasterNow = $dmrMasterHost[0]; }
                        else { echo "      <option value=\"$dmrMasterHost[2],$dmrMasterHost[3],$dmrMasterHost[4],$dmrMasterHost[0]\">$dmrMasterHost[0]</option>\n"; }
                }
        }
        fclose($dmrMasterFile);
        ?>
    </select></td>
    </tr>
<?php if ($dmrMasterNow == "DMRGateway") { ?>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['bm_master'];?>:<span><b>BrandMeister Master</b>Set your prefered DMR<br /> master here</span></a></td>
    <td style="text-align: left;"><select name="dmrMasterHost1">
<?php
	$dmrMasterFile1 = fopen("/usr/local/etc/MMDVMHost/DMR_Hosts.txt", "r");
	$testMMDVMdmrMaster1 = $config_dmrgateway['DMR Network 1']['Address'];
	while (!feof($dmrMasterFile1)) {
		$dmrMasterLine1 = fgets($dmrMasterFile1);
                $dmrMasterHost1 = preg_split('/\s+/', $dmrMasterLine1);
                if ((strpos($dmrMasterHost1[0], '#') === FALSE ) && (substr($dmrMasterHost1[0], 0, 2) == "BM") && ($dmrMasterHost1[0] != '')) {
                        if ($testMMDVMdmrMaster1 == $dmrMasterHost1[2]) { echo "      <option value=\"$dmrMasterHost1[2],$dmrMasterHost1[3],$dmrMasterHost1[4],$dmrMasterHost1[0]\" selected=\"selected\">$dmrMasterHost1[0]</option>\n"; }
                        else { echo "      <option value=\"$dmrMasterHost1[2],$dmrMasterHost1[3],$dmrMasterHost1[4],$dmrMasterHost1[0]\">$dmrMasterHost1[0]</option>\n"; }
                }
	}
	fclose($dmrMasterFile1);
?>
    </select></td></tr>
    <!-- <tr>
    <td align="left"><a class="tooltip2" href="#">BrandMeister Password:<span><b>BrandMeister Password</b>Override the Password<br />for BrandMeister</span></a></td>
    <td align="left"><input type="text" name="bmPasswordOverride" size="30" maxlength="30" value="<?php echo $config_dmrgateway['DMR Network 1']['Password']; ?>"></input></td>
    </tr> -->
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['bm_network'];?>:<span><b>BrandMeister Dashboards</b>Direct links to your<br />BrandMeister Dashboards</span></a></td>
    <td>
      <a href="https://brandmeister.network/?page=hotspot&amp;id=<?php echo $config_mmdvmhost['General']['Id']; ?>" target="_new" style="color: #000;">Repeater Information</a> |
      <a href="https://brandmeister.network/?page=hotspot-edit&amp;id=<?php echo $config_mmdvmhost['General']['Id']; ?>" target="_new" style="color: #000;">Edit Repeater (BrandMeister Selfcare)</a>
    </td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['dmr_plus_master'];?>:<span><b>DMR+ Master</b>Set your prefered DMR<br /> master here</span></a></td>
    <td style="text-align: left;"><select name="dmrMasterHost2">
<?php
	$dmrMasterFile2 = fopen("/usr/local/etc/MMDVMHost/DMR_Hosts.txt", "r");
	$testMMDVMdmrMaster2= $config_dmrgateway['DMR Network 2']['Address'];
	while (!feof($dmrMasterFile2)) {
		$dmrMasterLine2 = fgets($dmrMasterFile2);
                $dmrMasterHost2 = preg_split('/\s+/', $dmrMasterLine2);
                if ((strpos($dmrMasterHost2[0], '#') === FALSE ) && (substr($dmrMasterHost2[0], 0, 4) == "DMR+") && ($dmrMasterHost2[0] != '')) {
                        if ($testMMDVMdmrMaster2 == $dmrMasterHost2[2]) { echo "      <option value=\"$dmrMasterHost2[2],$dmrMasterHost2[3],$dmrMasterHost2[4],$dmrMasterHost2[0]\" selected=\"selected\">$dmrMasterHost2[0]</option>\n"; }
                        else { echo "      <option value=\"$dmrMasterHost2[2],$dmrMasterHost2[3],$dmrMasterHost2[4],$dmrMasterHost2[0]\">$dmrMasterHost2[0]</option>\n"; }
                }
	}
	fclose($dmrMasterFile2);
?>
    </select></td></tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['dmr_plus_network'];?>:<span><b>DMR+ Network</b>Set your options=<br />for DMR+ here</span></a></td>
    <td align="left">
    Options=<input type="text" name="dmrNetworkOptions" size="68" maxlength="100" value="<?php if (isset($config_dmrgateway['DMR Network 2']['Options'])) { echo $config_dmrgateway['DMR Network 2']['Options']; } ?>" />
    </td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['xlx_master'];?>:<span><b>XLX Master</b>Set your prefered XLX<br /> master here</span></a></td>
    <td style="text-align: left;"><select name="dmrMasterHost3">
<?php
	$dmrMasterFile3 = fopen("/usr/local/etc/MMDVMHost/DMR_Hosts.txt", "r");
	if (isset($config_dmrgateway['XLX Network 1']['Address'])) { $testMMDVMdmrMaster3= $config_dmrgateway['XLX Network 1']['Address']; }
	if (isset($config_dmrgateway['XLX Network']['Startup'])) { $testMMDVMdmrMaster3= $config_dmrgateway['XLX Network']['Startup']; }
	while (!feof($dmrMasterFile3)) {
		$dmrMasterLine3 = fgets($dmrMasterFile3);
                $dmrMasterHost3 = preg_split('/\s+/', $dmrMasterLine3);
                if ((strpos($dmrMasterHost3[0], '#') === FALSE ) && (substr($dmrMasterHost3[0], 0, 3) == "XLX") && ($dmrMasterHost3[0] != '')) {
                        if ($testMMDVMdmrMaster3 == $dmrMasterHost3[2]) { echo "      <option value=\"$dmrMasterHost3[2],$dmrMasterHost3[3],$dmrMasterHost3[4],$dmrMasterHost3[0]\" selected=\"selected\">$dmrMasterHost3[0]</option>\n"; }
			if ('XLX_'.$testMMDVMdmrMaster3 == $dmrMasterHost3[0]) { echo "      <option value=\"$dmrMasterHost3[2],$dmrMasterHost3[3],$dmrMasterHost3[4],$dmrMasterHost3[0]\" selected=\"selected\">$dmrMasterHost3[0]</option>\n"; }
                        else { echo "      <option value=\"$dmrMasterHost3[2],$dmrMasterHost3[3],$dmrMasterHost3[4],$dmrMasterHost3[0]\">$dmrMasterHost3[0]</option>\n"; }
                }
	}
	fclose($dmrMasterFile3);
?>
    </select></td></tr>
    <?php if (isset($config_dmrgateway['XLX Network 1']['Startup'])) { ?>
    <tr>
    <td align="left"><a class="tooltip2" href="#">XLX Startup TG:<span><b>XLX Startup TG</b></span></a></td>
    <td align="left"><select name="dmrMasterHost3Startup">
<?php
	if (isset($config_dmrgateway['XLX Network 1']['Startup'])) {
		echo '      <option value="None">None</option>'."\n";
	}
	else {
		echo '      <option value="None" selected="selected">None</option>'."\n";
	}
	for ($xlxSu = 1; $xlxSu <= 26; $xlxSu++) {
		$xlxSuVal = '40'.sprintf('%02d', $xlxSu);
		if ((isset($config_dmrgateway['XLX Network 1']['Startup'])) && ($config_dmrgateway['XLX Network 1']['Startup'] == $xlxSuVal)) {
			echo '      <option value="'.$xlxSuVal.'" selected="selected">'.$xlxSuVal.'</option>'."\n";
		}
		else {
			echo '      <option value="'.$xlxSuVal.'">'.$xlxSuVal.'</option>'."\n";
		}
	}
?>
    </select></td></tr>
    <?php } ?>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['xlx_enable'];?>:<span><b>XLX Master Enable</b></span></a></td>
    <td align="left">
    <?php
    if ((isset($config_dmrgateway['XLX Network 1']['Enabled'])) && ($config_dmrgateway['XLX Network 1']['Enabled'] == 1)) { echo "<div class=\"switch\"><input id=\"toggle-dmrGatewayXlxEn\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrGatewayXlxEn\" value=\"ON\" checked=\"checked\" /><label for=\"toggle-dmrGatewayXlxEn\"></label></div>\n"; }
    else if ((isset($config_dmrgateway['XLX Network']['Enabled'])) && ($config_dmrgateway['XLX Network']['Enabled'] == 1)) { echo "<div class=\"switch\"><input id=\"toggle-dmrGatewayXlxEn\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrGatewayXlxEn\" value=\"ON\" checked=\"checked\" /><label for=\"toggle-dmrGatewayXlxEn\"></label></div>\n"; }
    else { echo "<div class=\"switch\"><input id=\"toggle-dmrGatewayXlxEn\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrGatewayXlxEn\" value=\"ON\" /><label for=\"toggle-dmrGatewayXlxEn\"></label></div>\n"; } ?>
    </td></tr>
<?php }
    if (substr($dmrMasterNow, 0, 2) == "BM") { echo '    <!-- <tr>
    <td align="left"><a class="tooltip2" href="#">BrandMeister Password:<span><b>BrandMeister Password</b>Override the Password<br />for BrandMeister</span></a></td>
    <td align="left"><input type="text" name="bmPasswordOverride" size="30" maxlength="30" value="'.$config_mmdvmhost['DMR Network']['Password'].'"></input></td>
    </tr> -->
    <tr>
    <td align="left"><a class="tooltip2" href="#">'.$lang['bm_network'].':<span><b>BrandMeister Dashboards</b>Direct links to your<br />BrandMeister Dashboards</span></a></td>
    <td>
      <a href="https://brandmeister.network/?page=hotspot&amp;id='.$config_mmdvmhost['General']['Id'].'" target="_new" style="color: #000;">Repeater Information</a> |
      <a href="https://brandmeister.network/?page=hotspot-edit&amp;id='.$config_mmdvmhost['General']['Id'].'" target="_new" style="color: #000;">Edit Repeater (BrandMeister Selfcare)</a>
    </td>
    </tr>'."\n";}
    if (substr($dmrMasterNow, 0, 4) == "DMR+") {
      echo '    <tr>
    <td align="left"><a class="tooltip2" href="#">'.$lang['dmr_plus_network'].':<span><b>DMR+ Network</b>Set your options=<br />for DMR+ here</span></a></td>
    <td align="left">
    Options=<input type="text" name="dmrNetworkOptions" size="68" maxlength="100" value="';
	if (isset($config_mmdvmhost['DMR Network']['Options'])) { echo $config_mmdvmhost['DMR Network']['Options']; }
        echo '" />
    </td>
    </tr>'."\n";}
?>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['dmr_cc'];?>:<span><b>DMR Color Code</b>Set your DMR Color Code here</span></a></td>
    <td style="text-align: left;"><select name="dmrColorCode">
	<?php for ($dmrColorCodeInput = 0; $dmrColorCodeInput <= 15; $dmrColorCodeInput++) {
		if ($config_mmdvmhost['DMR']['ColorCode'] == $dmrColorCodeInput) { echo "<option selected=\"selected\" value=\"$dmrColorCodeInput\">$dmrColorCodeInput</option>\n"; }
		else {echo "      <option value=\"$dmrColorCodeInput\">$dmrColorCodeInput</option>\n"; }
	} ?>
    </select></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['dmr_embeddedlconly'];?>:<span><b>DMR EmbeddedLCOnly</b>Set EmbeddedLCOnly to ON<br />to help reduce problems<br />with some DMR Radios</span></a></td>
    <td align="left">
    <?php if ($config_mmdvmhost['DMR']['EmbeddedLCOnly'] == 1) { echo "<div class=\"switch\"><input id=\"toggle-dmrEmbeddedLCOnly\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrEmbeddedLCOnly\" value=\"ON\" checked=\"checked\" /><label for=\"toggle-dmrEmbeddedLCOnly\"></label></div>\n"; }
    else { echo "<div class=\"switch\"><input id=\"toggle-dmrEmbeddedLCOnly\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrEmbeddedLCOnly\" value=\"ON\" /><label for=\"toggle-dmrEmbeddedLCOnly\"></label></div>\n"; } ?>
    </td></tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['dmr_dumptadata'];?>:<span><b>DMR DumpTAData</b>Turn on for extended<br />message support, including<br />GPS.</span></a></td>
    <td align="left">
    <?php if ($config_mmdvmhost['DMR']['DumpTAData'] == 1) { echo "<div class=\"switch\"><input id=\"toggle-dmrDumpTAData\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrDumpTAData\" value=\"ON\" checked=\"checked\" /><label for=\"toggle-dmrDumpTAData\"></label></div>\n"; }
    else { echo "<div class=\"switch\"><input id=\"toggle-dmrDumpTAData\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrDumpTAData\" value=\"ON\" /><label for=\"toggle-dmrDumpTAData\"></label></div>\n"; } ?>
    </td></tr>
    <!-- <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo "DMR JitterBuffer";?>:<span><b>DMR JitterBuffer</b>Turn on for improved<br />network resiliancy, in high<br />Latency networks.</span></a></td>
    <td align="left">
    <?php // if ((isset($config_mmdvmhost['DMR Network']['JitterEnabled'])) && ($config_mmdvmhost['DMR Network']['JitterEnabled'] == 0)) { echo "<div class=\"switch\"><input id=\"toggle-dmrJitterBufer\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrDMRnetJitterBufer\" value=\"ON\"/><label for=\"toggle-dmrJitterBufer\"></label></div>\n"; }
    // else { echo "<div class=\"switch\"><input id=\"toggle-dmrJitterBufer\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"dmrDMRnetJitterBufer\" value=\"ON\" checked=\"checked\" /><label for=\"toggle-dmrJitterBufer\"></label></div>\n"; }
    ?>
    </td></tr>. -->
    </table>
	<div><input type="button" value="<?php echo $lang['apply'];?>" onclick="submitform()" /><br /><br /></div>
<?php } ?>

<?php if (($configPistarSystem['software']['modemControlSoftware'] == "mmdvmhost") || $config_mmdvmhost['D-Star']['Enable'] == 1) { ?>
	<div><b><?php echo $lang['dstar_config'];?></b></div>
	<input type="hidden" name="confTimeAnnounce" value="OFF" />
	<input type="hidden" name="confHostFilesNoDExtra" value="OFF" />
    <table>
    <tr>
    <th width="200"><a class="tooltip" href="#"><?php echo $lang['setting'];?><span><b>Setting</b></span></a></th>
    <th colspan="2"><a class="tooltip" href="#"><?php echo $lang['value'];?><span><b>Value</b>The current value from the<br />configuration files</span></a></th>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['dstar_rpt1'];?>:<span><b>RPT1 Callsign</b>This is the RPT1 field for your radio</span></a></td>
    <td align="left" colspan="2"><?php echo str_replace(' ', '&nbsp;', substr($config_dstarrepeater['callsign'], 0, 7)) ?>
	<select name="confDStarModuleSuffix">
	<?php echo "  <option value=\"".substr($config_dstarrepeater['callsign'], 7)."\" selected=\"selected\">".substr($config_dstarrepeater['callsign'], 7)."</option>\n"; ?>
        <option>A</option>
        <option>B</option>
        <option>C</option>
        <option>D</option>
        <option>E</option>
        <option>F</option>
        <option>G</option>
        <option>H</option>
        <option>I</option>
        <option>J</option>
        <option>K</option>
        <option>L</option>
        <option>M</option>
        <option>N</option>
        <option>O</option>
        <option>P</option>
        <option>Q</option>
        <option>R</option>
        <option>S</option>
        <option>T</option>
        <option>U</option>
        <option>V</option>
        <option>W</option>
        <option>X</option>
        <option>Y</option>
        <option>Z</option>
    </select></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['dstar_rpt2'];?>:<span><b>RPT2 Callsign</b>This is the RPT2 field for your radio</span></a></td>
    <td align="left" colspan="2"><?php echo str_replace(' ', '&nbsp;', $config_dstarrepeater['gateway']) ?></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['dstar_irc_password'];?>:<span><b>Gateway Password</b>Used for any kind of remote<br />access to this system</span></a></td>
    <td align="left" colspan="2"><input type="password" name="confPassword" size="30" maxlength="30" value="<?php echo $config_ircddbgateway['remotePassword'] ?>" /></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['dstar_default_ref'];?>:<span><b>Default Refelctor</b>Used for setting the<br />default reflector.</span></a></td>
    <td align="left" colspan="1"><select name="confDefRef"
	onchange="if (this.options[this.selectedIndex].value == 'customOption') {
	  toggleField(this,this.nextSibling);
	  this.selectedIndex='0';
	  } ">
<?php
$dcsFile = fopen("/usr/local/etc/ircDDBGateway/DCS_Hosts.txt", "r");
$dplusFile = fopen("/usr/local/etc/ircDDBGateway/DPlus_Hosts.txt", "r");
$dextraFile = fopen("/usr/local/etc/ircDDBGateway/DExtra_Hosts.txt", "r");

echo "    <option value=\"".substr($config_ircddbgateway['reflector1'], 0, 6)."\" selected=\"selected\">".substr($config_ircddbgateway['reflector1'], 0, 6)."</option>\n";
echo "    <option value=\"customOption\">Text Entry</option>\n";

while (!feof($dcsFile)) {
	$dcsLine = fgets($dcsFile);
	if (strpos($dcsLine, 'DCS') !== FALSE && strpos($dcsLine, '#') === FALSE)
		echo "	<option value=\"".substr($dcsLine, 0, 6)."\">".substr($dcsLine, 0, 6)."</option>\n";
}
fclose($dcsFile);
while (!feof($dplusFile)) {
	$dplusLine = fgets($dplusFile);
	if (strpos($dplusLine, 'REF') !== FALSE && strpos($dplusLine, '#') === FALSE) {
		echo "	<option value=\"".substr($dplusLine, 0, 6)."\">".substr($dplusLine, 0, 6)."</option>\n";
	}
	if (strpos($dplusLine, 'XRF') !== FALSE && strpos($dplusLine, '#') === FALSE) {
		echo "	<option value=\"".substr($dplusLine, 0, 6)."\">".substr($dplusLine, 0, 6)."</option>\n";
	}
}
fclose($dplusFile);
while (!feof($dextraFile)) {
	$dextraLine = fgets($dextraFile);
	if (strpos($dextraLine, 'XRF') !== FALSE && strpos($dextraLine, '#') === FALSE)
		echo "	<option value=\"".substr($dextraLine, 0, 6)."\">".substr($dextraLine, 0, 6)."</option>\n";
}
fclose($dextraFile);

?>
    </select><input name="confDefRef" style="display:none;" disabled="disabled" type="text" size="7" maxlength="7"
            onblur="if(this.value==''){toggleField(this,this.previousSibling);}" />
    <select name="confDefRefLtr">
	<?php echo "  <option value=\"".substr($config_ircddbgateway['reflector1'], 7)."\" selected=\"selected\">".substr($config_ircddbgateway['reflector1'], 7)."</option>\n"; ?>
        <option>A</option>
        <option>B</option>
        <option>C</option>
        <option>D</option>
        <option>E</option>
        <option>F</option>
        <option>G</option>
        <option>H</option>
        <option>I</option>
        <option>J</option>
        <option>K</option>
        <option>L</option>
        <option>M</option>
        <option>N</option>
        <option>O</option>
        <option>P</option>
        <option>Q</option>
        <option>R</option>
        <option>S</option>
        <option>T</option>
        <option>U</option>
        <option>V</option>
        <option>W</option>
        <option>X</option>
        <option>Y</option>
        <option>Z</option>
    </select>
    </td>
    <td width="300">
    <input type="radio" name="confDefRefAuto" value="ON"<?php if ($config_ircddbgateway['atStartup1'] == '1') {echo ' checked="checked"';} ?> />Startup
    <input type="radio" name="confDefRefAuto" value="OFF"<?php if ($config_ircddbgateway['atStartup1'] == '0') {echo ' checked="checked"';} ?> />Manual</td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['aprs_host'];?>:<span><b>APRS Host</b>Set your prefered APRS host here</span></a></td>
    <td colspan="2" style="text-align: left;"><select name="selectedAPRSHost">
<?php
        $testAPSRHost = $config_ircddbgateway['aprsHostname'];
    	$aprsHostFile = fopen("/usr/local/etc/General/APRSHosts.txt", "r");
        while (!feof($aprsHostFile)) {
                $aprsHostFileLine = fgets($aprsHostFile);
                $aprsHost = preg_split('/:/', $aprsHostFileLine);
                if ((strpos($aprsHost[0], ';') === FALSE ) && ($aprsHost[0] != '')) {
                        if ($testAPSRHost == $aprsHost[0]) { echo "      <option value=\"$aprsHost[0]\" selected=\"selected\">$aprsHost[0]</option>\n"; }
                        else { echo "      <option value=\"$aprsHost[0]\">$aprsHost[0]</option>\n"; }
                }
        }
        fclose($aprsHostFile);
        ?>
    </select></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['dstar_irc_lang'];?>:<span><b>Language</b>Set your prefered<br /> language here</span></a></td>
    <td colspan="2" style="text-align: left;"><select name="ircDDBGatewayAnnounceLanguage">
<?php
        $testIrcLanguage = $config_ircddbgateway['language'];
	if (is_readable("/var/www/dashboard/config/ircddbgateway_languages.inc")) {
	  $ircLanguageFile = fopen("/var/www/dashboard/config/ircddbgateway_languages.inc", "r");
        while (!feof($ircLanguageFile)) {
                $ircLanguageFileLine = fgets($ircLanguageFile);
                $ircLanguage = preg_split('/;/', $ircLanguageFileLine);
                if ((strpos($ircLanguage[0], '#') === FALSE ) && ($ircLanguage[0] != '')) {
			$ircLanguage[2] = rtrim($ircLanguage[2]);
                        if ($testIrcLanguage == $ircLanguage[1]) { echo "      <option value=\"$ircLanguage[1],$ircLanguage[2]\" selected=\"selected\">".htmlspecialchars($ircLanguage[0])."</option>\n"; }
                        else { echo "      <option value=\"$ircLanguage[1],$ircLanguage[2]\">".htmlspecialchars($ircLanguage[0])."</option>\n"; }
                }
        }
          fclose($ircLanguageFile);
	}
        ?>
    </select></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['dstar_irc_time'];?>:<span><b>Time Announce</b>Announce time<br />hourly</span></a></td>
    <?php
	if ( !file_exists('/usr/local/etc/config/timeserver.dissable') ) {
		echo "<td align=\"left\" colspan=\"2\"><div class=\"switch\"><input id=\"toggle-timeAnnounce\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"confTimeAnnounce\" value=\"ON\" checked=\"checked\" /><label for=\"toggle-timeAnnounce\"></label></div></td>\n";
		}
	else {
		echo "<td align=\"left\" colspan=\"2\"><div class=\"switch\"><input id=\"toggle-timeAnnounce\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"confTimeAnnounce\" value=\"ON\" /><label for=\"toggle-timeAnnounce\"></label></div></td>\n";
	}
    ?>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#">Use DPlus for XRF:<span><b>No DExtra</b>Should host files<br />use DPlus Protocol for XRFs</span></a></td>
    <?php
	if ( $configPistarSystem['d-star']['dstarNoDExtra'] == "1" ) {
		echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-dplusHostFiles\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"confHostFilesNoDExtra\" value=\"ON\" checked=\"checked\" /><label for=\"toggle-dplusHostFiles\"></label></div></td>\n";
		}
	else {
		echo "<td align=\"left\"><div class=\"switch\"><input id=\"toggle-dplusHostFiles\" class=\"toggle toggle-round-flat\" type=\"checkbox\" name=\"confHostFilesNoDExtra\" value=\"ON\" /><label for=\"toggle-dplusHostFiles\"></label></div></td>\n";
	}
    ?>
    <td>Note: Update Required if changed</td>
    </tr>
    </table>
	<div><input type="button" value="<?php echo $lang['apply'];?>" onclick="submitform()" /><br /><br /></div>
<?php } ?>
<?php if (($configPistarSystem['software']['modemControlSoftware'] == "mmdvmhost") && $config_mmdvmhost['System Fusion Network']['Enable'] == 1) {
$ysfHosts = fopen("/usr/local/etc/YSFGateway/YSFHosts.txt", "r"); ?>
	<div><b><?php echo $lang['ysf_config'];?></b></div>
    <table>
    <tr>
    <th width="200"><a class="tooltip" href="#"><?php echo $lang['setting'];?><span><b>Setting</b></span></a></th>
    <th colspan="2"><a class="tooltip" href="#"><?php echo $lang['value'];?><span><b>Value</b>The current value from the<br />configuration files</span></a></th>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['ysf_startup_host'];?>:<span><b>YSF Host</b>Set your prefered<br /> YSF Host here</span></a></td>
    <td style="text-align: left;"><select name="ysfStartupHost">
<?php
        if (isset($config_ysfgateway['Network']['Startup'])) {
                $testYSFHost = $config_ysfgateway['Network']['Startup'];
                echo "      <option value=\"none\">None</option>\n";
		//if ($testYSFHost == 00002) {
		//	echo "      <option value=\"00002\" selected=\"selected\">00002 - YSF2DMR - YSF2DMR Bridge</option>\n";
		//	}
		//else {
		//	echo "      <option value=\"00002\">00002 - YSF2DMR - YSF2DMR Bridge</option>\n";
		//	}
        	}
        else {
                $testYSFHost = "none";
                echo "      <option value=\"none\" selected=\"selected\">None</option>\n";
		//echo "      <option value=\"00002\">00002 - YSF2DMR - YSF2DMR Gateway</option>\n";
                }
        while (!feof($ysfHosts)) {
                $ysfHostsLine = fgets($ysfHosts);
                $ysfHost = preg_split('/;/', $ysfHostsLine);
                if ((strpos($ysfHost[0], '#') === FALSE ) && ($ysfHost[0] != '')) {
                        if ($testYSFHost == $ysfHost[0]) { echo "      <option value=\"$ysfHost[0]\" selected=\"selected\">$ysfHost[0] - ".htmlspecialchars($ysfHost[1])." - ".htmlspecialchars($ysfHost[2])."</option>\n"; }
			else { echo "      <option value=\"$ysfHost[0]\">$ysfHost[0] - ".htmlspecialchars($ysfHost[1])." - ".htmlspecialchars($ysfHost[2])."</option>\n"; }
                }
        }
        fclose($ysfHosts);
        ?>
    </select></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['aprs_host'];?>:<span><b>APRS Host</b>Set your prefered APRS host here</span></a></td>
    <td colspan="2" style="text-align: left;"><select name="selectedAPRSHost">
<?php
        $testAPSRHost = $config_ircddbgateway['aprsHostname'];
    	$aprsHostFile = fopen("/usr/local/etc/General/APRSHosts.txt", "r");
        while (!feof($aprsHostFile)) {
                $aprsHostFileLine = fgets($aprsHostFile);
                $aprsHost = preg_split('/:/', $aprsHostFileLine);
                if ((strpos($aprsHost[0], ';') === FALSE ) && ($aprsHost[0] != '')) {
                        if ($testAPSRHost == $aprsHost[0]) { echo "      <option value=\"$aprsHost[0]\" selected=\"selected\">$aprsHost[0]</option>\n"; }
                        else { echo "      <option value=\"$aprsHost[0]\">$aprsHost[0]</option>\n"; }
                }
        }
        fclose($aprsHostFile);
        ?>
    </select></td>
    </tr>
    <?php if (($configPistarSystem['software']['modemControlSoftware'] == "mmdvmhost") && $config_ysf2dmr['Enabled']['Enabled'] == 1) {
    $dmrMasterFile = fopen("/usr/local/etc/MMDVMHost/DMR_Hosts.txt", "r"); ?>
    <tr>
      <td align="left"><a class="tooltip2" href="#"><?php echo $lang['dmr_id'];?>:<span><b>CCS7/DMR ID</b>Enter your CCS7 / DMR ID here</span></a></td>
      <td align="left" colspan="2"><input type="text" name="ysf2dmrId" size="13" maxlength="9" value="<?php if (isset($config_ysf2dmr['DMR Network']['Id'])) { echo $config_ysf2dmr['DMR Network']['Id']; } ?>" /></td>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['dmr_master'];?>:<span><b>DMR Master (YSF2DMR)</b>Set your prefered DMR<br /> master here</span></a></td>
    <td style="text-align: left;"><select name="ysf2dmrMasterHost">
<?php
        $testMMDVMysf2dmrMaster = $config_ysf2dmr['DMR Network']['Address'];
        while (!feof($dmrMasterFile)) {
                $dmrMasterLine = fgets($dmrMasterFile);
                $dmrMasterHost = preg_split('/\s+/', $dmrMasterLine);
                if ((strpos($dmrMasterHost[0], '#') === FALSE ) && (substr($dmrMasterHost[0], 0, 3) != "XLX") && (substr($dmrMasterHost[0], 0, 4) != "DMRG") && ($dmrMasterHost[0] != '')) {
                        if ($testMMDVMysf2dmrMaster == $dmrMasterHost[2]) { echo "      <option value=\"$dmrMasterHost[2],$dmrMasterHost[3],$dmrMasterHost[4],$dmrMasterHost[0]\" selected=\"selected\">$dmrMasterHost[0]</option>\n"; $dmrMasterNow = $dmrMasterHost[0]; }
                        else { echo "      <option value=\"$dmrMasterHost[2],$dmrMasterHost[3],$dmrMasterHost[4],$dmrMasterHost[0]\">$dmrMasterHost[0]</option>\n"; }
                }
        }
        fclose($dmrMasterFile);
        ?>
    </select></td>
    </tr>
    <tr>
      <td align="left"><a class="tooltip2" href="#">DMR TG:<span><b>YSF2DMR TG</b>Enter your DMR TG here</span></a></td>
      <td align="left" colspan="2"><input type="text" name="ysf2dmrTg" size="13" maxlength="7" value="<?php if (isset($config_ysf2dmr['DMR Network']['StartupDstId'])) { echo $config_ysf2dmr['DMR Network']['StartupDstId']; } ?>" /></td>  
    </tr>
    <?php } ?>
    </table>
	<div><input type="button" value="<?php echo $lang['apply'];?>" onclick="submitform()" /><br /><br /></div>
<?php } ?>
<?php if (($configPistarSystem['software']['modemControlSoftware'] == "mmdvmhost") && $config_mmdvmhost['P25 Network']['Enable'] == 1) {
$p25Hosts = fopen("/usr/local/etc/P25Gateway/P25Hosts.txt", "r");
	?>
	<div><b><?php echo $lang['p25_config'];?></b></div>
    <table>
    <tr>
    <th width="200"><a class="tooltip" href="#"><?php echo $lang['setting'];?><span><b>Setting</b></span></a></th>
    <th colspan="2"><a class="tooltip" href="#"><?php echo $lang['value'];?><span><b>Value</b>The current value from the<br />configuration files</span></a></th>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['p25_startup_host'];?>:<span><b>P25 Host</b>Set your prefered<br /> P25 Host here</span></a></td>
    <td style="text-align: left;"><select name="p25StartupHost">
<?php
	$testP25Host = $config_p25gateway['Network']['Startup'];
	if ($testP25Host == "") { echo "      <option value=\"none\" selected=\"selected\">None</option>\n"; }
        else { echo "      <option value=\"none\">None</option>\n"; }
	if ($testP25Host == "10") { echo "      <option value=\"10\" selected=\"selected\">10 - Parrot</option>\n"; }
        else { echo "      <option value=\"10\">10 - Parrot</option>\n"; }
        while (!feof($p25Hosts)) {
                $p25HostsLine = fgets($p25Hosts);
                $p25Host = preg_split('/\s+/', $p25HostsLine);
                if ((strpos($p25Host[0], '#') === FALSE ) && ($p25Host[0] != '')) {
                        if ($testP25Host == $p25Host[0]) { echo "      <option value=\"$p25Host[0]\" selected=\"selected\">$p25Host[0] - $p25Host[1]</option>\n"; }
                        else { echo "      <option value=\"$p25Host[0]\">$p25Host[0] - $p25Host[1]</option>\n"; }
                }
        }
        fclose($p25Hosts);
        if (file_exists('/usr/local/etc/P25Gateway/P25HostsLocal.txt')) {
		$p25Hosts2 = fopen("/usr/local/etc/P25Gateway/P25HostsLocal.txt", "r");
		while (!feof($p25Hosts2)) {
                	$p25HostsLine2 = fgets($p25Hosts2);
                	$p25Host2 = preg_split('/\s+/', $p25HostsLine2);
                	if ((strpos($p25Host2[0], '#') === FALSE ) && ($p25Host2[0] != '')) {
                        	if ($testP25Host == $p25Host2[0]) { echo "      <option value=\"$p25Host2[0]\" selected=\"selected\">$p25Host2[0] - $p25Host2[1]</option>\n"; }
                        	else { echo "      <option value=\"$p25Host2[0]\">$p25Host2[0] - $p25Host2[1]</option>\n"; }
                	}
		}
		fclose($p25Hosts2);
	}
        ?>
    </select></td>
    </tr>
<?php if ($config_mmdvmhost['P25']['NAC']) { ?>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['p25_nac'];?>:<span><b>P25 NAC</b>Set your NAC<br /> code here</span></a></td>
    <td align="left"><input type="text" name="p25nac" size="13" maxlength="3" value="<?php echo $config_mmdvmhost['P25']['NAC'];?>" /></td>
    </tr>
<?php } ?>
    </table>
	<div><input type="button" value="<?php echo $lang['apply'];?>" onclick="submitform()" /><br /><br /></div>
<?php } ?>
	
<?php if (($configPistarSystem['software']['modemControlSoftware'] == "mmdvmhost") && $config_mmdvmhost['NXDN Network']['Enable'] == 1) { ?>
	<div><b><?php echo $lang['nxdn_config'];?></b></div>
    <table>
      <tr>
        <th width="200"><a class="tooltip" href="#"><?php echo $lang['setting'];?><span><b>Setting</b></span></a></th>
        <th colspan="2"><a class="tooltip" href="#"><?php echo $lang['value'];?><span><b>Value</b>The current value from the<br />configuration files</span></a></th>
      </tr>
      <tr>
        <td align="left"><a class="tooltip2" href="#"><?php echo $lang['nxdn_startup_host'];?>:<span><b>NXDN Host</b>Set your prefered<br /> NXDN Host here</span></a></td>
        <td style="text-align: left;"><select name="nxdnStartupHost">
<?php
	if (file_exists('/usr/local/etc/config/nxdngateway')) {
		$nxdnHosts = fopen("/usr/local/etc/NXDNGateway/NXDNHosts.txt", "r");
		$testNXDNHost = $config_nxdngateway['Network']['Startup'];
		if ($testNXDNHost == "") { echo "      <option value=\"none\" selected=\"selected\">None</option>\n"; }
	        else { echo "      <option value=\"none\">None</option>\n"; }
		if ($testNXDNHost == "10") { echo "      <option value=\"10\" selected=\"selected\">10 - Parrot</option>\n"; }
	        else { echo "      <option value=\"10\">10 - Parrot</option>\n"; }
	        while (!feof($nxdnHosts)) {
	                $nxdnHostsLine = fgets($nxdnHosts);
	                $nxdnHost = preg_split('/\s+/', $nxdnHostsLine);
	                if ((strpos($nxdnHost[0], '#') === FALSE ) && ($nxdnHost[0] != '')) {
	                        if ($testNXDNHost == $nxdnHost[0]) { echo "      <option value=\"$nxdnHost[0]\" selected=\"selected\">$nxdnHost[0] - $nxdnHost[1]</option>\n"; }
	                        else { echo "      <option value=\"$nxdnHost[0]\">$nxdnHost[0] - $nxdnHost[1]</option>\n"; }
	                }
	        }
	        fclose($nxdnHosts);
	        if (file_exists('/usr/local/etc/NXDNHostsLocal.txt')) {
			$nxdnHosts2 = fopen("/usr/local/etc/P25Gateway/P25HostsLocal.txt", "r");
			while (!feof($nxdnHosts2)) {
	                	$nxdnHostsLine2 = fgets($nxdnHosts2);
	                	$nxdnHost2 = preg_split('/\s+/', $nxdnHostsLine2);
	                	if ((strpos($nxdnHost2[0], '#') === FALSE ) && ($nxdnHost2[0] != '')) {
	                        	if ($testnxdnHost == $nxdnHost2[0]) { echo "      <option value=\"$nxdnHost2[0]\" selected=\"selected\">$nxdnHost2[0] - $nxdnHost2[1]</option>\n"; }
	                        	else { echo "      <option value=\"$nxdnHost2[0]\">$nxdnHost2[0] - $nxdnHost2[1]</option>\n"; }
	                	}
			}
			fclose($nxdnHosts2);
		}
	} else {
		echo '<option value="176.9.1.168">D2FET Test Host - 176.9.1.168</option>'."\n";
	}
?>
        </select></td>
      </tr>
    <?php if ($config_mmdvmhost['NXDN']['RAN']) { ?>
      <tr>
        <td align="left"><a class="tooltip2" href="#"><?php echo $lang['nxdn_ran'];?>:<span><b>NXDN RAN</b>Set your RAN<br /> code here</span></a></td>
        <td align="left"><input type="text" name="nxdnran" size="13" maxlength="1" value="<?php echo $config_mmdvmhost['NXDN']['RAN'];?>" /></td>
      </tr>
    <?php } ?>
    </table>
	<div><input type="button" value="<?php echo $lang['apply'];?>" onclick="submitform()" /><br /><br /></div>
<?php } ?>
	
	<div><b><?php echo $lang['fw_config'];?></b></div>
    <table>
    <tr>
    <th width="200"><a class="tooltip" href="#"><?php echo $lang['setting'];?><span><b>Setting</b></span></a></th>
    <th colspan="2"><a class="tooltip" href="#"><?php echo $lang['value'];?><span><b>Value</b>The current value from the<br />configuration files</span></a></th>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['fw_dash'];?>:<span><b>Dashboard Access</b>Do you want the dashboard access<br />to be publicly available? This<br />modifies the uPNP firewall<br />Configuration.</span></a></td>
    <?php
	$testPrvPubDash = exec('sudo sed -n 32p /usr/local/sbin/pistar-upnp.service | cut -c 1');
	if (substr($testPrvPubDash, 0, 1) === '#') {
		echo "   <td align=\"left\" colspan=\"2\"><input type=\"radio\" name=\"dashAccess\" value=\"PRV\" checked=\"checked\" />Private <input type=\"radio\" name=\"dashAccess\" value=\"PUB\" />Public</td>\n";
		}
	else {
		echo "   <td align=\"left\" colspan=\"2\"><input type=\"radio\" name=\"dashAccess\" value=\"PRV\" />Private <input type=\"radio\" name=\"dashAccess\" value=\"PUB\" checked=\"checked\" />Public</td>\n";
	}
    ?>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['fw_irc'];?>:<span><b>ircDDBGateway Remote Access</b>Do you want the ircDDBGateway<br />remote controll access to be<br />publicly available? This modifies<br />the uPNP firewall Configuration.</span></a></td>
    <?php
	$testPrvPubIRC = exec('sudo sed -n 33p /usr/local/sbin/pistar-upnp.service | cut -c 1');
	if (substr($testPrvPubIRC, 0, 1) === '#') {
		echo "   <td align=\"left\" colspan=\"2\"><input type=\"radio\" name=\"ircRCAccess\" value=\"PRV\" checked=\"checked\" />Private <input type=\"radio\" name=\"ircRCAccess\" value=\"PUB\" />Public</td>\n";
		}
	else {
		echo "   <td align=\"left\" colspan=\"2\"><input type=\"radio\" name=\"ircRCAccess\" value=\"PRV\" />Private <input type=\"radio\" name=\"ircRCAccess\" value=\"PUB\" checked=\"checked\" />Public</td>\n";
	}
    ?>
    </tr>
    <tr>
    <td align="left"><a class="tooltip2" href="#"><?php echo $lang['fw_ssh'];?>:<span><b>SSH Access</b>Do you want access to be<br />publicly available over SSH (used<br />for support issues)? This modifies<br />the uPNP firewall Configuration.</span></a></td>
    <?php
	$testPrvPubSSH = exec('sudo sed -n 31p /usr/local/sbin/pistar-upnp.service | cut -c 1');
	if (substr($testPrvPubSSH, 0, 1) === '#') {
		echo "   <td align=\"left\" colspan=\"2\"><input type=\"radio\" name=\"sshAccess\" value=\"PRV\" checked=\"checked\" />Private <input type=\"radio\" name=\"sshAccess\" value=\"PUB\" />Public</td>\n";
		}
	else {
		echo "   <td align=\"left\" colspan=\"2\"><input type=\"radio\" name=\"sshAccess\" value=\"PRV\" />Private <input type=\"radio\" name=\"sshAccess\" value=\"PUB\" checked=\"checked\" />Public</td>\n";
	}
    ?>
    </tr>
    <?php if (file_exists('/etc/default/hostapd')) { ?>
    <tr>
      <td align="left"><a class="tooltip2" href="#">Auto AP:<span><b>Auto AP</b>Do you want your Pi<br />to create its own AP<br />if it cannot connect to<br />WiFi within 120 secs of boot</span></a></td>
      <?php
        if ($configPistarSystem['wifi']['wifiAutoAP'] == "0") {
	  echo "   <td align=\"left\"><input type=\"radio\" name=\"autoAP\" value=\"ON\" />On <input type=\"radio\" name=\"autoAP\" value=\"OFF\" checked=\"checked\" />Off</td>\n";
	}
        else {
	  echo "   <td align=\"left\"><input type=\"radio\" name=\"autoAP\" value=\"ON\" checked=\"checked\" />On <input type=\"radio\" name=\"autoAP\" value=\"OFF\" />Off</td>\n";
	}
      ?>
      <td>Note: Reboot Required if changed</td>
    </tr>
    <?php } ?>
    </table>
	<div><input type="button" value="<?php echo $lang['apply'];?>" onclick="submitform()" /></div>
    </form>

<?php
//	exec('ifconfig wlan0',$return);
//	exec('iwconfig wlan0',$return);
//	$strWlan0 = implode(" ",$return);
//	$strWlan0 = preg_replace('/\s\s+/', ' ', $strWlan0);
//	if (strpos($strWlan0,'HWaddr') !== false) {
//		preg_match('/HWaddr ([0-9a-f:]+)/i',$strWlan0,$result);
//	}
//	elseif (strpos($strWlan0,'ether') !== false) {
//		preg_match('/ether ([0-9a-f:]+)/i',$strWlan0,$result);
//	}
//	$strHWAddress = $result['1'];
//
//	if ( isset($strHWAddress) ) {
	if ( file_exists('/sys/class/net/wlan0') || file_exists('/sys/class/net/wlan1') ) {
echo '
<br />
    <b>'.$lang['wifi_config'].'</b>
    <table><tr><td>
    <iframe frameborder="0" scrolling="auto" name="wifi" src="wifi.php?page=wlan0_info" width="100%" onload="javascript:resizeIframe(this);">If you can see this message, your browser does not support iFrames, however if you would like to see the content please click <a href="wifi.php?page=wlan0_info">here</a>.</iframe>
    </td></tr></table>'; } ?>

<br />
	<div><b><?php echo $lang['remote_access_pw'];?></b></div>
    <form id="adminPassForm" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <table>
    <tr><th width="200"><?php echo $lang['user'];?></th><th colspan="3"><?php echo $lang['password'];?></th></tr>
    <tr>
    <td align="left"><b>pi-star</b></td>
    <td align="left"><label for="pass1">Password:</label><input type="password" name="adminPassword" id="pass1" onkeyup="checkPass(); return false;" size="30"/>
    <label for="pass2">Confirm Password:</label><input type="password" name="adminPassword" id="pass2" onkeyup="checkPass(); return false;">
    <br /><span id="confirmMessage" class="confirmMessage"></span></td>
    <td align="right"><input type="button" id="submitpwd" value="<?php echo $lang['set_password'];?>" onclick="submitPassform()" disabled/></td>
    </tr>
    <tr><td colspan="3"><b>WARNING:</b> This changes the password for this admin page<br />AND the "pi-star" SSH account</td></tr>
    </table>
    </form>
<?php endif; ?>

<?php } else { ?>

<?php } ?>
</div><!-- End Content Wide -->
