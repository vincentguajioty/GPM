ALTER TABLE MATERIEL_ELEMENT ADD numeroSerie TEXT NULL AFTER peremptionNotification;
ALTER TABLE RESERVES_MATERIEL ADD numeroSerie TEXT NULL AFTER peremptionNotificationReserve;

ALTER TABLE MATERIEL_CATALOGUE ADD disponibleBenevolesConso BOOLEAN DEFAULT false;

UPDATE CONFIG SET version = '15.1';