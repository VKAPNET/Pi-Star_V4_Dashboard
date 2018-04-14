<?php

// Load ALL the configs
if (file_exists('/usr/local/etc/pi-star/system.ini')) {
  $config_system = parse_ini_file("/usr/local/etc/pi-star/system.ini", true);
}
if (file_exists('/usr/local/etc/pi-star/pi-star.ini')) {
  $config_radio = parse_ini_file("/usr/local/etc/pi-star/pi-star.ini", true);
}
if (file_exists('/usr/local/etc/pi-star/css-overlay.ini')) {
  $config_dashboard = parse_ini_file("/usr/local/etc/pi-star/css-overlay.ini", true);
}
if (file_exists('/etc/pistar-release')) {
  $config_pistar_release = parse_ini_file("/etc/pistar-release", true);
}
if (file_exists('/usr/local/etc/config/mmdvmhost')) {
  $config_mmdvmhost = parse_ini_file("/usr/local/etc/config/mmdvmhost", true);
}
if (file_exists('/usr/local/etc/config/dmrgateway')) {
  $config_dmrgateway = parse_ini_file("/usr/local/etc/config/dmrgateway", true);
}
if (file_exists('/usr/local/etc/config/p25gateway')) {
  $config_p25gateway = parse_ini_file("/usr/local/etc/config/p25gateway", true);
}
if (file_exists('/usr/local/etc/config/ysfgateway')) {
  $config_ysfgateway = parse_ini_file("/usr/local/etc/config/ysfgateway", true);
}
if (file_exists('/usr/local/etc/config/ysf2dmr')) {
  $config_ysf2dmr = parse_ini_file("/usr/local/etc/config/ysf2dmr", true);
}
// Load the dstarrepeater config file
$config_dstarrepeater = array();
if ($config_dstarrepeaterFile = fopen('/usr/local/etc/config/dstarrepeater','r')) {
        while ($line1 = fgets($config_dstarrepeaterFile)) {
		if (strpos($line1, '=') !== false) {
                	list($key1,$value1) = preg_split('/=/', $line1, 2);
                	$value1 = trim(str_replace('"','',$value1));
                	if (strlen($value1) > 0)
                	$config_dstarrepeater[$key1] = $value1;
		}
        }
}
// Load the ircDDBGateway config file
$config_ircddbgateway = array();
if ($config_ircddbgatewayFile = fopen('/usr/local/etc/config/ircddbgateway','r')) {
        while ($line0 = fgets($config_ircddbgatewayFile)) {
		if (strpos($line0, '=') !== false) {
                	list($key0,$value0) = preg_split('/=/', $line0, 2);
                	$value0 = trim(str_replace('"','',$value0));
                	if ($key0 != 'ircddbPassword' && strlen($value0) > 0)
                	$config_ircddbgateway[$key0] = $value0;
		}
        }
}

// System Info
function cpuTemp() {
  static $output;

  // Check the cache
  if (apcu_exists('cpuTemp')) {
    $output = apcu_fetch('cpuTemp');
  }

  if ( $output !== null ) { return $output; }
  else {
    $cpuTempCRaw = shell_exec('cat /sys/class/thermal/thermal_zone0/temp');
    if ($cpuTempCRaw > 1000) { $cpuTempC = round($cpuTempCRaw / 1000, 1); } else { $cpuTempC = round($cpuTempCRaw, 1); }
    $cpuTempF = round(+$cpuTempC * 9 / 5 + 32, 1);

    if (floatval($cpuTempC) <= "49") { $output = "<td style=\"background:#0b0;\">".$cpuTempC."&deg;C / ".$cpuTempF."&deg;F</td>\n"; }
    if (floatval($cpuTempC) >= "50") { $output = "<td style=\"background:orange;\">".$cpuTempC."&deg;C / ".$cpuTempF."&deg;F</td>\n"; }
    if (floatval($cpuTempC) >= "69") { $output = "<td style=\"background:#b00;\">".$cpuTempC."&deg;C / ".$cpuTempF."&deg;F</td>\n"; }

    return $output;

    // Add the value to the Cache
    apcu_add('cpuTemp', $output, 5);
  }
}

// Get the CPU Load
$cpuLoad = sys_getloadavg();

// Convert to MHz
function getMHz($freq) {
  return substr($freq,0,3) . "." . substr($freq,3,6) . " MHz";
}

// Process Check
function isProcessRunning($processname) {
  // Setup APCP Cache
  static $output;
  $apcu_field = "isProcessRunning_".$processname;
  // Check the Cache
  if (apcu_exists($apcu_field)) { $output = apcu_fetch($apcu_field); }
  else {
    $pids = shell_exec("ps -ef | grep '".$processname."' | grep -v grep");
    if(empty($pids)) {
      // process not running!
      $output = false;
    } else {
      // process running!
      $output = true;
    }
    // Cache the output
    apcu_add($apcu_field, $output, 10);
  }
  // Add the value to the Cache
  return $output;
}

function timeToLocal($utc_time) {
  $utc_tz =  new DateTimeZone('UTC');
  $local_tz = new DateTimeZone(date_default_timezone_get());
  $dt = new DateTime($utc_time, $utc_tz);
  $dt->setTimeZone($local_tz);
  $localised_time = $dt->format('H:i:s M jS');
  return $localised_time;
}

function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ') {
  $str = '';
  $max = mb_strlen($keyspace, '8bit') - 1;
  for ($i = 0; $i < $length; ++$i) {
    $str .= $keyspace[random_int(0, $max)];
  }
  return $str;
}

function aprspass ($callsign) {
  $stophere = strpos($callsign, '-');
  if ($stophere) $callsign = substr($callsign, 0, $stophere);
  $realcall = strtoupper(substr($callsign, 0, 10));

  // initialize hash
  $hash = 0x73e2;
  $i = 0;
  $len = strlen($realcall);

  // hash callsign two bytes at a time
  while ($i < $len) {
    $hash ^= ord(substr($realcall, $i, 1))<<8;
    $hash ^= ord(substr($realcall, $i + 1, 1));
    $i += 2;
  }

  // mask off the high bit so number is always positive
  return $hash & 0x7fff;
}

?>
