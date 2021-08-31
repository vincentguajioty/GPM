<?php
session_start();
require_once('logCheck.php');
?>
<?php
require_once 'config/bdd.php';
require_once 'commandesCommentAdd.php';
require_once 'config/config.php';
require_once 'config/mailFunction.php';

if ($_SESSION['commande_etreEnCharge']==0)
{
    echo "<script type='text/javascript'>document.location.replace('loginHabilitation.php');</script>";
}
else
{
    $query = $db->query('SELECT * FROM CONFIG;');
    $config = $query -> fetch();

    $query = $db->prepare('UPDATE COMMANDES_MATERIEL SET quantiteAtransferer = quantiteCommande WHERE idCommande = :idCommande;');
    $query->execute(array(
        'idCommande' => $_GET['id']
    ));
    $query = $db->prepare('UPDATE COMMANDES SET idEtat = 5 WHERE idCommande = :idCommande;');
    $query->execute(array(
        'idCommande' => $_GET['id']
    ));

    switch($query->errorCode())
    {
        case '00000':
            writeInLogs("Modification de la commande " . $_GET['id'], '3');
            addCommandeComment($_GET['id'], $_SESSION['identifiant'] . " clôture le SAV. La commande n'a plus qu'à être clôturée.", "25");
            break;

        default:
            writeInLogs("Erreur inconnue lors de la modification de la commande.", '5');
            $_SESSION['returnMessage'] = "Erreur inconnue lors la modification de la commande.";
            $_SESSION['returnType'] = '2';

    }

    $sujet = "[" . $APPNAME . "] SAV de la commande " .$_GET['id']. " clos.";

    if($config['notifications_commandes_demandeur_savOK']==1)
    {
        $query = $db->prepare('SELECT * FROM COMMANDES c LEFT OUTER JOIN PERSONNE_REFERENTE p ON c.idDemandeur = p.idPersonne WHERE idCommande = :idCommande;');
        $query->execute(array(
            'idCommande' => $_GET['id']
        ));
        $data = $query->fetch();
        $message = "Bonjour " . $data['prenomPersonne'] . ", <br/><br/> Pour information, la commande " . $_GET['id'] . " dont vous êtes le demandeur vient de terminer son traitement SAV.";
        $message = $message . "<br/><br/>Cordialement<br/><br/>L'équipe administrative de " . $APPNAME;
        $message = $RETOURLIGNE.$message.$RETOURLIGNE;
        if(sendmail($data['mailPersonne'], $sujet, 2, $message))
        {
            writeInLogs("Mail d'information de clôture SAV envoyé au demandeur pour la commande " . $_GET['id'], '2');
        }
        else
        {
            writeInLogs("Erreur lors de l'envoi du mail d'information de clôture SAV au demandeur pour la commande " . $_GET['id'], '5');
        }
    }
    if($config['notifications_commandes_valideur_savOK']==1)
    {
        $query = $db->prepare('SELECT * FROM COMMANDES c LEFT OUTER JOIN PERSONNE_REFERENTE p ON c.idValideur = p.idPersonne WHERE idCommande = :idCommande;');
        $query->execute(array(
            'idCommande' => $_GET['id']
        ));
        $data = $query->fetch();
        $message = "Bonjour " . $data['prenomPersonne'] . ", <br/><br/> La commande " . $_GET['id'] . " dont vous êtes le valideur vient de terminer son traitement SAV.";
        $message = $message . "<br/><br/>Cordialement<br/><br/>L'équipe administrative de " . $APPNAME;
        $message = $RETOURLIGNE.$message.$RETOURLIGNE;
        if(sendmail($data['mailPersonne'], $sujet, 2, $message))
        {
            writeInLogs("Mail d'information de clôture SAV envoyé au valideur pour la commande " . $_GET['id'], '2');
        }
        else
        {
            writeInLogs("Erreur lors de l'envoi du mail d'information de clôture SAV au valideur pour la commande " . $_GET['id'], '5');
        }
    }
    if($config['notifications_commandes_affectee_savOK']==1)
    {
        $query = $db->prepare('SELECT * FROM COMMANDES c LEFT OUTER JOIN PERSONNE_REFERENTE p ON c.idAffectee = p.idPersonne WHERE idCommande = :idCommande;');
        $query->execute(array(
            'idCommande' => $_GET['id']
        ));
        $data = $query->fetch();
        $message = "Bonjour " . $data['prenomPersonne'] . ", <br/><br/> Pour information, la commande " . $_GET['id'] . " qui vous est affectée vient de terminer son traitement SAV.";
        $message = $message . "<br/><br/>Cordialement<br/><br/>L'équipe administrative de " . $APPNAME;
        $message = $RETOURLIGNE.$message.$RETOURLIGNE;
        if(sendmail($data['mailPersonne'], $sujet, 2, $message))
        {
            writeInLogs("Mail d'information de clôture SAV envoyé au gérant pour la commande " . $_GET['id'], '2');
        }
        else
        {
            writeInLogs("Erreur lors de l'envoi du mail de passage de clôture SAV au gérant pour la commande " . $_GET['id'], '5');
        }
    }
    if($config['notifications_commandes_observateur_savOK']==1)
    {
        $query = $db->prepare('SELECT * FROM COMMANDES c LEFT OUTER JOIN PERSONNE_REFERENTE p ON c.idObservateur = p.idPersonne WHERE idCommande = :idCommande;');
        $query->execute(array(
            'idCommande' => $_GET['id']
        ));
        $data = $query->fetch();
        $message = "Bonjour " . $data['prenomPersonne'] . ", <br/><br/> Pour information, la commande " . $_GET['id'] . " dont vous êtes l'observateur vient de terminer son traitement SAV.";
        $message = $message . "<br/><br/>Cordialement<br/><br/>L'équipe administrative de " . $APPNAME;
        $message = $RETOURLIGNE.$message.$RETOURLIGNE;
        if(sendmail($data['mailPersonne'], $sujet, 2, $message))
        {
            writeInLogs("Mail d'information de clôture SAV envoyé à l'observateur pour la commande " . $_GET['id'], '2');
        }
        else
        {
            writeInLogs("Erreur lors de l'envoi du mail de passage de clôture SAV à l'observateur pour la commande " . $_GET['id'], '5');
        }
    }

    echo "<script type='text/javascript'>document.location.replace('commandesToutes.php');</script>";

}
?>