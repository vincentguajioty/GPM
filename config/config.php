<?php

require_once 'bdd.php';

$VERSIONCHECK = '8.4';

$query = $db->query('SELECT * FROM CONFIG;');
$data = $query->fetch();

$SITECOLOR = $data['sitecolor'];
$APPNAME = $data['appname'];
$URLSITE = $data['urlsite'];
$VERSION = $data['version'];
$MAILSERVER = $data['mailserver'];
$MAILCOPY = $data['mailcopy'];
$LOGOUTTEMP = $data['logouttemp'];
$MAINTENANCE = $data['maintenance'];
$CONFSUPPRESSION = $data['confirmationSuppression'];

?>