<?php
session_start();
require_once('logCheck.php');
?>
<?php
require_once 'config/bdd.php';

if ($_SESSION['profils_ajout']==0)
{
    echo "<script type='text/javascript'>document.location.replace('loginHabilitation.php');</script>";
}
else
{
    $query = $db->prepare('SELECT * FROM PROFILS WHERE idProfil = :idProfil');
	$query->execute(array(
        'idProfil'  =>  $_POST['idProfil']
	));
	$data = $query->fetch();

    $query = $db->prepare('INSERT INTO PROFILS(libelleProfil, descriptifProfil, connexion_connexion, logs_lecture, annuaire_lecture, annuaire_ajout, annuaire_modification, annuaire_mdp, annuaire_suppression, profils_lecture, profils_ajout, profils_modification, profils_suppression, categories_lecture, categories_ajout, categories_modification, categories_suppression, fournisseurs_lecture, fournisseurs_ajout, fournisseurs_modification, fournisseurs_suppression, typesLots_lecture, typesLots_ajout, typesLots_modification, typesLots_suppression, lieux_lecture, lieux_ajout, lieux_modification, lieux_suppression, lots_lecture, lots_ajout, lots_modification, lots_suppression, sac_lecture, sac_ajout, sac_modification, sac_suppression, sac2_lecture, sac2_ajout, sac2_modification, sac2_suppression, catalogue_lecture, catalogue_ajout, catalogue_modification, catalogue_suppression, materiel_lecture, materiel_ajout, materiel_modification, materiel_suppression, messages_ajout, messages_suppression, notifications, verrouIP, commande_lecture, commande_ajout, commande_valider, commande_etreEnCharge, commande_abandonner, cout_lecture, cout_ajout, cout_etreEnCharge, cout_supprimer, appli_conf, reserve_lecture, reserve_ajout, reserve_modification, reserve_suppression, reserve_cmdVersReserve, reserve_ReserveVersLot, vhf_canal_lecture, vhf_canal_ajout, vhf_canal_modification, vhf_canal_suppression, vhf_plan_lecture, vhf_plan_ajout, vhf_plan_modification, vhf_plan_suppression, vhf_equipement_lecture, vhf_equipement_ajout, vhf_equipement_modification, vhf_equipement_suppression, vehicules_lecture, vehicules_ajout, vehicules_modification, vehicules_suppression, vehicules_types_lecture, vehicules_types_ajout, vehicules_types_modification, vehicules_types_suppression) VALUES (:libelleProfil, :descriptifProfil, :connexion_connexion, :logs_lecture, :annuaire_lecture, :annuaire_ajout, :annuaire_modification, :annuaire_mdp, :annuaire_suppression, :profils_lecture, :profils_ajout, :profils_modification, :profils_suppression, :categories_lecture, :categories_ajout, :categories_modification, :categories_suppression, :fournisseurs_lecture, :fournisseurs_ajout, :fournisseurs_modification, :fournisseurs_suppression, :typesLots_lecture, :typesLots_ajout, :typesLots_modification, :typesLots_suppression, :lieux_lecture, :lieux_ajout, :lieux_modification, :lieux_suppression, :lots_lecture, :lots_ajout, :lots_modification, :lots_suppression, :sac_lecture, :sac_ajout, :sac_modification, :sac_suppression, :sac2_lecture, :sac2_ajout, :sac2_modification, :sac2_suppression, :catalogue_lecture, :catalogue_ajout, :catalogue_modification, :catalogue_suppression, :materiel_lecture, :materiel_ajout, :materiel_modification, :materiel_suppression, :messages_ajout, :messages_suppression, :notifications, :verrouIP, :commande_lecture, :commande_ajout, :commande_valider, :commande_etreEnCharge, :commande_abandonner, :cout_lecture, :cout_ajout, :cout_etreEnCharge, :cout_supprimer, :appli_conf, :reserve_lecture, :reserve_ajout, :reserve_modification, :reserve_suppression, :reserve_cmdVersReserve, :reserve_ReserveVersLot, :vhf_canal_lecture, :vhf_canal_ajout, :vhf_canal_modification, :vhf_canal_suppression, :vhf_plan_lecture, :vhf_plan_ajout, :vhf_plan_modification, :vhf_plan_suppression, :vhf_equipement_lecture, :vhf_equipement_ajout, :vhf_equipement_modification, :vhf_equipement_suppression, :vehicules_lecture, :vehicules_ajout, :vehicules_modification, :vehicules_suppression, :vehicules_types_lecture, :vehicules_types_ajout, :vehicules_types_modification, :vehicules_types_suppression);');
    $query->execute(array(
        'libelleProfil'  =>  $data['libelleProfil'] . ' - Copie',
        'descriptifProfil'  =>  $data['descriptifProfil'],
        'connexion_connexion'  =>  $data['connexion_connexion'],
        'logs_lecture'  =>  $data['logs_lecture'],
        'annuaire_lecture'  =>  $data['annuaire_lecture'],
        'annuaire_ajout'  =>  $data['annuaire_ajout'],
        'annuaire_modification'  =>  $data['annuaire_modification'],
        'annuaire_mdp'  =>  $data['annuaire_mdp'],
        'annuaire_suppression'  =>  $data['annuaire_suppression'],
        'profils_lecture'  =>  $data['profils_lecture'],
        'profils_ajout'  =>  $data['profils_ajout'],
        'profils_modification'  =>  $data['profils_modification'],
        'profils_suppression'  =>  $data['profils_suppression'],
        'categories_lecture'  =>  $data['categories_lecture'],
        'categories_ajout'  =>  $data['categories_ajout'],
        'categories_modification'  =>  $data['categories_modification'],
        'categories_suppression'  =>  $data['categories_suppression'],
        'fournisseurs_lecture'  =>  $data['fournisseurs_lecture'],
        'fournisseurs_ajout'  =>  $data['fournisseurs_ajout'],
        'fournisseurs_modification'  =>  $data['fournisseurs_modification'],
        'fournisseurs_suppression'  =>  $data['fournisseurs_suppression'],
        'typesLots_lecture'  =>  $data['typesLots_lecture'],
        'typesLots_ajout'  =>  $data['typesLots_ajout'],
        'typesLots_modification'  =>  $data['typesLots_modification'],
        'typesLots_suppression'  =>  $data['typesLots_suppression'],
        'lieux_lecture'  =>  $data['lieux_lecture'],
        'lieux_ajout'  =>  $data['lieux_ajout'],
        'lieux_modification'  =>  $data['lieux_modification'],
        'lieux_suppression'  =>  $data['lieux_suppression'],
        'lots_lecture'  =>  $data['lots_lecture'],
        'lots_ajout'  =>  $data['lots_ajout'],
        'lots_modification'  =>  $data['lots_modification'],
        'lots_suppression'  =>  $data['lots_suppression'],
        'sac_lecture'  =>  $data['sac_lecture'],
        'sac_ajout'  =>  $data['sac_ajout'],
        'sac_modification'  =>  $data['sac_modification'],
        'sac_suppression'  =>  $data['sac_suppression'],
        'sac2_lecture'  =>  $data['sac2_lecture'],
        'sac2_ajout'  =>  $data['sac2_ajout'],
        'sac2_modification'  =>  $data['sac2_modification'],
        'sac2_suppression'  =>  $data['sac2_suppression'],
        'catalogue_lecture'  =>  $data['catalogue_lecture'],
        'catalogue_ajout'  =>  $data['catalogue_ajout'],
        'catalogue_modification'  =>  $data['catalogue_modification'],
        'catalogue_suppression'  =>  $data['catalogue_suppression'],
        'materiel_lecture'  =>  $data['materiel_lecture'],
        'materiel_ajout'  =>  $data['materiel_ajout'],
        'materiel_modification'  =>  $data['materiel_modification'],
        'materiel_suppression'  =>  $data['materiel_suppression'],
        'messages_ajout' => $data['messages_ajout'],
        'messages_suppression' => $data['messages_suppression'],
        'notifications' => $data['notifications'],
        'verrouIP' => $data['verrouIP'],
        'commande_lecture' => $data['commande_lecture'],
	    'commande_ajout' => $data['commande_ajout'],
	    'commande_valider' => $data['commande_valider'],
	    'commande_etreEnCharge' => $data['commande_etreEnCharge'],
	    'commande_abandonner' => $data['commande_abandonner'],
	    'cout_lecture' => $data['cout_lecture'],
	    'cout_ajout' => $data['cout_ajout'],
	    'cout_etreEnCharge' => $data['cout_etreEnCharge'],
	    'cout_supprimer' => $data['cout_supprimer'],
	    'appli_conf' => $data['appli_conf'],
        'reserve_lecture' => $data['reserve_lecture'],
        'reserve_ajout' => $data['reserve_ajout'],
        'reserve_modification' => $data['reserve_modification'],
        'reserve_suppression' => $data['reserve_suppression'],
        'reserve_cmdVersReserve' => $data['reserve_cmdVersReserve'],
        'reserve_ReserveVersLot' => $data['reserve_ReserveVersLot'],
        'vhf_canal_lecture' => $data['vhf_canal_lecture'],
        'vhf_canal_ajout' => $data['vhf_canal_ajout'],
        'vhf_canal_modification' => $data['vhf_canal_modification'],
        'vhf_canal_suppression' => $data['vhf_canal_suppression'],
        'vhf_plan_lecture' => $data['vhf_plan_lecture'],
        'vhf_plan_ajout' => $data['vhf_plan_ajout'],
        'vhf_plan_modification' => $data['vhf_plan_modification'],
        'vhf_plan_suppression' => $data['vhf_plan_suppression'],
        'vhf_equipement_lecture' => $data['vhf_equipement_lecture'],
        'vhf_equipement_ajout' => $data['vhf_equipement_ajout'],
        'vhf_equipement_modification' => $data['vhf_equipement_modification'],
        'vhf_equipement_suppression' => $data['vhf_equipement_suppression'],
        'vehicules_lecture' => $data['vehicules_lecture'],
        'vehicules_ajout' => $data['vehicules_ajout'],
        'vehicules_modification' => $data['vehicules_modification'],
        'vehicules_suppression' => $data['vehicules_suppression'],
        'vehicules_types_lecture' => $data['vehicules_types_lecture'],
        'vehicules_types_ajout' => $data['vehicules_types_ajout'],
        'vehicules_types_modification' => $data['vehicules_types_modification'],
        'vehicules_types_suppression' => $data['vehicules_types_suppression']
));

    switch($query->errorCode())
    {
        case '00000':
            writeInLogs("Duplication du profil " . $data['libelleProfil'], '2');
            $_SESSION['returnMessage'] = 'Profil dupliqué avec succès.';
            $_SESSION['returnType'] = '1';
            break;

        default:
            writeInLogs("Erreur inconnue lors de la duplication du profil " . $data['libelleProfil'], '5');
            $_SESSION['returnMessage'] = "Erreur inconnue lors de la duplication du profil.";
            $_SESSION['returnType'] = '2';
    }


    echo "<script>window.location = document.referrer;</script>";
}
?>