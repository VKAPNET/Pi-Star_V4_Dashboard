<!DOCTYPE html>
<head>
    <meta name="robots" content="index" />
    <meta name="robots" content="follow" />
    <meta name="language" content="English" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <?php echo "<meta name=\"generator\" content=\"Pi-Star Dashboard $dashVersion\" />\n"; ?>
    <meta name="Author" content="Andy Taylor (MW0MWZ)" />
    <meta name="Description" content="Pi-Star Dashboard" />
    <meta name="KeyWords" content="Pi-Star,PiStar,MW0MWZ,MMDVMHost,ircDDBGateway,D-Star,ircDDB,DMR,DMRGateway,YSF2DMR,YSF,Fusion,SystemFusion,P25,NXDN" />
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="cache-control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="pragma" content="no-cache" />
    <title><?php echo $pageTitle;?></title>
    <link rel="stylesheet" type="text/css" href="/css/pi-star.css?version=1.0" />
    <link rel="stylesheet" type="text/css" href="/css/tabs-menu.css?version=1.0" />
    <script type="text/javascript" src="/java/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="/java/socket.io.js"></script>
    <script type="text/javascript">
      $.ajaxSetup({ cache: false });
<?php if ($_SERVER["PHP_SELF"] != "/admin/configure.php") { echo '      var socket = io(\'/socket.io\');'."\n"; } ?>
    </script>
</head>
<body>
<div class="container">
  <div class="header">
    <div style="font-size: 8px; text-align: left; padding-left: 8px; float: left;">Hostname: <?php echo preg_replace("/[^A-Za-z0-9- ]/", '', shell_exec('cat /etc/hostname')); ?></div><div style="font-size: 8px; text-align: right; padding-right: 8px;">Pi-Star: <?php echo $config_pistar_release['Pi-Star']['Version']?> / <?php echo $lang['dashboard'].": ".$dashVersion; ?></div>
    <h1><?php echo $pageTitle; ?></h1>
    <div class="menu">
<?php include_once "/var/www/dashboard/private/content/pistar_menu.php";?>
    </div><!-- end menu -->
  </div><!-- end headder -->
