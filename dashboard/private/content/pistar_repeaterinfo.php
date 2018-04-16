  <div class="nav">
    <table class="table" id="modesEnabled">
      <thead><tr><th colspan="2"><?php echo $lang['modes_enabled'];?></th></tr></thead>
      <tbody>
      <tr><td style="background:#606060; color:#b0b0b0; width:50%;">D-Star</td><td style="background:#606060; color:#b0b0b0;">DMR</td></tr>
      <tr><td style="background:#606060; color:#b0b0b0;">YSF</td><td style="background:#606060; color:#b0b0b0;">P25</td></tr>
      <tr><td style="background:#606060; color:#b0b0b0;">YSF2DMR</td><td style="background:#606060; color:#b0b0b0;">NXDN</td></tr>
      </tbody>
    </table>
    <br />
    <table class="table" id="networksEnabled">
      <thead><tr><th colspan="2"><?php echo $lang['net_status'];?></th></tr></thead>
      <tbody>
      <tr><td style="background:#606060; color:#b0b0b0; width:50%;">D-Star Net</td><td style="background:#606060; color:#b0b0b0;">DMR Net</td></tr>
      <tr><td style="background:#606060; color:#b0b0b0;">YSF Net</td><td style="background:#606060; color:#b0b0b0;">P25 Net</td></tr>
      <tr><td style="background:#606060; color:#b0b0b0;">YSF2DMR Net</td><td style="background:#606060; color:#b0b0b0;">NXDN Net</td></tr>
      </tbody>
    </table>
    <br />
    <table class="table" id="trxInfo">
    <thead><tr><th colspan="2"><?php echo $lang['radio_info'];?></th></tr></thead>
    <tbody>
      <tr><th width="40px">Trx</th><td>Waiting...</td></tr>
      <tr><th width="40px">Tx</th><td style="background: #ffffff;"><?php echo getMHz($config_mmdvmhost['Info']['TXFrequency']);?></td></tr>
      <tr><th width="40px">Rx</th><td style="background: #ffffff;"><?php echo getMHz($config_mmdvmhost['Info']['RXFrequency']);?></td></tr>
      <tr><th width="40px">FW</th><td style="background: #ffffff;">Waiting...</td></tr>
    </tbody>
    </table>
    <br />

<?php if ($config_radio['modes']['modeDStarEnable']) {?>
    <table class="table" id="dstarInfo">
    <thead><tr><th colspan="2"><?php echo $lang['dstar_repeater'];?></th></tr></thead>
    <tbody>
      <tr><th width="40px">RPT1</th><td style="background: #ffffff;"><?php echo $config_ircddbgateway['gatewayCallsign']."&nbsp;".$config_ircddbgateway['repeaterBand1']; ?></td></tr>
      <tr><th width="40px">RPT2</th><td style="background: #ffffff;"><?php echo $config_ircddbgateway['gatewayCallsign']."&nbsp;G"; ?></td></tr>
      <tr><th colspan="2"><?php echo $lang['dstar_net'];?></th></tr>
      <tr><th width="40px">IRC</th><td style="background: #ffffff;"><?php echo $config_ircddbgateway['aprsHostname']; ?></td></tr>
      <tr><td colspan="2" style="background: #ffffff;">Waiting...</td></tr>
    </tbody>
    </table>
    <br />
<?php } ?>

<?php if ($config_radio['modes']['modeDmrEnable']) {?>
    <table>
    <thead><tr><th colspan="2"><?php echo $lang['dmr_repeater'];?></th></tr></thead>
    <tbody>
      <tr><th width="55px">DMR ID</th><td style="background: #ffffff;"><?php echo $config_mmdvmhost['General']['Id'];?></td></tr>
      <tr><th width="55px">DMR CC</th><td style="background: #ffffff;"><?php echo $config_mmdvmhost['DMR']['ColorCode'];?></td></tr>
      <tr><th width="55px">Slot 1</th><?php if ($config_mmdvmhost['DMR Network']['Slot1']) { echo '<td style="background: #0b0;">Enabled'; } else { echo '<td style="background:#606060; color:#b0b0b0">Disabled'; };?></td></tr>
      <tr><th width="55px">Slot 2</th><?php if ($config_mmdvmhost['DMR Network']['Slot2']) { echo '<td style="background: #0b0;">Enabled'; } else { echo '<td style="background:#606060; color:#b0b0b0">Disabled'; };?></td></tr>
      <tr><th colspan="2"><?php echo $lang['dmr_master'];?></th></tr>
      <tr><td colspan="2" style="background: #ffffff;">Waiting...</td></tr>
    </tbody>
    </table>
    <br />
<?php } ?>

<?php if ($config_radio['modes']['modeYSFEnable']) {?>
    <table>
    <thead><tr><th colspan="2"><?php echo $lang['ysf_net'];?></th></tr></thead>
    <tbody>
    <tr><td colspan="2" style="background: #ffffff;">Waiting...</td></tr>
    </tbody>
    </table>
    <br />
<?php } ?>

<?php if ($config_radio['modes']['modeP25Enable']) {?>
    <table>
    <thead><tr><th colspan="2"><?php echo $lang['p25_radio'];?></th></tr></thead>
    <tbody>
    <tr><th width="55px">P25 ID</th><td style="background: #ffffff;"><?php echo $config_mmdvmhost['General']['Id'];?></td></tr>
    <tr><th width="55px">NAC</th><td style="background: #ffffff;"><?php echo $config_mmdvmhost['P25']['NAC'];?></td></tr>
    <tr><th colspan="2"><?php echo $lang['p25_net'];?></th></tr>
    <tr><td colspan="2" style="background: #ffffff;">Waiting...</td></tr>
    </tbody>
    </table>
    <br />
<?php } ?>

<?php if ($config_radio['modes']['modeNXDNEnable']) {?>
    <table>
    <thead><tr><th colspan="2"><?php echo $lang['nxdn_radio'];?></th></tr></thead>
    <tbody>
    <tr><th width="55px">NXDN ID</th><td style="background: #ffffff;"><?php echo $config_radio['identity']['nxdnId'];?></td></tr>
    <tr><th width="55px">RAN</th><td style="background: #ffffff;"><?php echo $config_radio['nxdn']['nxdnRAN'];?></td></tr>
    <tr><th colspan="2"><?php echo $lang['nxdn_net'];?></th></tr>
    <tr><td colspan="2" style="background: #ffffff;">Waiting...</td></tr>
    </tbody>
    </table>
    <br />
<?php } ?>

    <script>
      // Modes Status
      socket.on("SERVICE_STATUS", function(data) {
        // Modes Enabled
        if (data.includes("mmdvmhost:on") && <?php echo $config_radio['modes']['modeDStarEnable']; ?>)					{ $('#modesEnabled tbody tr:eq(0) td:eq(0)').css({"background-color":"#0b0", "color":"#000"}); }
	if (data.includes("dstarrepeater:on") && <?php echo $config_radio['software']['modemControlSoftware'] == 'dstarrepeater'; ?>)	{ $('#modesEnabled tbody tr:eq(0) td:eq(0)').css({"background-color":"#0b0", "color":"#000"}); }
        if (data.includes("mmdvmhost:on") && <?php echo $config_radio['modes']['modeDmrEnable']; ?>)					{ $('#modesEnabled tbody tr:eq(0) td:eq(1)').css({"background-color":"#0b0", "color":"#000"}); }
        if (data.includes("mmdvmhost:on") && <?php echo $config_radio['modes']['modeYSFEnable']; ?>)					{ $('#modesEnabled tbody tr:eq(1) td:eq(0)').css({"background-color":"#0b0", "color":"#000"}); }
	if (data.includes("mmdvmhost:on") && <?php echo $config_radio['modes']['modeP25Enable']; ?>)					{ $('#modesEnabled tbody tr:eq(1) td:eq(1)').css({"background-color":"#0b0", "color":"#000"}); }
	if (data.includes("mmdvmhost:on") && <?php echo $config_radio['modes']['modeYSF2DMREnable']; ?>)				{ $('#modesEnabled tbody tr:eq(2) td:eq(0)').css({"background-color":"#0b0", "color":"#000"}); }
	if (data.includes("mmdvmhost:on") && <?php echo $config_radio['modes']['modeNXDNEnable']; ?>)					{ $('#modesEnabled tbody tr:eq(2) td:eq(1)').css({"background-color":"#0b0", "color":"#000"}); }
	// Network Status
	if (data.includes("ircddbgateway:on") && <?php echo $config_radio['modes']['modeDStarEnable']; ?>)				{ $('#networksEnabled tbody tr:eq(0) td:eq(0)').css({"background-color":"#0b0", "color":"#000"}); }
	if (data.includes("ircddbgateway:on") && <?php echo $config_radio['software']['modemControlSoftware'] == 'dstarrepeater'; ?>)	{ $('#networksEnabled tbody tr:eq(0) td:eq(0)').css({"background-color":"#0b0", "color":"#000"}); }
        if (data.includes("mmdvmhost:on") && <?php echo $config_radio['modes']['modeDmrEnable']; ?>)					{ $('#networksEnabled tbody tr:eq(0) td:eq(1)').css({"background-color":"#0b0", "color":"#000"}); }
        if (data.includes("ysfgateway:on") && <?php echo $config_radio['modes']['modeYSFEnable']; ?>)					{ $('#networksEnabled tbody tr:eq(1) td:eq(0)').css({"background-color":"#0b0", "color":"#000"}); }
	if (data.includes("p25gateway:on") && <?php echo $config_radio['modes']['modeP25Enable']; ?>)					{ $('#networksEnabled tbody tr:eq(1) td:eq(1)').css({"background-color":"#0b0", "color":"#000"}); }
	if (data.includes("ysf2dmr:on") && <?php echo $config_radio['modes']['modeYSF2DMREnable']; ?>)				{ $('#networksEnabled tbody tr:eq(2) td:eq(0)').css({"background-color":"#0b0", "color":"#000"}); }
	if (data.includes("nxdngateway:on") && <?php echo $config_radio['modes']['modeNXDNEnable']; ?>)
      });


      // Modem Status
      socket.on("MODEM", function(data) {
        // Mode
        trxMode = data.split(" ")[2].slice(0, -1);
        if (data.split(" ")[2] == "DMR") { trxMode = data.split(" ")[2]+" TS"+data.split(" ")[4].slice(0, -1); }

        // Status
        if (data.search("network") > 0) { dataSrc = "Net"; } else { dataSrc = "RF"; }
        if (data.search("seconds") > 0) { modemStatus = "Waiting"; } else { modemStatus = "Active"; }

        // Set Output Format
        if (dataSrc == "RF" && modemStatus == "Active") { trxStatus = "RX "+trxMode; }
        if (dataSrc == "Net" && modemStatus == "Active") { trxStatus = "TX "+trxMode; }
        if (modemStatus == "Waiting") { trxStatus = "Listening"; }

        // Output Text / Formatting
        $('#trxInfo tbody tr:eq(0) td:eq(0)').html(trxStatus);
	if (trxStatus.includes("Listening")) { $('#trxInfo tbody tr:eq(0) td:eq(0)').css({"background-color":"#0b0"}); }
        if (trxStatus.includes("TX")) { $('#trxInfo tbody tr:eq(0) td:eq(0)').css({"background-color":"#f33"}); }
        if (trxStatus.includes("RX") > 0) {
          if (trxStatus.includes("D-STAR")) { $('#trxInfo tbody tr:eq(0) td:eq(0)').css({"background-color":"#ade"}); }
          if (trxStatus.includes("DMR")) { $('#trxInfo tbody tr:eq(0) td:eq(0)').css({"background-color":"#f93"}); }
          if (trxStatus.includes("YSF")) { $('#trxInfo tbody tr:eq(0) td:eq(0)').css({"background-color":"#ff9"}); }
          if (trxStatus.includes("P25")) { $('#trxInfo tbody tr:eq(0) td:eq(0)').css({"background-color":"#f9f"}); }
          if (trxStatus.includes("NXDN")) { $('#trxInfo tbody tr:eq(0) td:eq(0)').css({"background-color":"#c9f"}); }
        }
      });

      // Modem Firmware
      socket.on("MODEM_FW", function(data) {
        // 1970-01-01 00:00:00.000 MMDVM protocol version: 1, description: MMDVM 20170501 TCXO (D-Star/DMR/System Fusion/P25/RSSI/CW Id) (Build: 14:32:58 Aug 25 2017)
        // 1970-01-01 00:00:00.000 MMDVM protocol version: 1, description: ZUMspot-v1.3.3 20180224 ADF7021 FW by CA6JAU GitID #62323e7
        // 1970-01-01 00:00:00.000 MMDVM protocol version: 1, description: DVMEGA HR3.19
        if (data.includes("CA6JAU")) { modemFw = data.split(":")[4].split(" ")[1]; }
        else { modemFw = data.split(":")[4].split(" ")[1]+" "+data.split(":")[4].split(" ")[2]; }

        if(modemFw.length >= 15) { modemFw = modemFw.slice(0, 15); }
        $('#trxInfo tbody tr:eq(3) td:eq(0)').html(modemFw);
      });

      // D-Star Reflector Information
      socket.on("Links", function(data) {
        // 1970-01-01 00:00:00: DCS link - Type: Repeater Rptr: M1ABC  B Refl: DCS001 A Dir: Outgoing
        // Not Linked
        if (data.includes("-")) {
          linkProto = data.split(" ")[2];
          linkType  = data.split(" ")[6];
          linkSrc   = data.split(" ")[8]+" "+data.split(" ")[9];
          linkDst   = data.split(" ")[11]+" "+data.split(" ")[12];
          linkDir   = data.split(" ")[14].slice(0, -1);
          linkStatus = "Linked to: "+linkDst+"<br>\n"+linkProto+" ("+linkDir+")";
        } else {
          linkStatus = data;
        }

        $('#dstarInfo tbody tr:eq(4) td:eq(0)').html(linkStatus);
      });

     // Cause a page reload on config change
     socket.on("CONFIG", function(data) {
       if (data.includes("UPDATED")) { setTimeout(location.reload(true), 3000); }
     });

//enabled : background:#0b0; color:#030;
//disabled: background:#606060; color:#b0b0b0;


    </script>
  </div><!-- end nav -->

