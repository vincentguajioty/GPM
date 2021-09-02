<?php
session_start();
require_once('logCheck.php');
?>
<?php
require_once 'config/bdd.php';

if (centreCoutsEstCharge($_SESSION['idPersonne'],$_GET['idCentreDeCout'])==0)
{
    echo "<script type='text/javascript'>document.location.replace('loginHabilitation.php');</script>";
}
else
{
    $query = $db->prepare('UPDATE COMMANDES SET integreCentreCouts = 1 WHERE idCommande = :idCommande;');
    $query->execute(array('idCommande' => $_GET['idCommande']));

    switch($query->errorCode())
    {
        case '00000':
            writeInLogs("Rejet de la commande ".$_GET['idCommande']." dans le centre de couts " . $_GET['idCentreDeCout'], '1', NULL);
            $_SESSION['returnMessage'] = 'Commande rejetée avec succès.';
            $_SESSION['returnType'] = '1';

            break;

        default:
            writeInLogs("Erreur inconnue lors du rejet de la commande ".$_GET['idCommande']." dans le centre de couts " . $_GET['idCentreDeCout'], '3', NULL);
            $_SESSION['returnMessage'] = "Erreur inconnue lors du rejet de la commande.";
            $_SESSION['returnType'] = '2';
    }

    echo "<script>window.location = document.referrer;</script>";
  
}
?>