/*
##############################################################################################
#                                                                                            #
#    Node.js implementation of Logfile Parser, written by Andy Taylor (MW0MWZ) for Pi-Star   #
#     as a replacement for the VERY resource hungry PHP implementation previously in use.    #
#     Thanks to Rudy (PD0ZRY) for the push to move over to Node.JS / Socket.io /  Redis.     #
#                                                                                            #
##############################################################################################
*/

// Set some variables
var logFolderRoot = "/var/log/pi-star/";
var logFileSuffix = "*.log";
var configFolderRoot = "/usr/local/etc/pi-star/";
var configFileSuffix = "*.ini";
var debugging = "off";

// Required modules
var Tail = require('tail').Tail;
var chokidar = require('chokidar');
var fs = require('fs');
var parser = require('parse-ini');

// Load Socket.io
var io = require('socket.io')(8080);
var nsp = io.of('/socket.io')

// Setup redis
var redis = require('redis');
var redisClient = redis.createClient({host : 'localhost', port : 6379});

// Set some options for Tail
var options= {separator: /[\r]{0,1}\n/, fromBeginning: false, fsWatchOptions: {}, follow: true, logger: console}
var files = [];

// Function to make log output simple
var log = console.log.bind(console);

// Initialise Log Folder Watcher
var logWatcher = chokidar.watch(logFolderRoot+logFileSuffix, {
  ignored: /(^|[\/\\])\../,
  persistent: true
});

// Initialise Config Folder Watcher
var configWatcher = chokidar.watch(configFolderRoot+configFileSuffix, {
  ignored: /(^|[\/\\])\../,
  persistent: true
});

// Initialise Links Log Watcher
var linksLogWatcher = chokidar.watch("/var/log/pi-star/Links.log", {
  ignored: /(^|[\/\\])\../,
  persistent: true
});

// Start redis
redisClient.on('ready',function() {
 log("Redis is ready");
});

redisClient.on('error',function() {
 log("Error in Redis");
});

// When a client connects, we note it in the console
nsp.on('connection', function (socket) {
  if (debugging == "on") { log('A client is connected'); }

  // Get the data from redis, and send it to the client
  redisClient.keys('MODEM:*', function (err, keys) {
    if (err) return log(err);
    for(var i = 0, len = keys.length; i < len; i++) {
      // Add Keys to Sortable List
      redisClient.sadd('TEMP_MODEM', keys[i]);
    }
  });

  // Sort the redis temp key store and push to client
  redisClient.sort('TEMP_MODEM', 'alpha', function (err, keys) {
    if (err) return log(err);
    if ( keys.length > 50 ) { minKey = keys.length - 50; } else { minKey = 0; }
    for(var i = minKey, len = keys.length; i < len; i++) {
      redisClient.get(keys[i], function(err, reply) {
        nsp.emit("MODEM", reply);
      });
    }
  });

  // Clear up the used keyspace
  redisClient.del('TEMP_MODEM');

  // Send the modem/frequency info to the client
  redisClient.get('MODEM_FW', function(err, reply) {
    nsp.emit("MODEM_FW", reply);
  });

  // Send the Link information for D-Star
  redisClient.get('DSTAR_LINK', function(err, reply) {
    nsp.emit("Links", reply);
  });
});

// Add event listeners.
logWatcher
.on('add', function(path) {
  log('File', path, 'has been added');
  tailLog = new Tail(path, options);
  tailLog.on("line", function(data) {

    // MMDVMHost Log
    if (path.indexOf('MMDVM-') >= 0) {
      if(data.indexOf('received network') >= 0 || data.indexOf('network watchdog has expired') >= 0 || data.indexOf('received RF') >= 0 || data.indexOf('network end of transmission') >= 0){
        if (debugging == "on") { log("    MODEM Log: "+data.slice(3)); }
        nsp.emit("MODEM", data.slice(3));
	// Store the data in redis
        redisClient.set('MODEM:'+Date.now(), data.slice(3), 'EX', 36000);
      }
      if(data.indexOf('I:') >= 0 && data.indexOf('MMDVM protocol version: 1') >= 0){
        if (debugging == "on") { log("     Modem FW: "+data.slice(3)); }
        nsp.emit("MODEM_FW", data.slice(3));
        redisClient.set('MODEM_FW', data.slice(3));
      }
    }


  });
  tailLog.on("error", function(error) {
    log('ERROR: ', error);
    tailLog.unwatch(path)
  });
})
.on('unlink', function(path) {
  if (debugging == "on") { log('File', path, 'has been removed'); }
  tailLog.unwatch(path);
});

// Watch for Links.log Changes
linksLogWatcher
.on('add', function(path) {
  if (debugging == "on") { log("    Links Log: "+data); }
  const { spawnSync } = require( 'child_process' ),
  logLineLinks = spawnSync( 'tail', [ '-n', '1', '/var/log/pi-star/Links.log' ] );
  logLineLinksOutput = logLineLinks.stdout.toString();
  if (logLineLinksOutput) {
    redisClient.set('DSTAR_LINK', logLineLinksOutput);
    nsp.emit("Links", logLineLinksOutput);
  } else {
    redisClient.set('DSTAR_LINK', "Not Linked");
    nsp.emit("Links", "Not Linked");
  }
})
.on('change', function(path) {
  if (debugging == "on") { log("    Links Log: "+data); }
  const { spawnSync } = require( 'child_process' ),
  logLineLinks = spawnSync( 'tail', [ '-n', '1', '/var/log/pi-star/Links.log' ] );
  logLineLinksOutput = logLineLinks.stdout.toString();
  if (logLineLinksOutput) {
    redisClient.set('DSTAR_LINK', logLineLinksOutput);
    nsp.emit("Links", logLineLinksOutput);
  } else {
    redisClient.set('DSTAR_LINK', "Not Linked");
    nsp.emit("Links", "Not Linked");
  }
})
.on('error', function(path) {
  if (debugging == "on") { log('File', path, 'has been removed'); }
  linksLogWatcher.unwatch(path);
});

// Add Event Watcher for Config Files
configWatcher
.on('change', function(path) {
  log('File', path, 'has been changed, alerting dashboards and restarting services');
  nsp.emit("CONFIG", "UPDATED");

  // The config changed - bounce the services.
  var configPiStar = parser.parse('/usr/local/etc/pi-star/pi-star.ini');
  
});

// Service Monitor
function serviceMonitor() {
  // Read the config on each pass
  var configPiStar = parser.parse('/usr/local/etc/pi-star/pi-star.ini');

  // MMDVMHost Check / Restart
  if (configPiStar.software.modemControlSoftware == "mmdvmhost") {
    svcChkMMDVMHostCmd = spawnSync( 'ps', [ '--no-headers', '-C', 'MMDVMHost' ] );
    svcChkMMDVMHost = svcChkMMDVMHostCmd.stdout.toString();
    if (!(svcChkMMDVMHost)) {
      // If the service should be running - start it
    }
  }
}

// Check the services every 30 secs
setInterval(serviceMonitor, 30000);
