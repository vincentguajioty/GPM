ALTER TABLE CONFIG ADD consommation_benevoles BOOLEAN AFTER alertes_benevoles_vehicules;
UPDATE CONFIG set consommation_benevoles = false;

CREATE TABLE LOTS_CONSOMMATION(
	idConsommation INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	nomDeclarantConsommation TEXT,
	dateConsommation DATETIME,
	evenementConsommation TEXT,
	commentairesConsommation TEXT,
	ipDeclarantConsommation TEXT
);

CREATE TABLE LOTS_CONSOMMATION_MATERIEL(
	idConsommationMateriel INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
	idConsommation INT,
	idMaterielCatalogue INT,
	idEmplacement INT,
	quantiteConsommation INT,
	reconditionne BOOLEAN,
	idConteneur INT,
	CONSTRAINT fk_consoMateriel_conso
		FOREIGN KEY (idConsommation)
		REFERENCES LOTS_CONSOMMATION(idConsommation),
	CONSTRAINT fk_consoMateriel_materiel
		FOREIGN KEY (idMaterielCatalogue)
		REFERENCES MATERIEL_CATALOGUE(idMaterielCatalogue),
	CONSTRAINT fk_consoMateriel_emplacement
		FOREIGN KEY (idEmplacement)
		REFERENCES MATERIEL_EMPLACEMENT(idEmplacement),
	CONSTRAINT fk_consoMateriel_reserve
		FOREIGN KEY (idConteneur)
		REFERENCES RESERVES_CONTENEUR(idConteneur)
);

UPDATE CONFIG set version = '13.7';