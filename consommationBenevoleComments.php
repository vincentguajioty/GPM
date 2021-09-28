<?php
session_start();
require_once 'config/bdd.php';
require_once 'config/config.php';
require_once 'verrouIPcheck.php';

if (checkIP($_SERVER['REMOTE_ADDR'])==1)
{
	echo "<script type='text/javascript'>document.location.replace('logout.php');</script>";
	exit;
}

if ($MAINTENANCE)
{
	echo "<script type='text/javascript'>document.location.replace('logout.php');</script>";
	exit;
}

if ($CONSOMMATION_BENEVOLES==0)
{
    echo "<script type='text/javascript'>document.location.replace('logout.php');</script>";
}
else
{
	
	$_POST['commentairesConsommation']    = str_replace($XSS_SECURITY, "", $_POST['commentairesConsommation']);
	
    $_SESSION['commentairesConsommation'] = $_POST['commentairesConsommation'];
    
    echo "<script type='text/javascript'>document.location.replace('consommationBenevole.php');</script>";
    
}
?>