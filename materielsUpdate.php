<?php
session_start();
require_once('logCheck.php');
?>
<?php
require_once 'config/bdd.php';

if ($_SESSION['materiel_modification']==0)
{
    echo "<script type='text/javascript'>document.location.replace('loginHabilitation.php');</script>";
}
else
{

    if ($_POST['libelleMateriel'] == Null)
    {
        $idMaterielCatalogue = Null;
    }
    else
    {
        $idMaterielCatalogue = $_POST['libelleMateriel'];
    }

    if($_POST['nomFournisseur'] == Null)
    {
        $idFournisseur = Null;
    }
    else
    {
        $idFournisseur = $_POST['nomFournisseur'];
    }

    if ($_POST['libelleEmplacement'] == NULL)
    {
        $idEmplacement = Null;
    }
    else
    {
        $idEmplacement = $_POST['libelleEmplacement'];
    }

    if ($_POST['boolPeremption'] == '1')
    {
        $peremption = $_POST['peremption'];
        $peremptionNotification = date('Y-m-d', strtotime($_POST['peremption'] . ' -' . $_POST['delaisPeremption'] . ' days'));
    }
    else
    {
        $peremption = Null;
        $peremptionNotification = Null;
    }

    $query = $db->prepare('UPDATE MATERIEL_ELEMENT SET idMaterielCatalogue = :idMaterielCatalogue, idEmplacement = :idEmplacement, idFournisseur = :idFournisseur, quantite = :quantite, quantiteAlerte = :quantiteAlerte, peremption = :peremption, peremptionNotification = :peremptionNotification, commentairesElement = :commentairesElement WHERE idElement = :idElement;');
    $query->execute(array(
        'idElement' => $_GET['id'],
        'idMaterielCatalogue' => $idMaterielCatalogue,
        'idEmplacement' => $idEmplacement,
        'idFournisseur' => $idFournisseur,
        'quantite' => $_POST['quantite'],
        'quantiteAlerte' => $_POST['quantiteAlerte'],
        'peremption' => $peremption,
        'peremptionNotification' => $peremptionNotification,
        'commentairesElement' => $_POST['commentairesElement']
    ));

    switch($query->errorCode())
    {
        case '00000':
            writeInLogs("Modification du materiel " . $_POST['libelleMateriel'], '3');
            $_SESSION['returnMessage'] = 'Matériel modifié avec succès.';
            $_SESSION['returnType'] = '1';
            break;

        case '23000':
            writeInLogs("Doublon détecté lors de la modification du matériel " . $_POST['libelleMateriel'], '5');
            $_SESSION['returnMessage'] = 'Un matériel existe déjà dans cet emplacement. Au lieu d\'ajouter à nouveau le matériel, veuillez changer sa quantité.';
            $_SESSION['returnType'] = '2';
            break;

        default:
            writeInLogs("Erreur inconnue lors de la modification du matériel " . $_POST['libelleMateriel'], '5');
            $_SESSION['returnMessage'] = "Erreur inconnue lors la modification du matériel.";
            $_SESSION['returnType'] = '2';
    }

    echo "<script>javascript:history.go(-2);</script>";
}
?>