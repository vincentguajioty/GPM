UPDATE CONFIG set version = '5.6';

INSERT INTO LOGS(dateEvt, adresseIP, utilisateurEvt, idLogLevel, detailEvt) VALUES(CURRENT_TIMESTAMP, 'local', 'sysAdmin', 2, 'Mise à jour vers la version 5.6.');

ALTER TABLE PERSONNE_REFERENTE DROP conf_joursCalendAccueil;