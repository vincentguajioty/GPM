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

    $query = $db->prepare('
    	INSERT INTO
    		PROFILS
    	SET
			libelleProfil                             = :libelleProfil,
			LDAP_BINDDN                               = :LDAP_BINDDN,
			descriptifProfil                          = :descriptifProfil,
			connexion_connexion                       = :connexion_connexion,
			annuaire_lecture                          = :annuaire_lecture,
			annuaire_ajout                            = :annuaire_ajout,
			annuaire_modification                     = :annuaire_modification,
			annuaire_mdp                              = :annuaire_mdp,
			annuaire_suppression                      = :annuaire_suppression,
			profils_lecture                           = :profils_lecture,
			profils_ajout                             = :profils_ajout,
			profils_modification                      = :profils_modification,
			profils_suppression                       = :profils_suppression,
			categories_lecture                        = :categories_lecture,
			categories_ajout                          = :categories_ajout,
			categories_modification                   = :categories_modification,
			categories_suppression                    = :categories_suppression,
			fournisseurs_lecture                      = :fournisseurs_lecture,
			fournisseurs_ajout                        = :fournisseurs_ajout,
			fournisseurs_modification                 = :fournisseurs_modification,
			fournisseurs_suppression                  = :fournisseurs_suppression,
			typesLots_lecture                         = :typesLots_lecture,
			typesLots_ajout                           = :typesLots_ajout,
			typesLots_modification                    = :typesLots_modification,
			typesLots_suppression                     = :typesLots_suppression,
			lieux_lecture                             = :lieux_lecture,
			lieux_ajout                               = :lieux_ajout,
			lieux_modification                        = :lieux_modification,
			lieux_suppression                         = :lieux_suppression,
			lots_lecture                              = :lots_lecture,
			lots_ajout                                = :lots_ajout,
			lots_modification                         = :lots_modification,
			lots_suppression                          = :lots_suppression,
			sac_lecture                               = :sac_lecture,
			sac_ajout                                 = :sac_ajout,
			sac_modification                          = :sac_modification,
			sac_suppression                           = :sac_suppression,
			sac2_lecture                              = :sac2_lecture,
			sac2_ajout                                = :sac2_ajout,
			sac2_modification                         = :sac2_modification,
			sac2_suppression                          = :sac2_suppression,
			catalogue_lecture                         = :catalogue_lecture,
			catalogue_ajout                           = :catalogue_ajout,
			catalogue_modification                    = :catalogue_modification,
			catalogue_suppression                     = :catalogue_suppression,
			materiel_lecture                          = :materiel_lecture,
			materiel_ajout                            = :materiel_ajout,
			materiel_modification                     = :materiel_modification,
			materiel_suppression                      = :materiel_suppression,
			messages_ajout                            = :messages_ajout,
			messages_suppression                      = :messages_suppression,
			verrouIP                                  = :verrouIP,
			commande_lecture                          = :commande_lecture,
			commande_ajout                            = :commande_ajout,
			commande_valider_delegate                 = :commande_valider_delegate,
			commande_etreEnCharge                     = :commande_etreEnCharge,
			commande_abandonner                       = :commande_abandonner,
			cout_lecture                              = :cout_lecture,
			cout_ajout                                = :cout_ajout,
			cout_etreEnCharge                         = :cout_etreEnCharge,
			cout_supprimer                            = :cout_supprimer,
			appli_conf                                = :appli_conf,
			reserve_lecture                           = :reserve_lecture,
			reserve_ajout                             = :reserve_ajout,
			reserve_modification                      = :reserve_modification,
			reserve_suppression                       = :reserve_suppression,
			reserve_cmdVersReserve                    = :reserve_cmdVersReserve,
			reserve_ReserveVersLot                    = :reserve_ReserveVersLot,
			vhf_canal_lecture                         = :vhf_canal_lecture,
			vhf_canal_ajout                           = :vhf_canal_ajout,
			vhf_canal_modification                    = :vhf_canal_modification,
			vhf_canal_suppression                     = :vhf_canal_suppression,
			vhf_plan_lecture                          = :vhf_plan_lecture,
			vhf_plan_ajout                            = :vhf_plan_ajout,
			vhf_plan_modification                     = :vhf_plan_modification,
			vhf_plan_suppression                      = :vhf_plan_suppression,
			vhf_equipement_lecture                    = :vhf_equipement_lecture,
			vhf_equipement_ajout                      = :vhf_equipement_ajout,
			vhf_equipement_modification               = :vhf_equipement_modification,
			vhf_equipement_suppression                = :vhf_equipement_suppression,
			vehicules_lecture                         = :vehicules_lecture,
			vehicules_ajout                           = :vehicules_ajout,
			vehicules_modification                    = :vehicules_modification,
			vehicules_suppression                     = :vehicules_suppression,
			vehicules_types_lecture                   = :vehicules_types_lecture,
			vehicules_types_ajout                     = :vehicules_types_ajout,
			vehicules_types_modification              = :vehicules_types_modification,
			vehicules_types_suppression               = :vehicules_types_suppression,
			maintenance                               = :maintenance,
			todolist_perso                            = :todolist_perso,
			todolist_lecture                          = :todolist_lecture,
			todolist_modification                     = :todolist_modification,
			contactMailGroupe                         = :contactMailGroupe,
			tenues_lecture                            = :tenues_lecture,
			tenues_ajout                              = :tenues_ajout,
			tenues_modification                       = :tenues_modification,
			tenues_suppression                        = :tenues_suppression,
			tenuesCatalogue_lecture                   = :tenuesCatalogue_lecture,
			tenuesCatalogue_ajout                     = :tenuesCatalogue_ajout,
			tenuesCatalogue_modification              = :tenuesCatalogue_modification,
			tenuesCatalogue_suppression               = :tenuesCatalogue_suppression,
			cautions_lecture                          = :cautions_lecture,
			cautions_ajout                            = :cautions_ajout,
			cautions_modification                     = :cautions_modification,
			cautions_suppression                      = :cautions_suppression,
			etats_lecture                             = :etats_lecture,
			etats_ajout                               = :etats_ajout,
			etats_modification                        = :etats_modification,
			etats_suppression                         = :etats_suppression,
			notifications                             = :notifications,
			actionsMassives                           = :actionsMassives,
			delegation                                = :delegation,
			desinfections_lecture                     = :desinfections_lecture,
			desinfections_ajout                       = :desinfections_ajout,
			desinfections_modification                = :desinfections_modification,
			desinfections_suppression                 = :desinfections_suppression,
			typesDesinfections_lecture                = :typesDesinfections_lecture,
			typesDesinfections_ajout                  = :typesDesinfections_ajout,
			typesDesinfections_modification           = :typesDesinfections_modification,
			typesDesinfections_suppression            = :typesDesinfections_suppression,
			carburants_lecture                        = :carburants_lecture,
			carburants_ajout                          = :carburants_ajout,
			carburants_modification                   = :carburants_modification,
			carburants_suppression                    = :carburants_suppression,
			vehiculeHealthType_lecture                = :vehiculeHealthType_lecture,
			vehiculeHealthType_ajout                  = :vehiculeHealthType_ajout,
			vehiculeHealthType_modification           = :vehiculeHealthType_modification,
			vehiculeHealthType_suppression            = :vehiculeHealthType_suppression,
			vehiculeHealth_lecture                    = :vehiculeHealth_lecture,
			vehiculeHealth_ajout                      = :vehiculeHealth_ajout,
			vehiculeHealth_modification               = :vehiculeHealth_modification,
			vehiculeHealth_suppression                = :vehiculeHealth_suppression,
			alertesBenevolesLots_lecture              = :alertesBenevolesLots_lecture,
			alertesBenevolesLots_affectation          = :alertesBenevolesLots_affectation,
			alertesBenevolesLots_affectationTier      = :alertesBenevolesLots_affectationTier,
			alertesBenevolesVehicules_lecture         = :alertesBenevolesVehicules_lecture,
			alertesBenevolesVehicules_affectation     = :alertesBenevolesVehicules_affectation,
			alertesBenevolesVehicules_affectationTier = :alertesBenevolesVehicules_affectationTier,
            codeBarre_lecture                         = :codeBarre_lecture,
            codeBarre_ajout                           = :codeBarre_ajout,
            codeBarre_modification                    = :codeBarre_modification,
            codeBarre_suppression                     = :codeBarre_suppression,
            consommationLots_lecture                  = :consommationLots_lecture,
            consommationLots_affectation              = :consommationLots_affectation,
            consommationLots_supression               = :consommationLots_supression
	;');
    $query->execute(array(
		'libelleProfil'                             => $data['libelleProfil'] . ' - Copie',
		'LDAP_BINDDN'                               => $data['LDAP_BINDDN'],
		'descriptifProfil'                          => $data['descriptifProfil'],
		'connexion_connexion'                       => $data['connexion_connexion'],
		'annuaire_lecture'                          => $data['annuaire_lecture'],
		'annuaire_ajout'                            => $data['annuaire_ajout'],
		'annuaire_modification'                     => $data['annuaire_modification'],
		'annuaire_mdp'                              => $data['annuaire_mdp'],
		'annuaire_suppression'                      => $data['annuaire_suppression'],
		'profils_lecture'                           => $data['profils_lecture'],
		'profils_ajout'                             => $data['profils_ajout'],
		'profils_modification'                      => $data['profils_modification'],
		'profils_suppression'                       => $data['profils_suppression'],
		'categories_lecture'                        => $data['categories_lecture'],
		'categories_ajout'                          => $data['categories_ajout'],
		'categories_modification'                   => $data['categories_modification'],
		'categories_suppression'                    => $data['categories_suppression'],
		'fournisseurs_lecture'                      => $data['fournisseurs_lecture'],
		'fournisseurs_ajout'                        => $data['fournisseurs_ajout'],
		'fournisseurs_modification'                 => $data['fournisseurs_modification'],
		'fournisseurs_suppression'                  => $data['fournisseurs_suppression'],
		'typesLots_lecture'                         => $data['typesLots_lecture'],
		'typesLots_ajout'                           => $data['typesLots_ajout'],
		'typesLots_modification'                    => $data['typesLots_modification'],
		'typesLots_suppression'                     => $data['typesLots_suppression'],
		'lieux_lecture'                             => $data['lieux_lecture'],
		'lieux_ajout'                               => $data['lieux_ajout'],
		'lieux_modification'                        => $data['lieux_modification'],
		'lieux_suppression'                         => $data['lieux_suppression'],
		'lots_lecture'                              => $data['lots_lecture'],
		'lots_ajout'                                => $data['lots_ajout'],
		'lots_modification'                         => $data['lots_modification'],
		'lots_suppression'                          => $data['lots_suppression'],
		'sac_lecture'                               => $data['sac_lecture'],
		'sac_ajout'                                 => $data['sac_ajout'],
		'sac_modification'                          => $data['sac_modification'],
		'sac_suppression'                           => $data['sac_suppression'],
		'sac2_lecture'                              => $data['sac2_lecture'],
		'sac2_ajout'                                => $data['sac2_ajout'],
		'sac2_modification'                         => $data['sac2_modification'],
		'sac2_suppression'                          => $data['sac2_suppression'],
		'catalogue_lecture'                         => $data['catalogue_lecture'],
		'catalogue_ajout'                           => $data['catalogue_ajout'],
		'catalogue_modification'                    => $data['catalogue_modification'],
		'catalogue_suppression'                     => $data['catalogue_suppression'],
		'materiel_lecture'                          => $data['materiel_lecture'],
		'materiel_ajout'                            => $data['materiel_ajout'],
		'materiel_modification'                     => $data['materiel_modification'],
		'materiel_suppression'                      => $data['materiel_suppression'],
		'messages_ajout'                            => $data['messages_ajout'],
		'messages_suppression'                      => $data['messages_suppression'],
		'verrouIP'                                  => $data['verrouIP'],
		'commande_lecture'                          => $data['commande_lecture'],
		'commande_ajout'                            => $data['commande_ajout'],
		'commande_valider_delegate'                 => $data['commande_valider_delegate'],
		'commande_etreEnCharge'                     => $data['commande_etreEnCharge'],
		'commande_abandonner'                       => $data['commande_abandonner'],
		'cout_lecture'                              => $data['cout_lecture'],
		'cout_ajout'                                => $data['cout_ajout'],
		'cout_etreEnCharge'                         => $data['cout_etreEnCharge'],
		'cout_supprimer'                            => $data['cout_supprimer'],
		'appli_conf'                                => $data['appli_conf'],
		'reserve_lecture'                           => $data['reserve_lecture'],
		'reserve_ajout'                             => $data['reserve_ajout'],
		'reserve_modification'                      => $data['reserve_modification'],
		'reserve_suppression'                       => $data['reserve_suppression'],
		'reserve_cmdVersReserve'                    => $data['reserve_cmdVersReserve'],
		'reserve_ReserveVersLot'                    => $data['reserve_ReserveVersLot'],
		'vhf_canal_lecture'                         => $data['vhf_canal_lecture'],
		'vhf_canal_ajout'                           => $data['vhf_canal_ajout'],
		'vhf_canal_modification'                    => $data['vhf_canal_modification'],
		'vhf_canal_suppression'                     => $data['vhf_canal_suppression'],
		'vhf_plan_lecture'                          => $data['vhf_plan_lecture'],
		'vhf_plan_ajout'                            => $data['vhf_plan_ajout'],
		'vhf_plan_modification'                     => $data['vhf_plan_modification'],
		'vhf_plan_suppression'                      => $data['vhf_plan_suppression'],
		'vhf_equipement_lecture'                    => $data['vhf_equipement_lecture'],
		'vhf_equipement_ajout'                      => $data['vhf_equipement_ajout'],
		'vhf_equipement_modification'               => $data['vhf_equipement_modification'],
		'vhf_equipement_suppression'                => $data['vhf_equipement_suppression'],
		'vehicules_lecture'                         => $data['vehicules_lecture'],
		'vehicules_ajout'                           => $data['vehicules_ajout'],
		'vehicules_modification'                    => $data['vehicules_modification'],
		'vehicules_suppression'                     => $data['vehicules_suppression'],
		'vehicules_types_lecture'                   => $data['vehicules_types_lecture'],
		'vehicules_types_ajout'                     => $data['vehicules_types_ajout'],
		'vehicules_types_modification'              => $data['vehicules_types_modification'],
		'vehicules_types_suppression'               => $data['vehicules_types_suppression'],
		'maintenance'                               => $data['maintenance'],
		'todolist_perso'                            => $data['todolist_perso'],
		'todolist_lecture'                          => $data['todolist_lecture'],
		'todolist_modification'                     => $data['todolist_modification'],
		'tenues_lecture'                            => $data['tenues_lecture'],
		'tenues_ajout'                              => $data['tenues_ajout'],
		'tenues_modification'                       => $data['tenues_modification'],
		'tenues_suppression'                        => $data['tenues_suppression'],
		'tenuesCatalogue_lecture'                   => $data['tenuesCatalogue_lecture'],
		'tenuesCatalogue_ajout'                     => $data['tenuesCatalogue_ajout'],
		'tenuesCatalogue_modification'              => $data['tenuesCatalogue_modification'],
		'tenuesCatalogue_suppression'               => $data['tenuesCatalogue_suppression'],
		'cautions_lecture'                          => $data['cautions_lecture'],
		'cautions_ajout'                            => $data['cautions_ajout'],
		'cautions_modification'                     => $data['cautions_modification'],
		'cautions_suppression'                      => $data['cautions_suppression'],
		'contactMailGroupe'                         => $data['contactMailGroupe'],
		'etats_lecture'                             => $data['etats_lecture'],
		'etats_ajout'                               => $data['etats_ajout'],
		'etats_modification'                        => $data['etats_modification'],
		'etats_suppression'                         => $data['etats_suppression'],
		'notifications'                             => $data['notifications'],
		'actionsMassives'                           => $data['actionsMassives'],
		'delegation'                                => $data['delegation'],
		'desinfections_lecture'                     => $data['desinfections_lecture'],
		'desinfections_ajout'                       => $data['desinfections_ajout'],
		'desinfections_modification'                => $data['desinfections_modification'],
		'desinfections_suppression'                 => $data['desinfections_suppression'],
		'typesDesinfections_lecture'                => $data['typesDesinfections_lecture'],
		'typesDesinfections_ajout'                  => $data['typesDesinfections_ajout'],
		'typesDesinfections_modification'           => $data['typesDesinfections_modification'],
		'typesDesinfections_suppression'            => $data['typesDesinfections_suppression'],
		'carburants_lecture'                        => $data['carburants_lecture'],
		'carburants_ajout'                          => $data['carburants_ajout'],
		'carburants_modification'                   => $data['carburants_modification'],
		'carburants_suppression'                    => $data['carburants_suppression'],
		'vehiculeHealthType_lecture'                => $data['vehiculeHealthType_lecture'],
		'vehiculeHealthType_ajout'                  => $data['vehiculeHealthType_ajout'],
		'vehiculeHealthType_modification'           => $data['vehiculeHealthType_modification'],
		'vehiculeHealthType_suppression'            => $data['vehiculeHealthType_suppression'],
		'vehiculeHealth_lecture'                    => $data['vehiculeHealth_lecture'],
		'vehiculeHealth_ajout'                      => $data['vehiculeHealth_ajout'],
		'vehiculeHealth_modification'               => $data['vehiculeHealth_modification'],
		'vehiculeHealth_suppression'                => $data['vehiculeHealth_suppression'],
		'alertesBenevolesLots_lecture'              => $data['alertesBenevolesLots_lecture'],
		'alertesBenevolesLots_affectation'          => $data['alertesBenevolesLots_affectation'],
		'alertesBenevolesLots_affectationTier'      => $data['alertesBenevolesLots_affectationTier'],
		'alertesBenevolesVehicules_lecture'         => $data['alertesBenevolesVehicules_lecture'],
		'alertesBenevolesVehicules_affectation'     => $data['alertesBenevolesVehicules_affectation'],
		'alertesBenevolesVehicules_affectationTier' => $data['alertesBenevolesVehicules_affectationTier'],
        'codeBarre_lecture'                         => $data['codeBarre_lecture'],
        'codeBarre_ajout'                           => $data['codeBarre_ajout'],
        'codeBarre_modification'                    => $data['codeBarre_modification'],
        'codeBarre_suppression'                     => $data['codeBarre_suppression'],
        'consommationLots_lecture'                  => $data['consommationLots_lecture'],
        'consommationLots_affectation'              => $data['consommationLots_affectation'],
        'consommationLots_supression'               => $data['consommationLots_supression'],
));

    switch($query->errorCode())
    {
        case '00000':
            writeInLogs("Duplication du profil " . $data['libelleProfil'], '1', NULL);
            $_SESSION['returnMessage'] = 'Profil dupliqué avec succès.';
            $_SESSION['returnType'] = '1';
            break;

        default:
            writeInLogs("Erreur inconnue lors de la duplication du profil " . $data['libelleProfil'], '3', NULL);
            $_SESSION['returnMessage'] = "Erreur inconnue lors de la duplication du profil.";
            $_SESSION['returnType'] = '2';
    }


    echo "<script>window.location = document.referrer;</script>";
}
?>