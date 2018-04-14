<?php
if ($_SERVER["PHP_SELF"] == "/admin/configure.php") {

  if (!empty($_POST)) {
    // Take in the post values and write them to the config.

    // Initial Config
    if (empty($_POST['isConfigured']) != TRUE ) {
      if (escapeshellcmd($_POST['isConfigured']) == 'ON' )  { $config_radio['configStatus']['isConfigured'] = 1; }
    }

    // Modem Software
    if (empty($_POST['modemControlSoftware']) != TRUE ) {
      if (escapeshellcmd($_POST['modemControlSoftware']) === "mmdvmhost") { $config_radio['software']['modemControlSoftware'] = "mmdvmhost"; }
      if (escapeshellcmd($_POST['modemControlSoftware']) === "dstarrepeater") { $config_radio['software']['modemControlSoftware'] = "dstarrepeater"; }
    }

    // Simplex / Duplex
    if (empty($_POST['modemSplit']) != TRUE ) {
      if (escapeshellcmd($_POST['modemSplit']) == 'ON' )  { $config_radio['modem']['modemSplit'] = 1; } else { $config_radio['modem']['modemSplit'] = 0; }
    }

    // Modem Configuration(s)
    if (empty($_POST['modemHardware']) != TRUE ) {
      $modemHardware = escapeshellcmd($_POST['modemHardware']);

      // Modem - DVMega Single Band GPIO
      if ( $modemHardware == 'dvmega-pi-single' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyAMA0"; $config_radio['modem']['modemDuplex'] = "0"; }
      // Modem - DVMega Dual Band GPIO
      if ( $modemHardware == 'dvmega-pi-dual' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyAMA0"; $config_radio['modem']['modemDuplex'] = "0"; }
      // Modem - DVMega on Arduino
      if ( $modemHardware == 'dvmega-ardruino-dual' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyUSB0"; $config_radio['modem']['modemDuplex'] = "0"; }
      // Modem - DVMega on Arduino Alt
      if ( $modemHardware == 'dvmega-ardruino-dual-alt' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyACM0"; $config_radio['modem']['modemDuplex'] = "0"; }
      // Modem - DVMega on Arduino GMSK
      if ( $modemHardware == 'dvmega-ardruino-gmsk' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyUSB0"; if ($config_radio['modem']['modemSplit'] === 1) { $config_radio['modem']['modemDuplex'] = "1"; } else { $config_radio['modem']['modemDuplex'] = "0"; } }
      // Modem - DVMega on Arduino GMSK Alt
      if ( $modemHardware == 'dvmega-ardruino-gmsk-alt' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyACM0"; if ($config_radio['modem']['modemSplit'] === 1) { $config_radio['modem']['modemDuplex'] = "1"; } else { $config_radio['modem']['modemDuplex'] = "0"; } }
      // Modem - DVMega on Bluestack Single
      if ( $modemHardware == 'dvmega-bluestack-single' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyUSB0"; $config_radio['modem']['modemDuplex'] = "0"; }
      // Modem - DVMega on Bluestack Dual
      if ( $modemHardware == 'dvmega-bluestack-dual' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyUSB0"; $config_radio['modem']['modemDuplex'] = "0"; }
      // Modem - Generic GMSK Modem
      if ( $modemHardware == 'gmsk-modem' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyACM0"; if ($config_radio['modem']['modemSplit'] === 1) { $config_radio['modem']['modemDuplex'] = "1"; } else { $config_radio['modem']['modemDuplex'] = "0"; } }
      // Modem - DVR-PTR V1
      if ( $modemHardware == 'dvr-ptr-v1' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyACM0"; if ($config_radio['modem']['modemSplit'] === 1) { $config_radio['modem']['modemDuplex'] = "1"; } else { $config_radio['modem']['modemDuplex'] = "0"; } }
      // Modem - DVR-PTR V2
      if ( $modemHardware == 'dvr-ptr-v2' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyACM0"; if ($config_radio['modem']['modemSplit'] === 1) { $config_radio['modem']['modemDuplex'] = "1"; } else { $config_radio['modem']['modemDuplex'] = "0"; } }
      // Modem - DVR-PTR V3
      if ( $modemHardware == 'dvr-ptr-v3' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyACM0"; if ($config_radio['modem']['modemSplit'] === 1) { $config_radio['modem']['modemDuplex'] = "1"; } else { $config_radio['modem']['modemDuplex'] = "0"; } }
      // Modem - DVAP
      if ( $modemHardware == 'dvap' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyUSB0"; $config_radio['modem']['modemDuplex'] = "0"; }
      // Modem - MMDVM Generic
      if ( $modemHardware == 'mmdvm-generic' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyACM0"; if ($config_radio['modem']['modemSplit'] === 1) { $config_radio['modem']['modemDuplex'] = "1"; } else { $config_radio['modem']['modemDuplex'] = "0"; } }
      // Modem - STM32-DVM
      if ( $modemHardware == 'stm32dvm-gpio' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyAMA0"; $config_radio['modem']['modemDuplex'] = "0"; }
      // Modem - STM32-DVM USB
      if ( $modemHardware == 'stm32dvm-usb' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyUSB0"; if ($config_radio['modem']['modemSplit'] === 1) { $config_radio['modem']['modemDuplex'] = "1"; } else { $config_radio['modem']['modemDuplex'] = "0"; } }
      // Modem - ZUMSpot Libre
      if ( $modemHardware == 'zumspot-libre' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyACM0"; $config_radio['modem']['modemDuplex'] = "0"; }
      // Modem - ZUMSpot USB
      if ( $modemHardware == 'zumspot-usb' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyACM0"; $config_radio['modem']['modemDuplex'] = "0"; }
      // Modem - ZUMSpot GPIO
      if ( $modemHardware == 'zumspot-gpio' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyAMA0"; $config_radio['modem']['modemDuplex'] = "0"; }
      // Modem - MicroNode Nano Spot
      if ( $modemHardware == 'micronode-nano-spot' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyAMA0"; $config_radio['modem']['modemDuplex'] = "0"; }
      // Modem - MicroNode Teensy
      if ( $modemHardware == 'micronode-nano-spot' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyUSB0"; if ($config_radio['modem']['modemSplit'] === 1) { $config_radio['modem']['modemDuplex'] = "1"; } else { $config_radio['modem']['modemDuplex'] = "0"; } }
      // Modem - F4M GPIO Hat
      if ( $modemHardware == 'mmdvm-f4m-gpio' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyAMA0"; $config_radio['modem']['modemDuplex'] = "0"; }
      // Modem - MMDVM_HS Hat Single Band
      if ( $modemHardware == 'mmdvm-hshat-gpio' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyAMA0"; $config_radio['modem']['modemDuplex'] = "0"; }
      // Modem - MMDVM_HS Hat Dual Band
      if ( $modemHardware == 'mmdvm-hshat-dual-gpio' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyAMA0"; if ($config_radio['modem']['modemSplit'] === 1) { $config_radio['modem']['modemDuplex'] = "1"; } else { $config_radio['modem']['modemDuplex'] = "0"; } }
      // Modem - BG3MDO Single Band Hat
      if ( $modemHardware == 'mmdvm-mdo-gpio' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyAMA0"; $config_radio['modem']['modemDuplex'] = "0"; }
      // Modem - VR2VYE Single Band Hat
      if ( $modemHardware == 'mmdvm-vye-gpio' ) { $config_radio['modem']['modemHardware'] = $modemHardware; $config_radio['modem']['modemPort'] = "/dev/ttyAMA0"; $config_radio['modem']['modemDuplex'] = "0"; }
    }

    // Mode Selection
    if (empty($_POST['enable_dstar'])	!= TRUE ) { if (escapeshellcmd($_POST['enable_dstar'])		== 'ON')  { $config_radio['modes']['enable_dstar']	= "1"; } else { $config_radio['modes']['enable_dstar']		= "0"; } }
    if (empty($_POST['enable_dmr'])	!= TRUE ) { if (escapeshellcmd($_POST['enable_dmr'])		== 'ON')  { $config_radio['modes']['enable_dmr']	= "1"; } else { $config_radio['modes']['enable_dmr']		= "0"; } }
    if (empty($_POST['enable_ysf'])	!= TRUE ) { if (escapeshellcmd($_POST['enable_ysf'])		== 'ON')  { $config_radio['modes']['enable_ysf'] 	= "1"; } else { $config_radio['modes']['enable_ysf']		= "0"; } }
    if (empty($_POST['enable_p25'])	!= TRUE ) { if (escapeshellcmd($_POST['enable_p25'])		== 'ON')  { $config_radio['modes']['enable_p25']	= "1"; } else { $config_radio['modes']['enable_p25']		= "0"; } }
    if (empty($_POST['enable_nxdn'])	!= TRUE ) { if (escapeshellcmd($_POST['enable_nxdn'])		== 'ON')  { $config_radio['modes']['enable_nxdn']	= "1"; } else { $config_radio['modes']['enable_nxdn']		= "0"; } }
    if (empty($_POST['enable_ysf2dmr'])	!= TRUE ) { if (escapeshellcmd($_POST['enable_ysf2dmr'])	== 'ON')  { $config_radio['modes']['enable_ysf2dmr']	= "1"; } else { $config_radio['modes']['enable_ysf2dmr']	= "0"; } }

    // Hang Timers
    if (empty($_POST['dstarRFHangTime'])	!= TRUE ) { $config_radio['dstar']['modeHangRF']	= preg_replace('/[^0-9]/', '', $_POST['dstarRFHangTime']); }
    if (empty($_POST['dstarNetHangTime'])	!= TRUE ) { $config_radio['dstar']['modeHangNet']	= preg_replace('/[^0-9]/', '', $_POST['dstarNetHangTime']); }
    if (empty($_POST['dmrRFHangTime'])		!= TRUE ) { $config_radio['dmr']['modeHangRF']		= preg_replace('/[^0-9]/', '', $_POST['dmrRFHangTime']); }
    if (empty($_POST['dmrNetHangTime'])		!= TRUE ) { $config_radio['dmr']['modeHangNet']		= preg_replace('/[^0-9]/', '', $_POST['dmrNetHangTime']); }
    if (empty($_POST['ysfRFHangTime'])		!= TRUE ) { $config_radio['ysf']['modeHangRF']		= preg_replace('/[^0-9]/', '', $_POST['ysfRFHangTime']); }
    if (empty($_POST['ysfNetHangTime'])		!= TRUE ) { $config_radio['ysf']['modeHangNet']		= preg_replace('/[^0-9]/', '', $_POST['ysfNetHangTime']); }
    if (empty($_POST['p25RFHangTime'])		!= TRUE ) { $config_radio['p25']['modeHangRF']		= preg_replace('/[^0-9]/', '', $_POST['p25RFHangTime']); }
    if (empty($_POST['p25NetHangTime'])		!= TRUE ) { $config_radio['p25']['modeHangNet']		= preg_replace('/[^0-9]/', '', $_POST['p25NetHangTime']); }
    if (empty($_POST['nxdnRFHangTime'])		!= TRUE ) { $config_radio['nxdn']['modeHangRF']		= preg_replace('/[^0-9]/', '', $_POST['nxdnRFHangTime']); }
    if (empty($_POST['nxdnNetHangTime'])	!= TRUE ) { $config_radio['nxdn']['modeHangNet']	= preg_replace('/[^0-9]/', '', $_POST['nxdnNetHangTime']); }

    // MMDVMHost Display and Port settings
    if (empty($_POST['mmdvmDisplayType']) != TRUE ) { $config_radio['display']['mmdvmDisplayType'] = escapeshellcmd($_POST['mmdvmDisplayType']); }
    if (empty($_POST['mmdvmDisplayPort']) != TRUE ) { $config_radio['display']['mmdvmDisplayPort'] = escapeshellcmd($_POST['mmdvmDisplayPort']); }
    if (escapeshellcmd($_POST['mmdvmDisplayLayout']) == "G4KLX") { $config_radio['display']['mmdvmDisplayLayout'] = "0"; }
    if (escapeshellcmd($_POST['mmdvmDisplayLayout']) == "ON7LDS") { $config_radio['display']['mmdvmDisplayLayout'] = "2"; }

    // Pi-Star config file wrangling
    $radioContent = "";
    $tmpFileName = random_str(32).".tmp";
    foreach($config_radio as $radioSection=>$radioValues) {
      // UnBreak special cases
      $radioContent .= "[".$radioSection."]\n";
      // append the values
      foreach($radioValues as $radioKey=>$radioValue) {
        $radioContent .= $radioKey."=".$radioValue."\n";
      }
      $radioContent .= "\n";
    }
    if (!$handleRadioConfig = fopen('/tmp/'.$tmpFileName, 'w')) {
      return false;
    }
    if (!is_writable('/tmp/'.$tmpFileName)) {
      echo '  <div class="contentwide"><!-- Start the wide output -->'."\n";
      echo "    <br />\n";
      echo "    <table>\n";
      echo "      <tr><th>ERROR</th></tr>\n";
      echo "      <tr><td>Unable to write configuration file(s)...</td><tr>\n";
      echo "      <tr><td>Please wait a few seconds and retry...</td></tr>\n";
      echo "    </table>\n";
      echo "    <br />\n";
      unset($_POST);
      echo '    <script type="text/javascript">setTimeout(function() { window.location=window.location;},2500);</script>';
      echo '  </div><!-- End the wide output -->'."\n";
      die();
    }
    else {
      system('sudo mount -o remount,rw /');
      $success = fwrite($handleRadioConfig, $radioContent);
      fclose($handleRadioConfig);
      if (intval(exec('cat /tmp/'.$tmpFileName.' | wc -l')) > 80 ) {
        exec('sudo mv /tmp/'.$tmpFileName.' /usr/local/etc/pi-star/radio.ini');		// Move the file back
        exec('sudo chmod 644 /usr/local/etc/pi-star/radio.ini');			// Set the correct runtime permissions
        exec('sudo chown root:root /usr/local/etc/pi-star/radio.ini');			// Set the owner
      } else {
        exec('rm -rf /tmp/'.$tmpFileName);						// Clean up
      }
    }

   echo '  <div class="contentwide"><!-- Start the wide output -->'."\n";
   echo "    <br />\n";
   echo "    <table>\n";
   echo "      <tr><th>Re-Writing Configuration</th></tr>\n";
   echo "      <tr><td>Writing configuration file(s)...</td><tr>\n";
   echo "    </table>\n";
   echo "    <br />\n";
   unset($_POST);
   echo '    <script type="text/javascript">setTimeout(function() { window.location=window.location;},3000);</script>';
   echo '  </div><!-- End the wide output -->'."\n";
  }

  else {
    function tableStart($langSetting, $langValue) {
      echo '      <table class="table">'."\n";
      echo '       <tr>'."\n";
      echo '         <th width="300"><a class="tooltip" href="#">'.$langSetting.'<span><b>Setting</b></span></a></th>'."\n";
      echo '         <th colspan="2"><a class="tooltip" href="#">'.$langValue.'<span><b>Value</b>The current value from<br />the configuration files</span></a></th>'."\n";
      echo '       </tr>'."\n";
    }

    function tableEnd($langApply) {
      echo '      </table>'."\n";
      echo '      <div><input type="button" value="'.$langApply.'" onclick="submitform()" /><br /><br /></div>'."\n";
    }

    function sectionStart($sectName) {
     echo '      <div><b>'.$sectName.'</b></div>'."\n";
    }

    // No Post data so we output the form
    echo '  <div class="contentwide"><!-- Start the wide output -->'."\n";
    echo '    <form id="config" action="'.htmlspecialchars($_SERVER["PHP_SELF"]).'" method="post">'."\n";
    echo '    <input type="hidden" name="isConfigured" value="ON" />'."\n";
    sectionStart($lang['control_software']);
    tableStart($lang['setting'], $lang['value']);
    echo '      <tr>'."\n";
    echo '        <td align="right"><a class="tooltip2" href="#">'.$lang['controller_software'].':<span><b>Radio Control Software</b>Choose the software used<br />to control the DV Radio Module<br />PLease note that DV Mega hardware<br />will require a firmware upgrade.</span></a></td>'."\n";
    echo '        <td align="left" colspan="2"><select name="modemControlSoftware"><option value="mmdvmhost"'; if ($config_radio['software']['modemControlSoftware'] == "mmdvmhost") { echo ' selected=""'; }; echo '>MMDVMHost - For driving all MMDVM and compatible hardware</option><option value="dstarrepeater"'; if ($config_radio['software']['modemControlSoftware'] == "dstarrepeater") { echo ' selected=""'; }; echo '>DStarRepeater - Legacy DStarRepeater System</option></select></td>'."\n";
    echo '      </tr>'."\n";
    echo '      <tr>'."\n";
    echo '        <td align="right"><a class="tooltip2" href="#">'.$lang['controller_mode'].':<span><b>TRX Mode</b>Choose the mode type<br />Simplex node or<br />Duplex repeater.</span></a></td>'."\n";
    echo '        <td align="left" colspan="2"><select name="modemSplit"><option value="OFF"'; if ($config_radio['modem']['modemSplit'] == "0") { echo ' selected=""'; }; echo '>Simplex - Single Frequency</option><option value="ON"'; if ($config_radio['modem']['modemSplit'] == "1") { echo ' selected=""'; }; echo '>Duplex/Split - Seperate TX/RX Frequencies</option></select></td>'."\n";
    echo '      </tr>'."\n";
    echo '      <tr>'."\n";
    echo '        <td align="right"><a class="tooltip2" href="#">'.$lang['radio_type'].':<span><b>Radio/Modem</b>What kind of radio or modem<br />hardware do you have ?</span></a></td>'."\n";
    echo '        <td align="left" colspan="2"><select name="modemHardware">'."\n";
    echo '          <option'; if (!$config_radio['modem']['modemHardware'])					{ echo ' selected="selected"';}; echo ' value="">--</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'dvmega-pi-single')		{ echo ' selected="selected"';}; echo ' value="dvmega-pi-single"	>DV-Mega Raspberry Pi Hat (GPIO) - Single Band (70cm)</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'dvmega-pi-dual')			{ echo ' selected="selected"';}; echo ' value="dvmega-pi-dual"		>DV-Mega Raspberry Pi Hat (GPIO) - Dual Band</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'dvmega-ardruino-dual')		{ echo ' selected="selected"';}; echo ' value="dvmega-ardruino-dual"	>DV-Mega on Arduino (USB - /dev/ttyUSB0) - Dual Band</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'dvmega-ardruino-dual-alt')	{ echo ' selected="selected"';}; echo ' value="dvmega-ardruino-dual-alt">DV-Mega on Arduino (USB - /dev/ttyACM0) - Dual Band</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'dvmega-ardruino-gmsk')		{ echo ' selected="selected"';}; echo ' value="dvmega-ardruino-gmsk"	>DV-Mega on Arduino (USB - /dev/ttyUSB0) - GMSK Modem</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'dvmega-ardruino-gmsk-alt')	{ echo ' selected="selected"';}; echo ' value="dvmega-ardruino-gmsk-alt">DV-Mega on Arduino (USB - /dev/ttyACM0) - GMSK Modem</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'dvmega-bluestack-single')	{ echo ' selected="selected"';}; echo ' value="dvmega-bluestack-single"	>DV-Mega on Bluestack (USB) - Single Band (70cm)</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'dvmega-bluestack-dual')		{ echo ' selected="selected"';}; echo ' value="dvmega-bluestack-dual"	>DV-Mega on Bluestack (USB) - Dual Band</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'gmsk-modem')			{ echo ' selected="selected"';}; echo ' value="gmsk-modem"		>GMSK Modem (USB DStarRepeater Only)</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'dvr-ptr-v1')			{ echo ' selected="selected"';}; echo ' value="dvr-ptr-v1"		>DV-RPTR V1 (USB)</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'dvr-ptr-v2')			{ echo ' selected="selected"';}; echo ' value="dvr-ptr-v2"		>DV-RPTR V2 (USB)</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'dvr-ptr-v3')			{ echo ' selected="selected"';}; echo ' value="dvr-ptr-v3"		>DV-RPTR V3 (USB)</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'dvap')				{ echo ' selected="selected"';}; echo ' value="dvap"			>DVAP (USB)</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'mmdvm-generic')			{ echo ' selected="selected"';}; echo ' value="mmdvm-generic"		>MMDVM / MMDVM_HS / Teensy / ZUM (USB)</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'stm32dvm-gpio')			{ echo ' selected="selected"';}; echo ' value="stm32dvm-gpio"		>STM32-DVM / MMDVM_HS - Raspberry Pi Hat (GPIO)</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'stm32dvm-usb')			{ echo ' selected="selected"';}; echo ' value="stm32dvm-usb"		>STM32-DVM (USB)</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'zumspot-libre')			{ echo ' selected="selected"';}; echo ' value="zumspot-libre"		>ZumSpot Libre (USB)</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'zumspot-usb')			{ echo ' selected="selected"';}; echo ' value="zumspot-usb"		>ZumSpot - USB Stick</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'zumspot-gpio')			{ echo ' selected="selected"';}; echo ' value="zumspot-gpio"		>ZumSpot - Raspberry Pi Hat (GPIO)</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'zumradio-gpio')			{ echo ' selected="selected"';}; echo ' value="zumradio-gpio"		>ZUM Radio-MMDVM for Pi (GPIO)</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'micronode-nano-spot')		{ echo ' selected="selected"';}; echo ' value="micronode-nano-spot"	>MicroNode Nano-Spot (Built In)</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'micronode-teensy')		{ echo ' selected="selected"';}; echo ' value="micronode-teensy"	>MicroNode Teensy (USB)</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'mmdvm-f4m-gpio')			{ echo ' selected="selected"';}; echo ' value="mmdvm-f4m-gpio"		>MMDVM F4M-GPIO (GPIO)</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'mmdvm-hshat-gpio')		{ echo ' selected="selected"';}; echo ' value="mmdvm-hshat-gpio"	>MMDVM_HS_Hat (DB9MAT &amp; DF2ET) for Pi (GPIO)</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'mmdvm-hshat-dual-gpio')		{ echo ' selected="selected"';}; echo ' value="mmdvm-hshat-dual-gpio"	>MMDVM_HS_Hat Dual (DB9MAT &amp; DF2ET) for Pi (GPIO)</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'mmdvm-mdo-gpio')			{ echo ' selected="selected"';}; echo ' value="mmdvm-mdo-gpio"		>MMDVM_HS_MDO Hat (BG3MDO) for Pi (GPIO)</option>'."\n";
    echo '          <option'; if ($config_radio['modem']['modemHardware'] === 'mmdvm-vye-gpio')			{ echo ' selected="selected"';}; echo ' value="mmdvm-vye-gpio"		>MMDVM_HS_NPi Hat (VR2VYE) for Nano Pi (GPIO)</option>'."\n";
    echo '        </select></td>'."\n";
    echo '      </tr>'."\n";
    tableEnd($lang['apply']);

    if ($config_radio['configStatus']['isConfigured']) {
      // Only show this when the basics are configured....

      if ($config_radio['software']['modemControlSoftware'] === "mmdvmhost") {
        // MMDVMHost Specific Options
        echo '      <input type="hidden" name="enable_dstar"	value="OFF" />'."\n";
        echo '      <input type="hidden" name="enable_dmr"	value="OFF" />'."\n";
        echo '      <input type="hidden" name="enable_ysf"	value="OFF" />'."\n";
        echo '      <input type="hidden" name="enable_p25"	value="OFF" />'."\n";
        echo '      <input type="hidden" name="enable_nxdn"	value="OFF" />'."\n";
        echo '      <input type="hidden" name="enable_ysf2dmr"	value="OFF" />'."\n";
        sectionStart($lang['mmdvmhost_config']);
        tableStart($lang['setting'], $lang['value']);
        // D-Star Mode
        echo '      <tr>'."\n";
        echo '        <td align="right"><a class="tooltip2" href="#">'.$lang['d-star_mode'].':<span><b>D-Star Mode</b>Turn on D-Star Features</span></a></td>'."\n";
	if ( $config_radio['modes']['enable_dstar'] == 1 ) { echo '        <td align="left"><div class="switch"><input id="toggle-dstar" class="toggle toggle-round-flat" type="checkbox" name="enable_dstar" value="ON" checked="checked" /><label for="toggle-dstar"></label></div></td>'."\n"; }
	  else { echo '<td align="left"><div class="switch"><input id="toggle-dstar" class="toggle toggle-round-flat" type="checkbox" name="enable_dstar" value="ON" /><label for="toggle-dstar"></label></div></td>'."\n"; }
        echo '        <td align="left">RF Hangtime: <input type="text" name="dstarRFHangTime" size="7" maxlength="3" value="'; if (isset($config_radio['dstar']['dstarRFHangTime'])) { echo $config_radio['dstar']['dstarRFHangTime']; } else { echo '20'; }; echo '" />';
        echo 'Net Hangtime: <input type="text" name="dstarNetHangTime" size="7" maxlength="3" value="'; if (isset($config_radio['dstar']['dstarNetHangTime'])) { echo $config_radio['dstar']['dstarNetHangTime']; } else { echo '20'; }; echo '" /></td>'."\n";
        echo '      </tr>'."\n";
        // DMR Mode
        echo '      <tr>'."\n";
        echo '        <td align="right"><a class="tooltip2" href="#">'.$lang['dmr_mode'].':<span><b>DMR Mode</b>Turn on DMR Features</span></a></td>'."\n";
	if ( $config_radio['modes']['enable_dmr'] == 1 ) { echo '        <td align="left"><div class="switch"><input id="toggle-dmr" class="toggle toggle-round-flat" type="checkbox" name="enable_dmr" value="ON" checked="checked" /><label for="toggle-dmr"></label></div></td>'."\n"; }
	  else { echo '<td align="left"><div class="switch"><input id="toggle-dmr" class="toggle toggle-round-flat" type="checkbox" name="enable_dmr" value="ON" /><label for="toggle-dmr"></label></div></td>'."\n"; }
        echo '        <td align="left">RF Hangtime: <input type="text" name="dmrRFHangTime" size="7" maxlength="3" value="'; if (isset($config_radio['dmr']['dmrRFHangTime'])) { echo $config_radio['dmr']['dmrRFHangTime']; } else { echo '20'; }; echo '" />';
        echo 'Net Hangtime: <input type="text" name="dmrNetHangTime" size="7" maxlength="3" value="'; if (isset($config_radio['dmr']['dmrNetHangTime'])) { echo $config_radio['dmr']['dmrNetHangTime']; } else { echo '20'; }; echo '" /></td>'."\n";
        echo '      </tr>'."\n";
        // YSF Mode
        echo '      <tr>'."\n";
        echo '        <td align="right"><a class="tooltip2" href="#">'.$lang['ysf_mode'].':<span><b>YSF Mode</b>Turn on YSF Features</span></a></td>'."\n";
	if ( $config_radio['modes']['enable_ysf'] == 1 ) { echo '        <td align="left"><div class="switch"><input id="toggle-ysf" class="toggle toggle-round-flat" type="checkbox" name="enable_ysf" value="ON" checked="checked" /><label for="toggle-ysf"></label></div></td>'."\n"; }
	  else { echo '<td align="left"><div class="switch"><input id="toggle-ysf" class="toggle toggle-round-flat" type="checkbox" name="enable_ysf" value="ON" /><label for="toggle-ysf"></label></div></td>'."\n"; }
        echo '        <td align="left">RF Hangtime: <input type="text" name="ysfRFHangTime" size="7" maxlength="3" value="'; if (isset($config_radio['ysf']['ysfRFHangTime'])) { echo $config_radio['ysf']['ysfRFHangTime']; } else { echo '20'; }; echo '" />';
        echo 'Net Hangtime: <input type="text" name="ysfNetHangTime" size="7" maxlength="3" value="'; if (isset($config_radio['ysf']['ysfNetHangTime'])) { echo $config_radio['ysf']['ysfNetHangTime']; } else { echo '20'; }; echo '" /></td>'."\n";
        echo '      </tr>'."\n";
        // P25 Mode
        echo '      <tr>'."\n";
        echo '        <td align="right"><a class="tooltip2" href="#">'.$lang['p25_mode'].':<span><b>P25 Mode</b>Turn on P25 Features</span></a></td>'."\n";
	if ( $config_radio['modes']['enable_p25'] == 1 ) { echo '        <td align="left"><div class="switch"><input id="toggle-p25" class="toggle toggle-round-flat" type="checkbox" name="enable_p25" value="ON" checked="checked" /><label for="toggle-p25"></label></div></td>'."\n"; }
	  else { echo '<td align="left"><div class="switch"><input id="toggle-p25" class="toggle toggle-round-flat" type="checkbox" name="enable_p25" value="ON" /><label for="toggle-p25"></label></div></td>'."\n"; }
        echo '        <td align="left">RF Hangtime: <input type="text" name="p25RFHangTime" size="7" maxlength="3" value="'; if (isset($config_radio['p25']['p25RFHangTime'])) { echo $config_radio['p25']['p25RFHangTime']; } else { echo '20'; }; echo '" />';
        echo 'Net Hangtime: <input type="text" name="p25NetHangTime" size="7" maxlength="3" value="'; if (isset($config_radio['p25']['p25NetHangTime'])) { echo $config_radio['p25']['p25NetHangTime']; } else { echo '20'; }; echo '" /></td>'."\n";
        echo '      </tr>'."\n";
        // NXDN Mode
        echo '      <tr>'."\n";
        echo '        <td align="right"><a class="tooltip2" href="#">'.$lang['nxdn_mode'].':<span><b>NXDN Mode</b>Turn on NXDN Features</span></a></td>'."\n";
	if ( $config_radio['modes']['enable_nxdn'] == 1 ) { echo '        <td align="left"><div class="switch"><input id="toggle-nxdn" class="toggle toggle-round-flat" type="checkbox" name="enable_nxdn" value="ON" checked="checked" /><label for="toggle-nxdn"></label></div></td>'."\n"; }
	  else { echo '<td align="left"><div class="switch"><input id="toggle-nxdn" class="toggle toggle-round-flat" type="checkbox" name="enable_nxdn" value="ON" /><label for="toggle-nxdn"></label></div></td>'."\n"; }
        echo '        <td align="left">RF Hangtime: <input type="text" name="nxdnRFHangTime" size="7" maxlength="3" value="'; if (isset($config_radio['nxdn']['nxdnRFHangTime'])) { echo $config_radio['nxdn']['nxdnRFHangTime']; } else { echo '20'; }; echo '" />';
        echo 'Net Hangtime: <input type="text" name="nxdnNetHangTime" size="7" maxlength="3" value="'; if (isset($config_radio['nxdn']['nxdnNetHangTime'])) { echo $config_radio['nxdn']['nxdnNetHangTime']; } else { echo '20'; }; echo '" /></td>'."\n";
        echo '      </tr>'."\n";
        // YSF2DMR Mode
        echo '      <tr>'."\n";
        echo '        <td align="right"><a class="tooltip2" href="#">YSF2DMR:<span><b>YSF2DMR Mode</b>Turn on YSF2DMR Features</span></a></td>'."\n";
	if ( $config_radio['modes']['enable_ysf2dmr'] == 1 ) { echo '        <td colspan="2" align="left"><div class="switch"><input id="toggle-ysf2dmr" class="toggle toggle-round-flat" type="checkbox" name="enable_ysf2dmr" value="ON" checked="checked" /><label for="toggle-ysf2dmr"></label></div></td>'."\n"; }
	  else { echo '<td colspan="2" align="left"><div class="switch"><input id="toggle-ysf2dmr" class="toggle toggle-round-flat" type="checkbox" name="enable_ysf2dmr" value="ON" /><label for="toggle-ysf2dmr"></label></div></td>'."\n"; }
        echo '      </tr>'."\n";
        echo '      <tr>'."\n";
        echo '        <td align="right"><a class="tooltip2" href="#">'.$lang['mmdvm_display'].':<span><b>Display Type</b>Choose your display<br />type if you have one.</span></a></td>'."\n";
        echo '        <td align="left" colspan="2"><select class="short" name="mmdvmDisplayType">'."\n";
        echo '          <option '; if (($config_radio['display']['mmdvmDisplayType'] == "None") || ($config_radio['display']['mmdvmDisplayType'] == "") ) { echo 'selected="selected" '; }; echo 'value="None">None</option>'."\n";
        echo '          <option '; if ($config_radio['display']['mmdvmDisplayType'] == "oled") { echo 'selected="selected" '; }; echo 'value="oled">OLED</option>'."\n";
        echo '          <option '; if ($config_radio['display']['mmdvmDisplayType'] == "nextion") { echo 'selected="selected" '; }; echo 'value="nextion">Nextion</option>'."\n";
        echo '          <option '; if ($config_radio['display']['mmdvmDisplayType'] == "hd44780") { echo 'selected="selected" '; }; echo 'value="hd44780">HD44780</option>'."\n";
        echo '          <option '; if ($config_radio['display']['mmdvmDisplayType'] == "tftSerial") { echo 'selected="selected" '; }; echo 'value="tftSerial">TFT Serial</option>'."\n";
        echo '          <option '; if ($config_radio['display']['mmdvmDisplayType'] == "ldcProc") { echo 'selected="selected" '; }; echo 'value="lcdProc">LCD Proc</option>'."\n";
        echo '       </select>'."\n";
        echo '       Port: <select class="short" name="mmdvmDisplayPort">'."\n";
        echo '         <option '; if (($config_radio['display']['mmdvmDisplayType'] == "None") || ($config_radio['display']['mmdvmDisplayType'] == "") ) { echo 'selected="selected" '; }; echo 'value="None">None</option>'."\n";
        echo '         <option '; if ($config_radio['display']['mmdvmDisplayPort'] == "modem") { echo 'selected="selected" '; }; echo 'value="modem">Modem</option>'."\n";
        echo '         <option '; if ($config_radio['display']['mmdvmDisplayPort'] == "/dev/ttyAMA0") { echo 'selected="selected" '; }; echo 'value="/dev/ttyAMA0">/dev/ttyAMA0</option>'."\n";
        echo '         <option '; if ($config_radio['display']['mmdvmDisplayPort'] == "/dev/ttyUSB0") { echo 'selected="selected" '; }; echo 'value="/dev/ttyUSB0">/dev/ttyUSB0</option>'."\n";
        if (file_exists('/dev/ttyS2')) {
          echo '         <option '; if ($config_radio['display']['port'] == "/dev/ttyS2") { echo 'selected="selected" '; }; echo 'value="/dev/ttyS2">/dev/ttyS2</option>'."\n";
        }
        if (file_exists('/dev/ttyNextionDriver')) {
	  echo '         <option '; if ($config_radio['display']['port'] == "/dev/ttyNextionDriver") { echo 'selected="selected" '; }; echo 'value="/dev/ttyNextionDriver">/dev/ttyNextionDriver</option>'."\n";
        }
        echo '       </select>'."\n";
        echo '       Nextion Layout: <select class="short" name="mmdvmDisplayLayout">'."\n";
        echo '         <option '; if ($config_radio['display']['mmdvmDisplayLayout'] == "0") { echo 'selected="selected" '; }; echo 'value="G4KLX">G4KLX</option>'."\n";
        echo '         <option '; if ($config_radio['display']['mmdvmDisplayLayout'] == "1") { echo 'selected="selected" '; }; echo 'value="ON7LDS">ON7LDS</option>'."\n";
        echo '       </select></td>'."\n";
        echo '     </tr>'."\n";
        tableEnd($lang['apply']);
      }
      // General Settings
      sectionStart($lang['general_config']);
      tableStart($lang['setting'], $lang['value']);
      echo '     <tr>'."\n";
      echo '       <td align="right"><a class="tooltip2" href="#">'.$lang['hostname'].':<span><b>System Hostname</b>This is the system<br />hostname, used for access<br />to the dashboard etc.</span></a></td>'."\n";
      echo '       <td align="left" colspan="2"><input type="text" name="hostnameConfigured" size="15" maxlength="15" value="'; echo exec('cat /etc/hostname'); echo '" />Do not add suffixes such as .local</td>'."\n";
      echo '     </tr>'."\n";
      echo '     <tr>'."\n";
      echo '       <td align="right"><a class="tooltip2" href="#">'.$lang['node_call'].':<span><b>Gateway Callsign</b>This is your licenced callsign for use<br />on this gateway, do not append<br />the "G"</span></a></td>'."\n";
      echo '       <td align="left" colspan="2"><input type="text" name="callsign" size="15" maxlength="7" value="'.$config_radio['identity']['callsign'].'" /></td>'."\n";
      echo '     </tr>'."\n";
      echo '     <tr>'."\n";
      echo '       <td align="right"><a class="tooltip2" href="#">'.$lang['dmr_id'].':<span><b>CCS7/DMR ID</b>Enter your CCS7 / DMR ID here</span></a></td>'."\n";
      echo '       <td align="left" colspan="2"><input type="text" name="dmrId" size="15" maxlength="9" value="'.$config_radio['identity']['dmrId'].'" /></td>'."\n";
      echo '     </tr>'."\n";
      echo '     <tr>'."\n";
      echo '       <td align="right"><a class="tooltip2" href="#">NXDN ID:<span><b>NXDN ID</b>Enter your NXDN ID here</span></a></td>'."\n";
      echo '       <td align="left" colspan="2"><input type="text" name="nxdnId" size="15" maxlength="5" value="'.$config_radio['identity']['nxdnId'].'" /></td>'."\n";
      echo '     </tr>'."\n";
      if ($config_radio['modem']['modemFreqTx'] === $config_radio['modem']['modemFreqRx']) {
        echo '     <tr>'."\n";
	echo '       <td align="right"><a class="tooltip2" href="#">'.$lang['radio_freq'].':<span><b>Radio Frequency</b>This is the Frequency your<br />Pi-Star is on</span></a></td>'."\n";
	echo '       <td align="left" colspan="2"><input type="text" name="modemFreq" size="15" maxlength="12" value="'.number_format($config_radio['modem']['modemFreqRx'], 0, '.', '.').'" />MHz</td>'."\n";
	echo '     </tr>'."\n";
      } else {
        echo '     <tr>'."\n";
	echo '       <td align="right"><a class="tooltip2" href="#">'.$lang['radio_freq'].' RX:<span><b>Radio Frequency</b>This is the Frequency your<br />repeater will listen on</span></a></td>'."\n";
	echo '       <td align="left" colspan="2"><input type="text" name="modemFreqRx" size="15" maxlength="12" value="'.number_format($config_radio['modem']['modemFreqRx'], 0, '.', '.').'" />MHz</td>'."\n";
	echo '     </tr>'."\n";
	echo '     <tr>'."\n";
	echo '       <td align="right"><a class="tooltip2" href="#">'.$lang['radio_freq'].' TX:<span><b>Radio Frequency</b>This is the Frequency your<br />repeater will transmit on</span></a></td>'."\n";
	echo '       <td align="left" colspan="2"><input type="text" name="modemFreqTx" size="15" maxlength="12" value="'.number_format($config_radio['modem']['modemFreqTx'], 0, '.', '.').'" />MHz</td>'."\n";
	echo '     </tr>'."\n";
      }
      echo '     <tr>'."\n";
      echo '       <td align="right"><a class="tooltip2" href="#">'.$lang['lattitude'].':<span><b>Gateway Latitude</b>This is the latitude where the<br />gateway is located (positive<br />number for North, negative<br />number for South)</span></a></td>'."\n";
      echo '       <td align="left" colspan="2"><input type="text" name="latitude" size="15" maxlength="9" value="'.$config_radio['location']['latitude'].'" />degrees (positive value for North, negative for South)</td>'."\n";
      echo '     </tr>'."\n";
      echo '     <tr>'."\n";
      echo '       <td align="right"><a class="tooltip2" href="#">'.$lang['longitude'].':<span><b>Gateway Longitude</b>This is the longitude where the<br />gateway is located (positive<br />number for East, negative<br />number for West)</span></a></td>'."\n";
      echo '       <td align="left" colspan="2"><input type="text" name="longitude" size="15" maxlength="9" value="'.$config_radio['location']['longitude'].'" />degrees (positive value for East, negative for West)</td>'."\n";
      echo '     </tr>'."\n";
      echo '     <tr>'."\n";
      echo '       <td align="right"><a class="tooltip2" href="#">'.$lang['town'].':<span><b>Gateway Town</b>The town where the gateway<br />is located</span></a></td>'."\n";
      echo '       <td align="left" colspan="2"><input type="text" name="town" size="30" maxlength="30" value="'.$config_radio['location']['town'].'" /></td>'."\n";
      echo '     </tr>'."\n";
      echo '     <tr>'."\n";
      echo '       <td align="right"><a class="tooltip2" href="#">'.$lang['country'].':<span><b>Gateway Country</b>The country where the gateway<br />is located</span></a></td>'."\n";
      echo '       <td align="left" colspan="2"><input type="text" name="country" size="30" maxlength="30" value="'.$config_radio['location']['country'].'" /></td>'."\n";
      echo '     </tr>'."\n";
      echo '     <tr>'."\n";
      echo '       <td align="right"><a class="tooltip2" href="#">'.$lang['url'].':<span><b>Gateway URL</b>The URL used to access<br />this dashboard</span></a></td>'."\n";
      echo '       <td align="left"><input type="text" name="url" size="30" maxlength="30" value="'.$config_radio['location']['url'].'" /></td>'."\n";
      echo '       <td width="300">'."\n";
      echo '         <input type="radio" name="urlAuto" value="auto"'; if (strpos($config_radio['location']['url'], 'www.qrz.com/db/'.$config_radio['identity']['callsign']) !== FALSE) {echo ' checked="checked"';}; echo ' />Auto'."\n";
      echo '         <input type="radio" name="urlAuto" value="man"';  if (strpos($config_radio['location']['url'], 'www.qrz.com/db/'.$config_radio['identity']['callsign'])  == FALSE) {echo ' checked="checked"';}; echo ' />Manual'."\n";
      echo '       </td>'."\n";
      echo '     </tr>'."\n";
      echo '     <tr>'."\n";
      echo '       <td align="right"><a class="tooltip2" href="#">'.$lang['timezone'].':<span><b>System TimeZone</b>Set the system timezone</span></a></td>'."\n";
      echo '       <td align="left" colspan="2"><select name="systemTimezone">'."\n";
                     exec('timedatectl list-timezones', $tzList);
                     exec('cat /etc/timezone', $tzCurrent);
                     foreach ($tzList as $timeZone) {
                       if ($timeZone == $tzCurrent[0]) { echo '         <option selected="selected" value="'.$timeZone.'">'.$timeZone.'</option>'."\n"; }
                       else { echo '         <option value="'.$timeZone.'">'.$timeZone.'</option>'."\n"; }
                     }
      echo '       </select></td>'."\n";
      echo '     </tr>'."\n";
      echo '     <tr>'."\n";
      echo '       <td align="right"><a class="tooltip2" href="#">'.$lang['dash_lang'].':<span><b>Dashboard Language</b>Set the language for<br />the dashboard.</span></a></td>'."\n";
      echo '       <td align="left" colspan="2"><select name="language">'."\n";
                     $langDir = '/var/www/dashboard/private/language';
                     if (is_dir($langDir)) {
                       if ($dh = opendir($langDir)) {
                         while ($files[] = readdir($dh))
                         sort($files); // Add sorting for the Language(s)
                         foreach ($files as $file) {
                           if (($file != 'index.php') && ($file != '.') && ($file != '..') && ($file != '')) {
                             $file = substr($file, 0, -4);
                             if ($file == $config_radio['location']['language']) { echo '         <option selected="selected" value="'.$file.'">'.$file.'</option>'."\n"; }
                             else { echo '         <option value="'.$file.'">'.$file.'</option>'."\n"; }
                           }
                         }
                       closedir($dh);
                       }
                     }
      echo '       </select></td>'."\n";
      echo '     </tr>'."\n";
      echo '     <tr>'."\n";
      echo '       <td align="right"><a class="tooltip2" href="#">'.$lang['aprs_host'].':<span><b>APRS Host</b>Set your prefered APRS host here</span></a></td>'."\n";
      echo '       <td colspan="2" style="text-align: left;"><select name="aprsHost">'."\n";
        $testAPSRHost = $config_radio['aprs']['aprsHost'];
    	$aprsHostFile = fopen('/usr/local/etc/General/APRSHosts.txt', 'r');
        while (!feof($aprsHostFile)) {
                $aprsHostFileLine = fgets($aprsHostFile);
                $aprsHost = preg_split('/:/', $aprsHostFileLine);
                if ((strpos($aprsHost[0], ';') === FALSE ) && ($aprsHost[0] != '')) {
                        if ($testAPSRHost == $aprsHost[0]) { echo '         <option value="'.$aprsHost[0].'" selected="selected">'.$aprsHost[0].'</option>'."\n"; }
                        else { echo '         <option value="'.$aprsHost[0].'">'.$aprsHost[0].'</option>'."\n"; }
                }
        }
        fclose($aprsHostFile);
      echo '       </select></td>'."\n";
      echo '     </tr>'."\n";
      tableEnd($lang['apply']);

      // D-Star Settings
      echo '   <input type="hidden" name="dstarTimeAnnounce" value="OFF" />'."\n";
      echo '   <input type="hidden" name="dstarUseDPlusForXRF" value="OFF" />'."\n";
      sectionStart($lang['dstar_config']);
      tableStart($lang['setting'], $lang['value']);
      echo '     <td align="right"><a class="tooltip2" href="#">'.$lang['dstar_rpt1'].':<span><b>RPT1 Callsign</b>This is the RPT1 field for your radio</span></a></td>'."\n";
      echo '     <td align="left" colspan="2">'.str_replace(' ', '&nbsp;', str_pad($config_radio['identity']['callsign'], 7));
      echo '       <select class="short" name="dstarModuleLetter">'."\n";
      echo '         <option value="'.$config_radio['dstar']['dstarModuleLetter'].'" selected="selected">'.$config_radio['dstar']['dstarModuleLetter'].'</option>'."\n";
      echo '         <option>A</option>'."\n";
      echo '         <option>B</option>'."\n";
      echo '         <option>C</option>'."\n";
      echo '         <option>D</option>'."\n";
      echo '         <option>E</option>'."\n";
      echo '         <option>F</option>'."\n";
      echo '         <option>G</option>'."\n";
      echo '         <option>H</option>'."\n";
      echo '         <option>I</option>'."\n";
      echo '         <option>J</option>'."\n";
      echo '         <option>K</option>'."\n";
      echo '         <option>L</option>'."\n";
      echo '         <option>M</option>'."\n";
      echo '         <option>N</option>'."\n";
      echo '         <option>O</option>'."\n";
      echo '         <option>P</option>'."\n";
      echo '         <option>Q</option>'."\n";
      echo '         <option>R</option>'."\n";
      echo '         <option>S</option>'."\n";
      echo '         <option>T</option>'."\n";
      echo '         <option>U</option>'."\n";
      echo '         <option>V</option>'."\n";
      echo '         <option>W</option>'."\n";
      echo '         <option>X</option>'."\n";
      echo '         <option>Y</option>'."\n";
      echo '         <option>Z</option>'."\n";
      echo '       </select></td>'."\n";
      echo '     </tr>'."\n";
      echo '     <tr>'."\n";
      echo '       <td align="right"><a class="tooltip2" href="#">'.$lang['dstar_rpt2'].':<span><b>RPT2 Callsign</b>This is the RPT2 field for your radio</span></a></td>'."\n";
      echo '       <td align="left" colspan="2">'.str_replace(' ', '&nbsp;', str_pad($config_radio['identity']['callsign'], 7)).'G</td>'."\n";
      echo '     </tr>'."\n";
      echo '     <tr>'."\n";
      echo '       <td align="right"><a class="tooltip2" href="#">'.$lang['dstar_irc_password'].':<span><b>Gateway Password</b>Used for any kind of remote<br />access to this system</span></a></td>'."\n";
      echo '       <td align="left" colspan="2"><input type="password" name="dstarRemotePassword" size="30" maxlength="30" value="'.$config_radio['dstar']['dstarRemotePassword'].'" /></td>'."\n";
      echo '     </tr>'."\n";
      echo '     <tr>'."\n";
      echo '       <td align="right"><a class="tooltip2" href="#">'.$lang['dstar_default_ref'].':<span><b>Default Refelctor</b>Used for setting the<br />default reflector.</span></a></td>'."\n";
      echo '       <td align="left" colspan="1"><select class="short" name="dstarDefaultRef"'."\n";
      echo '         onchange="if (this.options[this.selectedIndex].value == \'customOption\') {'."\n";
      echo '           toggleField(this,this.nextSibling);'."\n";
      echo '           this.selectedIndex=\'0\';'."\n";
      echo '         } ">'."\n";
      $dcsFile = fopen("/usr/local/etc/ircDDBGateway/DCS_Hosts.txt", "r");
      $dplusFile = fopen("/usr/local/etc/ircDDBGateway/DPlus_Hosts.txt", "r");
      $dextraFile = fopen("/usr/local/etc/ircDDBGateway/DExtra_Hosts.txt", "r");
      echo '         <option value="'.substr($config_radio['dstar']['dstarDefaultRef'], 0, 6).'" selected="selected">'.substr($config_radio['dstar']['dstarDefaultRef'], 0, 6).'</option>'."\n";
      echo '         <option value="customOption">Text Entry</option>'."\n";

      // DCS Hosts
      while (!feof($dcsFile)) {
        $dcsLine = fgets($dcsFile);
        if (strpos($dcsLine, 'DCS') !== FALSE && strpos($dcsLine, '#') === FALSE) {
          echo '         <option value="'.substr($dcsLine, 0, 6).'">'.substr($dcsLine, 0, 6).'</option>'."\n";
        }
      }
      fclose($dcsFile);

      // DPlus Hosts
      while (!feof($dplusFile)) {
        $dplusLine = fgets($dplusFile);
        if (strpos($dplusLine, 'REF') !== FALSE && strpos($dplusLine, '#') === FALSE) {
          echo '         <option value="'.substr($dplusLine, 0, 6).'">'.substr($dplusLine, 0, 6).'</option>'."\n";
        }
        if (strpos($dplusLine, 'XRF') !== FALSE && strpos($dplusLine, '#') === FALSE) {
          echo '         <option value="'.substr($dplusLine, 0, 6).'">'.substr($dplusLine, 0, 6).'</option>'."\n";
        }
      }
      fclose($dplusFile);

      // DExtra Hosts
      while (!feof($dextraFile)) {
        $dextraLine = fgets($dextraFile);
        if (strpos($dextraLine, 'XRF') !== FALSE && strpos($dextraLine, '#') === FALSE) {
          echo '         <option value="'.substr($dextraLine, 0, 6).'">'.substr($dextraLine, 0, 6).'</option>'."\n";
        }
      }
      fclose($dextraFile);

      echo '       </select><input name="dstarDefaultRef" style="display:none;" disabled="disabled" type="text" size="7" maxlength="7"'."\n";
      echo '        onblur="if(this.value==\'\'){toggleField(this,this.previousSibling);}" />'."\n";
      echo '      <select class="short" name="dstarDefaultRefSuffix">'."\n";
      echo '         <option value="'.substr($config_radio['dstar']['dstarDefaultRef'], 7).'" selected="selected">'.substr($config_radio['dstar']['dstarDefaultRef'], 7).'</option>'."\n";
      echo '         <option>A</option>'."\n";
      echo '         <option>B</option>'."\n";
      echo '         <option>C</option>'."\n";
      echo '         <option>D</option>'."\n";
      echo '         <option>E</option>'."\n";
      echo '         <option>F</option>'."\n";
      echo '         <option>G</option>'."\n";
      echo '         <option>H</option>'."\n";
      echo '         <option>I</option>'."\n";
      echo '         <option>J</option>'."\n";
      echo '         <option>K</option>'."\n";
      echo '         <option>L</option>'."\n";
      echo '         <option>M</option>'."\n";
      echo '         <option>N</option>'."\n";
      echo '         <option>O</option>'."\n";
      echo '         <option>P</option>'."\n";
      echo '         <option>Q</option>'."\n";
      echo '         <option>R</option>'."\n";
      echo '         <option>S</option>'."\n";
      echo '         <option>T</option>'."\n";
      echo '         <option>U</option>'."\n";
      echo '         <option>V</option>'."\n";
      echo '         <option>W</option>'."\n";
      echo '         <option>X</option>'."\n";
      echo '         <option>Y</option>'."\n";
      echo '         <option>Z</option>'."\n";
      echo '       </select></td>'."\n";
      echo '     <td width="300">'."\n";
      echo '       <input type="radio" name="dstarLinkRefAtStartup" value="ON"';  if ($config_radio['dstar']['dstarLinkRefAtStartup'] == '1') {echo ' checked="checked"';}; echo ' />Startup'."\n";
      echo '       <input type="radio" name="dstarLinkRefAtStartup" value="OFF"'; if ($config_radio['dstar']['dstarLinkRefAtStartup'] == '0') {echo ' checked="checked"';}; echo ' />Manual</td>'."\n";
      echo '     </tr>'."\n";
      echo '     <tr>'."\n";
      echo '       <td align="right"><a class="tooltip2" href="#">'.$lang['dstar_irc_lang'].':<span><b>Language</b>Set your prefered<br /> language here</span></a></td>'."\n";
      echo '       <td colspan="2" style="text-align: left;"><select name="dstarLanguage">'."\n";
      $testIrcLanguage = $config_radio['dstar']['dstarLanguage'];
      if (is_readable("/var/www/dashboard/private/pi-star/ircddbgateway_languages.inc")) {
        $ircLanguageFile = fopen("/var/www/dashboard/private/pi-star/ircddbgateway_languages.inc", "r");
        while (!feof($ircLanguageFile)) {
          $ircLanguageFileLine = fgets($ircLanguageFile);
          $ircLanguage = preg_split('/;/', $ircLanguageFileLine);
          if ((strpos($ircLanguage[0], '#') === FALSE ) && ($ircLanguage[0] != '')) {
            $ircLanguage[2] = rtrim($ircLanguage[2]);
            if ($testIrcLanguage == $ircLanguage[1]) { echo '         <option value="'.$ircLanguage[1].','.$ircLanguage[2].'" selected="selected">'.htmlspecialchars($ircLanguage[0]).'</option>'."\n"; }
            else { echo '         <option value="'.$ircLanguage[1].','.$ircLanguage[2].'">'.htmlspecialchars($ircLanguage[0]).'</option>'."\n"; }
          }
        }
        fclose($ircLanguageFile);
      }
      echo '       </select></td>'."\n";
      echo '     </tr>'."\n";
      echo '     <tr>'."\n";
      echo '       <td align="right"><a class="tooltip2" href="#">'.$lang['dstar_irc_time'].':<span><b>Time Announce</b>Announce time<br />hourly</span></a></td>'."\n";
      if ( $config_radio['dstar']['dstarTimeAnnounce'] == "1" ) {
        echo '       <td align="left" colspan="2"><div class="switch"><input id="toggle-timeAnnounce" class="toggle toggle-round-flat" type="checkbox" name="dstarTimeAnnounce" value="ON" checked="checked" /><label for="toggle-timeAnnounce"></label></div></td>'."\n";
      } else {
        echo '       <td align="left" colspan="2"><div class="switch"><input id="toggle-timeAnnounce" class="toggle toggle-round-flat" type="checkbox" name="dstarTimeAnnounce" value="ON" /><label for="toggle-timeAnnounce"></label></div></td>'."\n";
      }
      echo '     </tr>'."\n";
      echo '     <tr>'."\n";
      echo '       <td align="right"><a class="tooltip2" href="#">Use DPlus for XRF:<span><b>No DExtra</b>Should host files<br />use DPlus Protocol for XRFs</span></a></td>'."\n";
      if ( $config_radio['dstar']['dstarUseDPlusForXRF'] == "1" ) {
        echo '       <td align="left"><div class="switch"><input id="toggle-dplusHostFiles" class="toggle toggle-round-flat" type="checkbox" name="dstarUseDPlusForXRF" value="ON" checked="checked" /><label for="toggle-dplusHostFiles"></label></div></td>'."\n";
      }	else {
        echo '       <td align="left"><div class="switch"><input id="toggle-dplusHostFiles" class="toggle toggle-round-flat" type="checkbox" name="dstarUseDPlusForXRF" value="ON" /><label for="toggle-dplusHostFiles"></label></div></td>'."\n";
      }
      echo '       <td>Note: Update Required if changed</td>'."\n";
      echo '     </tr>'."\n";
      tableEnd($lang['apply']);

      // DMR Settings
      $dmrMasterFile = fopen("/usr/local/etc/MMDVMHost/DMR_Hosts.txt", "r");
      echo '   <input type="hidden" name="dmrEmbeddedLCOnly" value="OFF" />'."\n";
      echo '   <input type="hidden" name="dmrDumpTAData" value="OFF" />'."\n";
      echo '   <input type="hidden" name="dmrGwMaster1Enable" value="OFF" />'."\n";
      echo '   <input type="hidden" name="dmrGwMaster2Enable" value="OFF" />'."\n";
      echo '   <input type="hidden" name="dmrGwMaster3Enable" value="OFF" />'."\n";
      echo '   <input type="hidden" name="dmrGwXLXMasterEnable" value="OFF" />'."\n";
      sectionStart($lang['dmr_config']);
      tableStart($lang['setting'], $lang['value']);
      echo '     <tr>'."\n";
      echo '       <td align="right"><a class="tooltip2" href="#">'.$lang['dmr_master'].' (MMDVMHost):<span><b>DMR Master (MMDVMHost)</b>Set your prefered DMR<br /> master here</span></a></td>'."\n";
      echo '       <td style="text-align: left;"><select name="dmrMasterHost">'."\n";
      $testMMDVMdmrMaster = $config_radio['dmr']['dmrMMDVMHostMaster'];
      while (!feof($dmrMasterFile)) {
        $dmrMasterLine = fgets($dmrMasterFile);
        $dmrMasterHost = preg_split('/\s+/', $dmrMasterLine);
        if ((strpos($dmrMasterHost[0], '#') === FALSE ) && (substr($dmrMasterHost[0], 0, 3) != 'XLX') && ($dmrMasterHost[0] != '')) {
          if ($testMMDVMdmrMaster == $dmrMasterHost[2]) { echo '          <option value="'.$dmrMasterHost[2].','.$dmrMasterHost[3].','.$dmrMasterHost[4].','.$dmrMasterHost[0].'" selected="selected">'.$dmrMasterHost[0].'</option>'."\n"; $dmrMasterNow = $dmrMasterHost[0]; }
          else { echo '          <option value="'.$dmrMasterHost[2].','.$dmrMasterHost[3].','.$dmrMasterHost[4].','.$dmrMasterHost[0].'">'.$dmrMasterHost[0].'</option>'."\n"; }
        }
      }
      fclose($dmrMasterFile);
      echo '       </select></td>'."\n";
      echo '     </tr>'."\n";



      tableEnd($lang['apply']);

      // YSF Settings
      sectionStart($lang['ysf_config']);
      tableStart($lang['setting'], $lang['value']);
      tableEnd($lang['apply']);

      // P25 Settings
      sectionStart($lang['p25_config']);
      tableStart($lang['setting'], $lang['value']);
      tableEnd($lang['apply']);

      // NXDN Settings
      sectionStart($lang['nxdn_config']);
      tableStart($lang['setting'], $lang['value']);
      tableEnd($lang['apply']);

      // Firewall Settings
      sectionStart($lang['fw_config']);
      tableStart($lang['setting'], $lang['value']);
      tableEnd($lang['apply']);

      // Passwords
      sectionStart($lang['remote_access_pw']);
      tableStart($lang['setting'], $lang['value']);
      tableEnd($lang['apply']);

    }
    echo '    </form>'."\n";
    echo '  </div><!-- End the wide output -->'."\n";
  } // End of the HTML output

} else { // Script not called correctly...
  echo "Somthing very bad happend....";
  die();
}
?>
  <script type="text/javascript">
    function submitform() {
      document.getElementById("config").submit();
    }
    function submitPassform() {
      document.getElementById("adminPassForm").submit();
    }
    function factoryReset() {
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
    function toggleField(hideObj,showObj) {
      hideObj.disabled=true;
      hideObj.style.display='none';
      showObj.disabled=false;
      showObj.style.display='inline';
      showObj.focus();
    }
  </script>
