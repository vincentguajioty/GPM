<?php
session_start();
require_once('logCheck.php');
?>
<?php
require_once 'config/bdd.php';

if ($_SESSION['sac2_ajout']==0)
{
    echo "<script type='text/javascript'>document.location.replace('loginHabilitation.php');</script>";
}
else
{
    $_POST['libelleSac'] = ($_POST['libelleSac'] == Null) ? Null : $_POST['libelleSac'];


    $query = $db->prepare('INSERT INTO MATERIEL_EMPLACEMENT(libelleEmplacement, idSac) VALUES(:libelleEmplacement, :idSac);');
    $query->execute(array(
        'libelleEmplacement' => $_POST['libelleEmplacement'],
        'idSac' => $_POST['libelleSac']
    ));

    switch($query->errorCode())
    {
        case '00000':
            writeInLogs("Ajout de l'emplacement " . $_POST['libelleEmplacement'], '2');
            $_SESSION['returnMessage'] = 'Emplacement ajouté avec succès.';
            $_SESSION['returnType'] = '1';
            break;

        case '23000':
            writeInLogs("Doublon détecté lors de l'ajout de l'emplacement " . $_POST['libelleEmplacement'], '5');
            $_SESSION['returnMessage'] = 'Un emplacement existe déjà avec le même libellé. Merci de changer le libellé.';
            $_SESSION['returnType'] = '2';
            break;

        default:
            writeInLogs("Erreur inconnue lors de l'ajout de l'emplacement " . $_POST['libelleEmplacement'], '5');
            $_SESSION['returnMessage'] = "Erreur inconnue lors l'ajout de l'emplacement.";
            $_SESSION['returnType'] = '2';
    }


    echo "<script>window.location = document.referrer;</script>";
}
?>