<?php

require_once 'bdd.php';
require_once 'config.php';
require_once 'mailFunction.php';

writeInLogs("Début du traitement automatiques des alertes par mail.", '1', NULL);

$query = $db->query('
	SELECT
		COUNT(*) as nb
	FROM
		PERSONNE_REFERENTE
	WHERE
		notifications_abo_cejour = 1
		AND
			(notif_lots_manquants = 1
			OR notif_lots_peremptions = 1
			OR notif_lots_inventaires = 1
			OR notif_lots_conformites = 1
			OR notif_reserves_manquants = 1
			OR notif_reserves_peremptions = 1
			OR notif_reserves_inventaires = 1
			OR notif_vehicules_assurances = 1
			OR notif_vehicules_revisions = 1
			OR notif_vehicules_ct = 1
			OR notif_vehicules_desinfections = 1
			OR notif_vehicules_health = 1
			OR notif_tenues_stock = 1
			OR notif_tenues_retours = 1)
	;');
$data = $query->fetch();
$nbDest = $data['nb'];

if ($nbDest > 0)
{
    $query = $db->query('SELECT COUNT(*) as nb FROM LOTS_LOTS WHERE alerteConfRef = 1 AND idEtat = 1;');
	$data = $query->fetch();
	$nbLotsNOK = $data['nb'];

	$query = $db->query('SELECT COUNT(*) as nb FROM MATERIEL_ELEMENT m LEFT OUTER JOIN MATERIEL_EMPLACEMENT e ON m.idEmplacement=e.idEmplacement LEFT OUTER JOIN MATERIEL_SAC s ON e.idSac = s.idSac LEFT OUTER JOIN LOTS_LOTS l ON s.idLot = l.idLot LEFT OUTER JOIN MATERIEL_CATALOGUE c ON m.idMaterielCatalogue = c.idMaterielCatalogue WHERE (quantite < quantiteAlerte OR quantite = quantiteAlerte) AND idEtat = 1;');
	$data = $query->fetch();
	$nbManquant = $data['nb'];

	$query = $db->query('SELECT COUNT(*) as nb FROM MATERIEL_ELEMENT m LEFT OUTER JOIN MATERIEL_EMPLACEMENT e ON m.idEmplacement=e.idEmplacement LEFT OUTER JOIN MATERIEL_SAC s ON e.idSac = s.idSac LEFT OUTER JOIN LOTS_LOTS l ON s.idLot = l.idLot LEFT OUTER JOIN MATERIEL_CATALOGUE c ON m.idMaterielCatalogue = c.idMaterielCatalogue WHERE (peremptionNotification < CURRENT_DATE OR peremptionNotification = CURRENT_DATE) AND idEtat = 1;');
	$data = $query->fetch();
	$nbPerime = $data['nb'];
	
	$query = $db->query('SELECT COUNT(*) as nb FROM RESERVES_MATERIEL WHERE quantiteReserve < quantiteAlerteReserve OR quantiteReserve = quantiteAlerteReserve;');
	$data = $query->fetch();
	$nbManquantReserve = $data['nb'];
	
	$query = $db->query('SELECT COUNT(*) as nb FROM RESERVES_MATERIEL WHERE peremptionNotificationReserve < CURRENT_DATE OR peremptionNotificationReserve = CURRENT_DATE;');
	$data = $query->fetch();
	$nbPerimeReserve = $data['nb'];
	
	$query = $db->prepare('SELECT COUNT(*) as nb FROM VEHICULES WHERE idEtat = 1 AND (assuranceExpiration IS NOT NULL) AND ((DATE_SUB(assuranceExpiration, INTERVAL :delai DAY) < CURRENT_DATE) OR (DATE_SUB(assuranceExpiration, INTERVAL :delai DAY) = CURRENT_DATE));');
	$query->execute(array('delai'=>$VEHICULES_ASSURANCE_DELAIS_NOTIF));
	$data = $query->fetch();
	$nbAssurance = $data['nb'];
	
	$query = $db->prepare('SELECT COUNT(*) as nb FROM VEHICULES WHERE idEtat = 1 AND ((dateNextRevision IS NOT NULL) AND ((DATE_SUB(dateNextRevision, INTERVAL :delai DAY) < CURRENT_DATE) OR (DATE_SUB(dateNextRevision, INTERVAL :delai DAY) = CURRENT_DATE)));');
	$query->execute(array('delai'=>$VEHICULES_REVISION_DELAIS_NOTIF));
	$data = $query->fetch();
	$nbRevisions = $data['nb'];

	$query = $db->prepare('SELECT COUNT(*) as nb FROM VEHICULES WHERE idEtat = 1 AND ((dateNextCT IS NOT NULL) AND ((DATE_SUB(dateNextCT, INTERVAL :delai DAY) < CURRENT_DATE) OR (DATE_SUB(dateNextCT, INTERVAL :delai DAY) = CURRENT_DATE)));');
	$query->execute(array('delai'=>$VEHICULES_CT_DELAIS_NOTIF));
	$data = $query->fetch();
	$nbCT = $data['nb'];

	$query = $db->query('SELECT COUNT(*) as nb FROM VEHICULES WHERE idEtat = 1 AND alerteDesinfection = 1;');
	$data = $query->fetch();
	$nbDesinfections = $data['nb'];

	$query = $db->query('SELECT COUNT(*) as nb FROM VEHICULES WHERE idEtat = 1 AND alerteMaintenance = 1;');
	$data = $query->fetch();
	$nbHealth = $data['nb'];
	
	$query = $db->query('SELECT COUNT(*) as nb FROM LOTS_LOTS WHERE idEtat = 1 AND (frequenceInventaire IS NOT NULL) AND ((DATE_ADD(dateDernierInventaire, INTERVAL frequenceInventaire DAY) < CURRENT_DATE) OR (DATE_ADD(dateDernierInventaire, INTERVAL frequenceInventaire DAY) = CURRENT_DATE));');
	$data = $query->fetch();
	$nbInventaires = $data['nb'];
	
	$query = $db->query('SELECT COUNT(*) as nb FROM RESERVES_CONTENEUR WHERE (frequenceInventaire IS NOT NULL) AND ((DATE_ADD(dateDernierInventaire, INTERVAL frequenceInventaire DAY) < CURRENT_DATE) OR (DATE_ADD(dateDernierInventaire, INTERVAL frequenceInventaire DAY) = CURRENT_DATE));');
	$data = $query->fetch();
	$nbInventairesReserve = $data['nb'];

	$query = $db->query('SELECT COUNT(*) as nb FROM TENUES_CATALOGUE WHERE stockCatalogueTenue < stockAlerteCatalogueTenue OR stockCatalogueTenue = stockAlerteCatalogueTenue;');
	$data = $query->fetch();
	$nbManquantTenues = $data['nb'];
	
	$query = $db->query('SELECT COUNT(*) as nb FROM TENUES_AFFECTATION WHERE dateRetour < CURRENT_DATE OR dateRetour = CURRENT_DATE;');
	$data = $query->fetch();
	$nbRetoursTenues = $data['nb'];

    $nbAlertes = $nbManquant + $nbPerime + $nbLotsNOK + $nbManquantReserve + $nbPerimeReserve + $nbAssurance + $nbRevisions + $nbInventaires + $nbInventairesReserve + $nbCT + $nbDesinfections + $nbHealth + $nbManquantTenues + $nbRetoursTenues;

    writeInLogs($nbManquant." alertes détectées sur la requete nbManquant (éléments en quantité insuffisante dans les lots).", '1', NULL);
    writeInLogs($nbPerime." alertes détectées sur la requete nbPerime (éléments périmés dans les lots).", '1', NULL);
    writeInLogs($nbLotsNOK." alertes détectées sur la requete nbLotsNOK (conformité des lots).", '1', NULL);
    writeInLogs($nbManquantReserve." alertes détectées sur la requete nbManquantReserve (éléments en quantité insuffisante dans la réserve).", '1', NULL);
    writeInLogs($nbPerimeReserve." alertes détectées sur la requete nbPerimeReserve (éléments périmés dans la réserve).", '1', NULL);
    writeInLogs($nbAssurance." alertes détectées sur la requete nbAssurance (péremption des assurances).", '1', NULL);
    writeInLogs($nbRevisions." alertes détectées sur la requete nbRevisions (révisions à faire).", '1', NULL);
    writeInLogs($nbInventaires." alertes détectées sur la requete nbInventaires (inventaires des lots).", '1', NULL);
    writeInLogs($nbInventairesReserve." alertes détectées sur la requete nbInventairesReserve (inventaires de la réserve).", '1', NULL);
    writeInLogs($nbCT." alertes détectées sur la requete nbCT (controles techniques).", '1', NULL);
    writeInLogs($nbHealth." alertes détectées sur la requete nbHealth (maintenances régulières).", '1', NULL);
    writeInLogs($nbDesinfections." alertes détectées sur la requete nbDesinfections (désinfections des véhicules).", '1', NULL);
    writeInLogs($nbManquantTenues." alertes détectées sur la requete nbManquantTenues (éléments de tenue en quantité insuffisante dans les stocks).", '1', NULL);
    writeInLogs($nbRetoursTenues." alertes détectées sur la requete nbRetoursTenues (éléments de tenue non-rendus).", '1', NULL);
    
    writeInLogs($nbAlertes." alertes détectées par le système de notifications journalières.", '1', NULL);

    if ($nbAlertes == 0)
    {
        writeInLogs("Pas de notification journalière à envoyer.", '1', NULL);
        exit;
    }

    


    if ($nbPerime > 0)
	{
	    $message_Perime = "Alertes de péremption des consommables dans les lots:<br/><ul>";
	    $query = $db->query('SELECT * FROM MATERIEL_ELEMENT m LEFT OUTER JOIN MATERIEL_EMPLACEMENT e ON m.idEmplacement=e.idEmplacement LEFT OUTER JOIN MATERIEL_SAC s ON e.idSac = s.idSac LEFT OUTER JOIN LOTS_LOTS l ON s.idLot = l.idLot LEFT OUTER JOIN MATERIEL_CATALOGUE c ON m.idMaterielCatalogue = c.idMaterielCatalogue LEFT OUTER JOIN PERSONNE_REFERENTE p ON l.idPersonne = p.idPersonne WHERE (peremptionNotification < CURRENT_DATE OR peremptionNotification = CURRENT_DATE) AND idEtat = 1;');
	    while($data = $query->fetch())
	    {
	        $message_Perime = $message_Perime . "<li>".$data['libelleLot'] . " > " . $data['libelleSac'] . " > " . $data['libelleEmplacement'] . " > " . $data['libelleMateriel']."</li>";
	    }
	    $message_Perime = $message_Perime."</ul><br/><br/>";
	}
	
	if ($nbManquant > 0)
	{
	    $message_Manquant = "Alertes de quantité des lots:<br/><ul>";
	    $query = $db->query('SELECT * FROM MATERIEL_ELEMENT m LEFT OUTER JOIN MATERIEL_EMPLACEMENT e ON m.idEmplacement=e.idEmplacement LEFT OUTER JOIN MATERIEL_SAC s ON e.idSac = s.idSac LEFT OUTER JOIN LOTS_LOTS l ON s.idLot = l.idLot LEFT OUTER JOIN PERSONNE_REFERENTE p ON l.idPersonne = p.idPersonne LEFT OUTER JOIN MATERIEL_CATALOGUE c ON m.idMaterielCatalogue = c.idMaterielCatalogue WHERE (quantite < quantiteAlerte OR quantite = quantiteAlerte) AND idEtat = 1;');
	    while($data = $query->fetch())
	    {
	        $message_Manquant = $message_Manquant . "<li>".$data['libelleLot'] . " > " . $data['libelleSac'] . " > " . $data['libelleEmplacement'] . " > " . $data['libelleMateriel']."</li>";
	    }
	    $message_Manquant = $message_Manquant."</ul><br/><br/>";
	}
	
	if ($nbLotsNOK > 0)
	{
	    $message_Conf = "Alertes de conformité des lots:<br/><ul>";
	    $query = $db->query('SELECT * FROM LOTS_LOTS l LEFT OUTER JOIN PERSONNE_REFERENTE p ON l.idPersonne = p.idPersonne LEFT OUTER JOIN LOTS_TYPES t ON l.idTypeLot = t.idTypeLot WHERE alerteConfRef = 1 AND idEtat = 1;');
	    while($data = $query->fetch())
	    {
	        $message_Conf = $message_Conf . "<li>".$data['libelleLot'] . "</li>";
	    }
	    $message_Conf = $message_Conf."</ul><br/><br/>";
	}
	
	if ($nbInventaires > 0)
	{
	    $message_InventaireLots = "Alertes d'inventaires des lots:<br/><ul>";
	    $query = $db->query('SELECT * FROM LOTS_LOTS WHERE idEtat = 1 AND (frequenceInventaire IS NOT NULL) AND ((DATE_ADD(dateDernierInventaire, INTERVAL frequenceInventaire DAY) < CURRENT_DATE) OR (DATE_ADD(dateDernierInventaire, INTERVAL frequenceInventaire DAY) = CURRENT_DATE));');
	    while($data = $query->fetch())
	    {
	        $message_InventaireLots = $message_InventaireLots . "<li>".$data['libelleLot'] . "</li>";
	    }
	    $message_InventaireLots = $message_InventaireLots."</ul><br/><br/>";
	}
	
	if ($nbPerimeReserve > 0)
	{
	    $message_PerimeReserve = "Alertes de péremption de la réserve:<br/><ul>";
	    $query = $db->query('SELECT * FROM RESERVES_MATERIEL m LEFT OUTER JOIN RESERVES_CONTENEUR c ON m.idConteneur=c.idConteneur LEFT OUTER JOIN MATERIEL_CATALOGUE r ON m.idMaterielCatalogue = r.idMaterielCatalogue WHERE peremptionNotificationReserve < CURRENT_DATE OR peremptionNotificationReserve = CURRENT_DATE;');
	    while($data = $query->fetch())
	    {
	        $message_PerimeReserve = $message_PerimeReserve . "<li>".$data['libelleConteneur'] . " > " . $data['libelleMateriel']."</li>";
	    }
	    $message_PerimeReserve = $message_PerimeReserve."</ul><br/><br/>";
	}
    
    if ($nbManquantReserve > 0)
	{
	    $message_ManquantReserve = "Alertes de quantité de la réserve:<br/><ul>";
	    $query = $db->query('SELECT * FROM RESERVES_MATERIEL m LEFT OUTER JOIN RESERVES_CONTENEUR c ON m.idConteneur=c.idConteneur LEFT OUTER JOIN MATERIEL_CATALOGUE r ON m.idMaterielCatalogue = r.idMaterielCatalogue WHERE quantiteReserve < quantiteAlerteReserve OR quantiteReserve = quantiteAlerteReserve;');
	    while($data = $query->fetch())
	    {
	        $message_ManquantReserve = $message_ManquantReserve . "<li>".$data['libelleConteneur'] . " > " . $data['libelleMateriel']."</li>";
	    }
	    $message_ManquantReserve = $message_ManquantReserve."</ul><br/><br/>";
	}
	
	if ($nbInventairesReserve > 0)
	{
	    $message_InventaireReserve = "Alertes d'inventaires des conteneurs de réserve:<br/><ul>";
	    $query = $db->query('SELECT * FROM RESERVES_CONTENEUR WHERE (frequenceInventaire IS NOT NULL) AND ((DATE_ADD(dateDernierInventaire, INTERVAL frequenceInventaire DAY) < CURRENT_DATE) OR (DATE_ADD(dateDernierInventaire, INTERVAL frequenceInventaire DAY) = CURRENT_DATE));');
	    while($data = $query->fetch())
	    {
	        $message_InventaireReserve = $message_InventaireReserve . "<li>".$data['libelleConteneur'] . "</li>";
	    }
	    $message_InventaireReserve = $message_InventaireReserve."</ul><br/><br/>";
	}
    
    if ($nbAssurance > 0)
	{
	    $message_Assurance = "Véhicules dont l'assurance est arrivée à échéance:<br/><ul>";
	    $query = $db->prepare('SELECT * FROM VEHICULES WHERE idEtat = 1 AND (assuranceExpiration IS NOT NULL) AND ((DATE_SUB(assuranceExpiration, INTERVAL :delai DAY) < CURRENT_DATE) OR (DATE_SUB(assuranceExpiration, INTERVAL :delai DAY) = CURRENT_DATE));');
	    $query->execute(array('delai'=>$VEHICULES_ASSURANCE_DELAIS_NOTIF));
	    while($data = $query->fetch())
	    {
	        $message_Assurance = $message_Assurance . "<li>".$data['libelleVehicule'] . "</li>";
	    }
	    $message_Assurance = $message_Assurance."</ul><br/><br/>";
	}
    
    if ($nbRevisions > 0)
	{
	    $message_Revisions = "Véhicules à faire passer à la révision:<br/><ul>";
	    $query = $db->prepare('SELECT * FROM VEHICULES WHERE idEtat = 1 AND ((dateNextRevision IS NOT NULL) AND ((DATE_SUB(dateNextRevision, INTERVAL :delai DAY) < CURRENT_DATE) OR (DATE_SUB(dateNextRevision, INTERVAL :delai DAY) = CURRENT_DATE)));');
	    $query->execute(array('delai'=>$VEHICULES_REVISION_DELAIS_NOTIF));
	    while($data = $query->fetch())
	    {
	        $message_Revisions = $message_Revisions . "<li>".$data['libelleVehicule'] . "</li>";
	    }
	    $message_Revisions = $message_Revisions."</ul><br/><br/>";
	}

	if ($nbCT > 0)
	{
	    $message_CT = "Véhicules à faire passer au CT:<br/><ul>";
	    $query = $db->prepare('SELECT * FROM VEHICULES WHERE idEtat = 1 AND ((dateNextCT IS NOT NULL) AND ((DATE_SUB(dateNextCT, INTERVAL :delai DAY) < CURRENT_DATE) OR (DATE_SUB(dateNextCT, INTERVAL :delai DAY) = CURRENT_DATE)));');
	    $query->execute(array('delai'=>$VEHICULES_CT_DELAIS_NOTIF));
	    while($data = $query->fetch())
	    {
	        $message_CT = $message_CT . "<li>".$data['libelleVehicule'] . "</li>";
	    }
	    $message_CT = $message_CT."</ul><br/><br/>";
	}

	if ($nbDesinfections > 0)
	{
	    $message_Desinfection = "Véhicules à désinfecter:<br/><ul>";
	    $query = $db->query('SELECT * FROM VEHICULES WHERE idEtat = 1 AND alerteDesinfection = 1;');
	    while($data = $query->fetch())
	    {
	        $message_Desinfection = $message_Desinfection . "<li>".$data['libelleVehicule'] . "</li>";
	    }
	    $message_Desinfection = $message_Desinfection."</ul><br/><br/>";
	}

	if ($nbHealth > 0)
	{
	    $message_Health = "Véhicules pour lesquels une tache de maintenance est en attente:<br/><ul>";
	    $query = $db->query('SELECT * FROM VEHICULES WHERE idEtat = 1 AND alerteMaintenance = 1;');
	    while($data = $query->fetch())
	    {
	        $message_Health = $message_Health . "<li>".$data['libelleVehicule'] . "</li>";
	    }
	    $message_Health = $message_Health."</ul><br/><br/>";
	}
	
	if ($nbManquantTenues > 0)
	{
	    $message_TenuesManquantes = "Elements de tenue dont le stock est insuffisant:<br/><ul>";
	    $query = $db->query('SELECT * FROM TENUES_CATALOGUE WHERE stockCatalogueTenue < stockAlerteCatalogueTenue OR stockCatalogueTenue = stockAlerteCatalogueTenue;');
	    while($data = $query->fetch())
	    {
	        $message_TenuesManquantes = $message_TenuesManquantes . "<li>".$data['libelleCatalogueTenue'] . "</li>";
	    }
	    $message_TenuesManquantes = $message_TenuesManquantes."</ul><br/><br/>";
	}
	
	if ($nbRetoursTenues > 0)
	{
	    $message_TenuesRetour = "Tenues à récupérer:<br/><ul>";
	    $query = $db->query('SELECT * FROM TENUES_AFFECTATION ta JOIN TENUES_CATALOGUE tc ON ta.idCatalogueTenue = tc.idCatalogueTenue LEFT OUTER JOIN PERSONNE_REFERENTE p ON ta.idPersonne = p.idPersonne WHERE dateRetour < CURRENT_DATE OR dateRetour = CURRENT_DATE;');
	    while($data = $query->fetch())
	    {
	        $message_TenuesRetour = $message_TenuesRetour . "<li>".$data['nomPersonne'] . ' ' . $data['prenomPersonne'] . $data['personneNonGPM'] . ' - ' . $data['libelleCatalogueTenue'] . "</li>";
	    }
	    $message_TenuesRetour = $message_TenuesRetour."</ul><br/><br/>";
	}





    $query = $db->query('
    	SELECT
    		*
    	FROM
    		PERSONNE_REFERENTE
    	WHERE
    		notifications_abo_cejour = 1
    		AND
	    		(notif_lots_manquants = 1
	    		OR notif_lots_peremptions = 1
	    		OR notif_lots_inventaires = 1
	    		OR notif_lots_conformites = 1
	    		OR notif_reserves_manquants = 1
	    		OR notif_reserves_peremptions = 1
	    		OR notif_reserves_inventaires = 1
	    		OR notif_vehicules_assurances = 1
	    		OR notif_vehicules_revisions = 1
	    		OR notif_vehicules_ct = 1
	    		OR notif_vehicules_health = 1
	    		OR notif_vehicules_desinfections = 1
	    		OR notif_tenues_stock = 1
	    		OR notif_tenues_retours = 1)
    ;');
	while($data = $query->fetch())
	{
		$nbAlertes = 0;
		$nbAlertes += ($data['notif_lots_manquants']          == 1) ? $nbManquant : 0;
		$nbAlertes += ($data['notif_lots_peremptions']        == 1) ? $nbPerime : 0;
		$nbAlertes += ($data['notif_lots_inventaires']        == 1) ? $nbInventaires : 0;
		$nbAlertes += ($data['notif_lots_conformites']        == 1) ? $nbLotsNOK : 0;
		$nbAlertes += ($data['notif_reserves_manquants']      == 1) ? $nbManquantReserve : 0;
		$nbAlertes += ($data['notif_reserves_peremptions']    == 1) ? $nbPerimeReserve : 0;
		$nbAlertes += ($data['notif_vehicules_assurances']    == 1) ? $nbAssurance : 0;
		$nbAlertes += ($data['notif_vehicules_revisions']     == 1) ? $nbRevisions : 0;
		$nbAlertes += ($data['notif_vehicules_ct']            == 1) ? $nbCT : 0;
		$nbAlertes += ($data['notif_vehicules_desinfections'] == 1) ? $nbDesinfections : 0;
		$nbAlertes += ($data['notif_vehicules_health']        == 1) ? $nbHealth : 0;
		$nbAlertes += ($data['notif_reserves_inventaires']    == 1) ? $nbInventairesReserve : 0;
		$nbAlertes += ($data['notif_tenues_stock']            == 1) ? $nbManquantTenues : 0;
		$nbAlertes += ($data['notif_tenues_retours']          == 1) ? $nbRetoursTenues : 0;

		if($nbAlertes > 0)
		{
			writeInLogs($nbAlertes." alertes à envoyer à la personne référence ".$data['idPersonne']." sur l'adresse ".$data['mailPersonne'], '1', NULL);
			if ($nbAlertes == 1)
		    {
		        $sujet = "[" . $APPNAME . "] Bilan journalier - 1 alerte en cours sur votre parc materiel";
		    }
		    else
		    {
		        $sujet = "[" . $APPNAME . "] Bilan journalier - " . $nbAlertes  . " alertes en cours sur votre parc materiel";
		    }


		    $message_html = "Bonjour " . $data['prenomPersonne'] . ", <br/><br/>Ceci est une notification journalière d'alerte sur " . $APPNAME . ".<br/><br/>";

		    if(($data['notif_lots_manquants']) AND ($nbManquant>0))
		    {
		    	$message_html .= $message_Manquant;
		    }
		    if(($data['notif_lots_peremptions']) AND ($nbPerime>0))
		    {
		    	$message_html .= $message_Perime;
		    }
		    if(($data['notif_lots_inventaires']) AND ($nbInventaires>0))
		    {
		    	$message_html .= $message_InventaireLots;
		    }
		    if(($data['notif_lots_conformites']) AND ($nbLotsNOK>0))
		    {
		    	$message_html .= $message_Conf;
		    }
		    if(($data['notif_reserves_manquants']) AND ($nbManquantReserve>0))
		    {
		    	$message_html .= $message_ManquantReserve;
		    }
		    if(($data['notif_reserves_peremptions']) AND ($nbPerimeReserve>0))
		    {
		    	$message_html .= $message_PerimeReserve;
		    }
		    if(($data['notif_reserves_inventaires']) AND ($nbInventairesReserve>0))
		    {
		    	$message_html .= $message_InventaireReserve;
		    }
		    if(($data['notif_vehicules_assurances']) AND ($nbAssurance>0))
		    {
		    	$message_html .= $message_Assurance;
		    }
		    if(($data['notif_vehicules_revisions']) AND ($nbRevisions>0))
		    {
		    	$message_html .= $message_Revisions;
		    }
		    if(($data['notif_vehicules_ct']) AND ($nbCT>0))
		    {
		    	$message_html .= $message_CT;
		    }
		    if(($data['notif_vehicules_desinfections']) AND ($nbDesinfections>0))
		    {
		    	$message_html .= $message_Desinfection;
		    }
		    if(($data['notif_vehicules_health']) AND ($nbHealth>0))
		    {
		    	$message_html .= $message_Health;
		    }
		    if(($data['notif_tenues_stock']) AND ($nbManquantTenues>0))
		    {
		    	$message_html .= $message_TenuesManquantes;
		    }
		    if(($data['notif_tenues_retours']) AND ($nbRetoursTenues>0))
		    {
		    	$message_html .= $message_TenuesRetour;
		    }
		    
		    $message_html = $message_html . "Cordialement<br/><br/>L'équipe administrative de " . $APPNAME . "<br/><br/>";
		    $message.= $RETOURLIGNE.$message_html.$RETOURLIGNE;
		    $prio = 3;

		    if(sendmail($data['mailPersonne'], $sujet, $prio, $message))
	        {
	            writeInLogs("Notification journalière envoyée avec succès à la personne référence ".$data['idPersonne']." sur l'adresse ". $data['mailPersonne'], '1', NULL);
	        }
	        else
	        {
	            writeInLogs("Echec de l'envoi de la notification journalière à la personne référence ".$data['idPersonne']." sur l'adresse ". $data['mailPersonne'], '1', NULL);
	        }
	        
	        unset($message_html);
	        unset($message);
	        unset($sujet);
		}
		else
		{
			writeInLogs("Aucune alerte à envoyer à la personne référence ".$data['idPersonne']." sur l'adresse ".$data['mailPersonne']." . Pas de mail généré.", '1', NULL);
		}
		
	}
}
else
{
	writeInLogs("Aucun destinataire potentiel d'alertes par email, abandon du process d'alerte.", '1', NULL);
}

writeInLogs("Fin du traitement automatiques des alertes par mail.", '1', NULL);

?>
