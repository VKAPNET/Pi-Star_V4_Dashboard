<?php // Includes & Logic here

// Page output
echo "  <div class=\"content\">\n";
?>
    <b>Gateway Activity</b>
    <table class="table" id="gatewayActivity">
      <thead><tr><th>Time</th><th>Mode</th><th>Callsign</th><th>Target</th><th>Src</th><th>Dur(s)</th><th>Loss</th><th>BER</th></tr></thead>
      <tbody></tbody>
    </table>
    <br />

    <b>Local RF Activity</b>
    <table class="table" id="localRFactivity">
      <thead><tr><th>Time</th><th>Mode</th><th>Callsign</th><th>Target</th><th>Src</th><th>Dur(s)</th><th>BER</th><th>RSSI</th></tr></thead>
      <tbody></tbody>
    </table>
    <br />

    <b>debug mmdvm</b>
    <table class="table" id="MMDVM">
      <thead><tr><th>Log Line</th></tr></thead>
      <tbody></tbody>
    </table>
    <br />

    <b>debug links</b>
    <table class="table" id="Links">
      <thead><tr><th>Log Line</th></tr></thead>
      <tbody></tbody>
    </table>
    <br />

    <b>debug status</b>
    <table class="table" id="Status">
      <thead><tr><th>Log Line</th></tr></thead>
      <tbody></tbody>
    </table>


    <script>
      // Get the timezone from the Client
      var tzName = Date().toString().match(/\(([A-Za-z\s].*)\)/)[1];
      // Get the offset in mins
      var tzOffset = -(new Date().getTimezoneOffset());
      // Add the timezone to the table headder
      $('#gatewayActivity thead tr:first th:eq(0)').append(" ("+tzName+")");
      $('#localRFactivity thead tr:first th:eq(0)').append(" ("+tzName+")");

      var nrLogLines = "25";
      socket.on("MODEM", function(data) {
	// 1970-01-01 00:00:00.000 D-Star, received network header from MW0MWZ  /1234 to CQCQCQ   via REF000 Z
	// 1970-01-01 00:00:00.000 D-Star, received network end of transmission, 123.4 seconds, 0% packet loss, BER: 0.0%
	// 1970-01-01 00:00:00.000 D-Star, network watchdog has expired, 123.4 seconds,  99% packet loss, BER: 0.0%
	// 1970-01-01 00:00:00.000 D-Star, received RF header from MW0MWZ  /1234 to CQCQCQ  
	// 1970-01-01 00:00:00.000 D-Star, received RF end of transmission, 123.4 seconds, BER: 0.0%, RSSI: -126/-65/-95 dBm
	// 1970-01-01 00:00:00.000 DMR Slot 2, received network voice header from MW0MWZ to TG 9
	// 1970-01-01 00:00:00.000 DMR Slot 2, received network end of voice transmission, 123.4 seconds, 0% packet loss, BER: 0.0%
	// 1970-01-01 00:00:00.000 DMR Slot 2, received RF voice header from MW0MWZ to 12345
	// 1970-01-01 00:00:00.000 DMR Slot 2, received RF end of voice transmission, 123.4 seconds, BER: 0.0%, RSSI: -126/-65/-95 dBm
	// 1970-01-01 00:00:00.000 YSF, received network data from MW0MWZ-RPT to  ALL       at MW0MWZ    
	// 1970-01-01 00:00:00.000 YSF, received network end of transmission, 123.4 seconds, 0% packet loss, BER: 0.0%
	// 1970-01-01 00:00:00.000 YSF, received RF header from MW0MWZ     to ALL       
	// 1970-01-01 00:00:00.000 YSF, received RF end of transmission, 123.4 seconds
	// 1970-01-01 00:00:00.000 P25, received network transmission from MW0MWZ to TG 10200
	// 1970-01-01 00:00:00.000 P25, network end of transmission, 123.4 seconds, 0% packet loss
	// 1970-01-01 00:00:00.000 P25, received RF voice transmission from MW0MWZ to TG 10200
	// 1970-01-01 00:00:00.000 P25, received RF end of voice transmission, 123.4 seconds, BER: 0.1%, RSSI: -126/-65/-95 dBm
	// 1970-01-01 00:00:00.000 NXDN, received network transmission from MW0MWZ to TG 65000
	// 1970-01-01 00:00:00.000 NXDN, received network end of transmission, 123.4 seconds, 0% packet loss, BER: 0.0%
	// 1970-01-01 00:00:00.000 NXDN, received RF voice transmission from MW0MWZ to TG 65000
	// 1970-01-01 00:00:00.000 NXDN, received RF end of voice transmission, 123.4 seconds, BER: 0.1%, RSSI: -126/-65/-95 dBm

	// Is this the Start or End of a transmission?
	if (data.search("seconds") > 0) { logType = "End"; } else { logType = "Start"; }
	// RF or Network?
	if (data.search("network") > 0) { tblSrc = "Net"; } else { tblSrc = "RF"; }

	dataSplitOnSpace = data.split(" ");
	dateStampUTC = dataSplitOnSpace[0];
	timeStampUTC = dataSplitOnSpace[1].slice(0, -4);
	//tblTimeUTC = dateStampUTC + " " + timeStampUTC;
	tblTimeUTC = new Date(dateStampUTC.split("-")[0], dateStampUTC.split("-")[1] -1, dateStampUTC.split("-")[2], timeStampUTC.split(":")[0], timeStampUTC.split(":")[1], timeStampUTC.split(":")[2], 0).getTime();
	tblTimeRaw = new Date(tblTimeUTC + (tzOffset * 60000));
	//tblTime = tblTimeRaw.getFullYear()+"-"+(tblTimeRaw.getMonth() +1)+"-"+tblTimeRaw.getDate()+" "+tblTimeRaw.getHours()+":"+tblTimeRaw.getMinutes()+":"+tblTimeRaw.getSeconds();
        tblTime = tblTimeRaw.toLocaleString();

	if (dataSplitOnSpace[2] == "DMR") { tblMode = dataSplitOnSpace[2]+" "+dataSplitOnSpace[3]+" "+dataSplitOnSpace[4].slice(0, -1); }
	else { tblMode = dataSplitOnSpace[2].slice(0, -1); }

	// Set some basics to fall back to.
	tblFrom = "";
	tblTo	= "";
	tblDur	= "";
	tblLoss	= "";
	tblBER	= "";
	tblRSSI	= "";

	if (logType == "Start") {
	  fromPos = data.lastIndexOf("from");
	  tblFrom = data.slice(fromPos).split(" ")[1];
          tblFrom = "<a href=\"https://www.qrz.com/db/"+tblFrom+"\">"+tblFrom+"</a>"
	  if (tblMode == "D-Star") {
	    dstarTxt = data.slice(fromPos).split("/")[1].slice(0, 4);
	    if (dstarTxt !== "    ") { tblFrom = tblFrom+"/"+dstarTxt; }
	  }
	  toPos = data.lastIndexOf("to");
          if (data.slice(toPos).split(" ")[1] == "TG") { tblTo = data.slice(toPos).split(" ")[1]+" "+data.slice(toPos).split(" ")[2]; } else { tblTo = data.slice(toPos).split(" ")[1]; }
          tblDur = "TX";

	  // Output to browser
	  $('#gatewayActivity tbody').prepend("<tr><td>"+tblTime+"</td><td>"+tblMode+"</td><td>"+tblFrom+"</td><td>"+tblTo+"</td><td>"+tblSrc+"</td><td>"+tblDur+"</td><td>"+tblLoss+"</td><td>"+tblBER+"</td></tr>");
          $('#gatewayActivity tbody tr:first td:eq(5)').css({"background-color":"#f33"});
	  $('#gatewayActivity tbody tr').slice(nrLogLines).remove();
	  if (tblSrc == "RF") {
    	    $('#localRFactivity tbody').prepend("<tr><td>"+tblTime+"</td><td>"+tblMode+"</td><td>"+tblFrom+"</td><td>"+tblTo+"</td><td>"+tblSrc+"</td><td>"+tblDur+"</td><td>"+tblBER+"</td><td>"+tblRSSI+"</td></tr>")
	    $('#localRFactivity tbody tr').slice(nrLogLines).remove();
            $('#localRFactivity tbody tr:first td:eq(5)').css({"background-color":"#f33"});
	  }
	}

	if (logType == "End") {
	  tblDur = data.split(",")[2].slice(0, -7);
	  $('#gatewayActivity tbody tr:first td:eq(5)').html(tblDur);
	  if (tblSrc == "RF") {
	    tblBER = data.split(",")[3].slice(1, 5);
	    tblLoss = "0%";
	  } else {
	    if (tblMode !== "P25") { tblBER = data.split(",")[4].slice(5); } else { tblBER = "0.0%"; }
	    tblLoss = data.split(",")[3].slice(0, -11);
	  }
	  $('#gatewayActivity tbody tr:first td:eq(6)').html(tblLoss);
	  $('#gatewayActivity tbody tr:first td:eq(7)').html(tblBER);
          $('#gatewayActivity tbody tr:first td:eq(5)').css({"background-color":""});
	  if (tblSrc == "RF") {
	    $('#localRFactivity tbody tr:first td:eq(5)').html(tblDur);
	    $('#localRFactivity tbody tr:first td:eq(7)').html(tblBER);
            $('#localRFactivity tbody tr:first td:eq(5)').css({"background-color":""});
	  }
	}
      })

      socket.on("MODEM", function(data) {
        $('#MMDVM tbody').prepend("<tr><td align=\"left\">" + data + "</td></tr>")
        $('#MMDVM tbody tr').slice(5).remove();
        // $(document.getElementById("MMDVM")).append("<tr><td>" + data + "</td></tr>")
      })
      socket.on("Links", function(data) {
	$('#Links tbody tr').slice(0).remove();
        $('#Links tbody').prepend("<tr><td align=\"left\">" + data + "</td></tr>")
        // $(document.getElementById("Links")).append("<tr><td>" + data + "</td></tr>")
      })
      socket.on("SERVICE_STATUS", function(data) {
	$('#Status tbody tr').slice(0).remove();
        $('#Status tbody').prepend("<tr><td align=\"left\">" + data + "</td></tr>")
        // $(document.getElementById("Links")).append("<tr><td>" + data + "</td></tr>")
      })
    </script>

<?php
// Page output
echo "  </div><!-- end content -->\n";
?>
