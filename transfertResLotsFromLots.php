<?php
session_start();
require_once('logCheck.php');
?>
<?php
require_once 'config/bdd.php';
require_once 'config/config.php';

if ($_SESSION['reserve_ReserveVersLot']==0)
{
    echo "<script type='text/javascript'>document.location.replace('loginHabilitation.php');</script>";
}
else
{
    
	
	$_SESSION['transfertIdMaterielCatalogue'] = $_GET['idMaterielCatalogue'];
	$_SESSION['transfertIdMaterielLot']       = $_GET['idElement'];
	$_SESSION['transfertStade']               = 2;
	
    echo "<script type='text/javascript'>document.location.replace('transfertResLots.php');</script>";


}
?>