ALTER TABLE TODOLIST ADD dateCloture DATETIME NULL AFTER dateExecution;

UPDATE CONFIG set version = '8.6';

