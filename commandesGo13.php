<?php
session_start();
require_once('logCheck.php');
require_once 'config/bdd.php';
require_once 'commandesCommentAdd.php';
require_once 'config/config.php';
require_once 'config/mailFunction.php';

if ($_SESSION['commande_valider']==0)
{
    echo "<script type='text/javascript'>document.location.replace('loginHabilitation.php');</script>";
}
else
{
    $query = $db->query('SELECT * FROM CONFIG;');
    $config = $query -> fetch();

    if ($_POST['button'] == 'ok')
    {
        $query = $db->prepare('UPDATE COMMANDES SET idEtat = 3, remarquesValidation = :remarquesValidation, dateValidation = CURRENT_TIMESTAMP WHERE idCommande = :idCommande;');
        $query->execute(array(
            'idCommande' => $_GET['id'],
            'remarquesValidation' => $_POST['remarquesValidation']
        ));
        addCommandeComment($_GET['id'], $_SESSION['identifiant'] . " valide la commande avec le commentaire: " . $_POST['remarquesValidation'], "13");

        $sujet = "[" . $APPNAME . "] Validation positive de la commande " .$_GET['id'];

        if($config['notifications_commandes_demandeur_validationOK']==1)
        {
            $query = $db->query('SELECT * FROM PERSONNE_REFERENTE;');
            $data = $query->fetch();
            if(cmdEstDemandeur($data['idPersonne'],$_GET['id']))
            {
                $message = "Bonjour " . $data['prenomPersonne'] . ", <br/><br/> Pour information, la commande " . $_GET['id'] . " dont vous êtes le demandeur vient d'être acceptée.";
                $message = $message . "<br/><br/>Cordialement<br/><br/>L'équipe administrative de " . $APPNAME;
                $message = $RETOURLIGNE.$message.$RETOURLIGNE;
                if(sendmail($data['mailPersonne'], $sujet, 2, $message))
                {
                    writeInLogs("Mail d'information de validation positive de commande envoyé au demandeur pour la commande " . $_GET['id'], '2');
                }
                else
                {
                    writeInLogs("Erreur lors de l'envoi du mail d'information de validation positive de commande au demandeur pour la commande " . $_GET['id'], '5');
                }
            }
        }
        if($config['notifications_commandes_valideur_validationOK']==1)
        {
            $query = $db->query('SELECT * FROM PERSONNE_REFERENTE;');
            $data = $query->fetch();
            if(cmdEstValideur($data['idPersonne'],$_GET['id']))
            {
                $message = "Bonjour " . $data['prenomPersonne'] . ", <br/><br/> La commande " . $_GET['id'] . " dont vous êtes le valideur vient d'être acceptée.";
                $message = $message . "<br/><br/>Cordialement<br/><br/>L'équipe administrative de " . $APPNAME;
                $message = $RETOURLIGNE.$message.$RETOURLIGNE;
                if(sendmail($data['mailPersonne'], $sujet, 2, $message))
                {
                    writeInLogs("Mail d'information de validation positive de commande envoyé au valideur pour la commande " . $_GET['id'], '2');
                }
                else
                {
                    writeInLogs("Erreur lors de l'envoi du mail d'information de validation positive de commande au valideur pour la commande " . $_GET['id'], '5');
                }
            }
        }
        if($config['notifications_commandes_affectee_validationOK']==1)
        {
            $query = $db->query('SELECT * FROM PERSONNE_REFERENTE;');
            $data = $query->fetch();
            if(cmdEstAffectee($data['idPersonne'],$_GET['id']))
            {
                $message = "Bonjour " . $data['prenomPersonne'] . ", <br/><br/> Pour information, la commande " . $_GET['id'] . " qui vous est affectée vient d'être acceptée.";
                $message = $message . "<br/><br/>Cordialement<br/><br/>L'équipe administrative de " . $APPNAME;
                $message = $RETOURLIGNE.$message.$RETOURLIGNE;
                if(sendmail($data['mailPersonne'], $sujet, 2, $message))
                {
                    writeInLogs("Mail d'information de validation positive de commande envoyé au gérant pour la commande " . $_GET['id'], '2');
                }
                else
                {
                    writeInLogs("Erreur lors de l'envoi du mail de passage de validation positive de commande au gérant pour la commande " . $_GET['id'], '5');
                }
            }
        }
        if($config['notifications_commandes_observateur_validationOK']==1)
        {
            $query = $db->query('SELECT * FROM PERSONNE_REFERENTE;');
            $data = $query->fetch();
            if(cmdEstObservateur($data['idPersonne'],$_GET['id']))
            {
                $message = "Bonjour " . $data['prenomPersonne'] . ", <br/><br/> Pour information, la commande " . $_GET['id'] . " dont vous êtes l'observateur vient d'être acceptée.";
                $message = $message . "<br/><br/>Cordialement<br/><br/>L'équipe administrative de " . $APPNAME;
                $message = $RETOURLIGNE.$message.$RETOURLIGNE;
                if(sendmail($data['mailPersonne'], $sujet, 2, $message))
                {
                    writeInLogs("Mail d'information de validation positive de commande envoyé à l'observateur pour la commande " . $_GET['id'], '2');
                }
                else
                {
                    writeInLogs("Erreur lors de l'envoi du mail de passage de validation positive de commande à l'observateur pour la commande " . $_GET['id'], '5');
                }
            }
        }
    }
    else
    {
        $query = $db->prepare('UPDATE COMMANDES SET idEtat = 1, remarquesValidation = :remarquesValidation, dateValidation = CURRENT_TIMESTAMP WHERE idCommande = :idCommande;');
        $query->execute(array(
            'idCommande' => $_GET['id'],
            'remarquesValidation' => $_POST['remarquesValidation']
        ));
        addCommandeComment($_GET['id'], $_SESSION['identifiant'] . " refuse la commande avec le commentaire: " . $_POST['remarquesValidation'], "19");

        $sujet = "[" . $APPNAME . "] Validation négative de la commande " .$_GET['id'];

        if($config['notifications_commandes_demandeur_validationNOK']==1)
        {
            $query = $db->query('SELECT * FROM PERSONNE_REFERENTE;');
            $data = $query->fetch();
            if(cmdEstDemandeur($data['idPersonne'],$_GET['id']))
            {
                $message = "Bonjour " . $data['prenomPersonne'] . ", <br/><br/> Pour information, la commande " . $_GET['id'] . " dont vous êtes le demandeur vient d'être refusée.";
                $message = $message . "<br/><br/>Cordialement<br/><br/>L'équipe administrative de " . $APPNAME;
                $message = $RETOURLIGNE.$message.$RETOURLIGNE;
                if(sendmail($data['mailPersonne'], $sujet, 2, $message))
                {
                    writeInLogs("Mail d'information de validation négative de commande envoyé au demandeur pour la commande " . $_GET['id'], '2');
                }
                else
                {
                    writeInLogs("Erreur lors de l'envoi du mail d'information de validation négative de commande au demandeur pour la commande " . $_GET['id'], '5');
                }
            }
        }
        if($config['notifications_commandes_valideur_validationNOK']==1)
        {
            $query = $db->query('SELECT * FROM PERSONNE_REFERENTE;');
            $data = $query->fetch();
            if(cmdEstValideur($data['idPersonne'],$_GET['id']))
            {
                $message = "Bonjour " . $data['prenomPersonne'] . ", <br/><br/> La commande " . $_GET['id'] . " dont vous êtes le valideur vient d'être refusée.";
                $message = $message . "<br/><br/>Cordialement<br/><br/>L'équipe administrative de " . $APPNAME;
                $message = $RETOURLIGNE.$message.$RETOURLIGNE;
                if(sendmail($data['mailPersonne'], $sujet, 2, $message))
                {
                    writeInLogs("Mail d'information de validation négative de commande envoyé au valideur pour la commande " . $_GET['id'], '2');
                }
                else
                {
                    writeInLogs("Erreur lors de l'envoi du mail d'information de validation négative de commande au valideur pour la commande " . $_GET['id'], '5');
                }
            }
        }
        if($config['notifications_commandes_affectee_validationNOK']==1)
        {
            $query = $db->query('SELECT * FROM PERSONNE_REFERENTE;');
            $data = $query->fetch();
            if(cmdEstAffectee($data['idPersonne'],$_GET['id']))
            {
                $message = "Bonjour " . $data['prenomPersonne'] . ", <br/><br/> Pour information, la commande " . $_GET['id'] . " qui vous est affectée vient d'être refusée.";
                $message = $message . "<br/><br/>Cordialement<br/><br/>L'équipe administrative de " . $APPNAME;
                $message = $RETOURLIGNE.$message.$RETOURLIGNE;
                if(sendmail($data['mailPersonne'], $sujet, 2, $message))
                {
                    writeInLogs("Mail d'information de validation négative de commande envoyé au gérant pour la commande " . $_GET['id'], '2');
                }
                else
                {
                    writeInLogs("Erreur lors de l'envoi du mail de passage de validation négative de commande au gérant pour la commande " . $_GET['id'], '5');
                }
            }
        }
        if($config['notifications_commandes_observateur_validationNOK']==1)
        {
            $query = $db->query('SELECT * FROM PERSONNE_REFERENTE;');
            $data = $query->fetch();
            if(cmdEstObservateur($data['idPersonne'],$_GET['id']))
            {
                $message = "Bonjour " . $data['prenomPersonne'] . ", <br/><br/> Pour information, la commande " . $_GET['id'] . " dont vous êtes l'observateur vient d'être refusée.";
                $message = $message . "<br/><br/>Cordialement<br/><br/>L'équipe administrative de " . $APPNAME;
                $message = $RETOURLIGNE.$message.$RETOURLIGNE;
                if(sendmail($data['mailPersonne'], $sujet, 2, $message))
                {
                    writeInLogs("Mail d'information de validation négative de commande envoyé à l'observateur pour la commande " . $_GET['id'], '2');
                }
                else
                {
                    writeInLogs("Erreur lors de l'envoi du mail de passage de validation négative de commande à l'observateur pour la commande " . $_GET['id'], '5');
                }
            }
        }
    }

    switch($query->errorCode())
    {
        case '00000':
            writeInLogs("Modification de la commande " . $_GET['id'], '3');
            break;

        default:
            writeInLogs("Erreur inconnue lors de la modification de la commande.", '5');
            $_SESSION['returnMessage'] = "Erreur inconnue lors l'enregistrement de la validation.";
            $_SESSION['returnType'] = '2';

    }

    echo "<script type='text/javascript'>document.location.replace('commandesToutes.php');</script>";

}
?>