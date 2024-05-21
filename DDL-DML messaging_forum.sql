DROP DATABASE IF EXISTS messaging_forum;
CREATE DATABASE IF NOT EXISTS messaging_forum CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE messaging_forum;
SET NAMES 'utf8mb4';

CREATE TABLE user (
	id			INTEGER			NOT NULL UNIQUE AUTO_INCREMENT, 
    username 	VARCHAR(50) 	NOT NULL UNIQUE,
	password	CHAR(255)		NOT NULL,
	firstname	VARCHAR(100)	NOT NULL,
	lastname	VARCHAR(100)	NOT NULL,
	level		INTEGER			NOT NULL,
	email		VARCHAR(100)	NOT NULL,
	approved	TINYINT			NOT NULL DEFAULT 0,
	CONSTRAINT user_pk PRIMARY KEY (id)
)ENGINE=InnoDB;

CREATE TABLE unit (
	id				INTEGER			NOT NULL UNIQUE AUTO_INCREMENT,
	name			VARCHAR(100)	NOT NULL,
	CONSTRAINT unit_pk PRIMARY KEY (id)
)ENGINE=InnoDB;

DROP TABLE IF EXISTS thread;
CREATE TABLE thread (
	id				INTEGER			NOT NULL UNIQUE AUTO_INCREMENT,
	header			VARCHAR(50)		NOT NULL,
	userid			INTEGER			NOT NULL,
	unitid			INTEGER			NOT NULL,
	CONSTRAINT thread_pk PRIMARY KEY (id),
	CONSTRAINT thread_user_fk FOREIGN KEY (userid) REFERENCES user(id) ON UPDATE CASCADE ON DELETE RESTRICT,
	CONSTRAINT thread_unit_fk FOREIGN KEY (unitid) REFERENCES unit(id) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;

DROP TABLE IF EXISTS message;
CREATE TABLE message (
	id				INTEGER			NOT NULL UNIQUE AUTO_INCREMENT,
	text			VARCHAR(1000)	NOT NULL,
	userid 			INTEGER		 	NOT NULL,
	threadid		INTEGER			NOT NULL,
	CONSTRAINT message_pk PRIMARY KEY (id),
	CONSTRAINT message_user_fk FOREIGN KEY (userid) REFERENCES user(id) ON UPDATE CASCADE ON DELETE RESTRICT,
	CONSTRAINT message_thread_fk FOREIGN KEY (threadid) REFERENCES thread(id) ON UPDATE CASCADE ON DELETE CASCADE
)ENGINE=InnoDB;

INSERT INTO unit(name) values("Ζώα");
INSERT INTO unit(name) values("Οικογένεια");
INSERT INTO unit(name) values("Χόμπυ");
INSERT INTO unit(name) values("Επάγγελμα");
INSERT INTO unit(name) values("Εκπαίδευση");
INSERT INTO unit(name) values("Ελεύθερος Χρόνος");

# Κωδικός είναι το 1
INSERT INTO user(username, password, firstname, lastname, level, email, approved) VALUES 
						('admin',	'$2y$10$zW4.Lw6oB8ow82Aiz9Kue.x1eOAgRr/1saAPPurMZ0AMdxwnsXcZ6','admin','admin',0,'admin@gmail.com',1),
						('nikk',	'$2y$10$hyA2wp.5hK9gAdMtjINoXu.5njJjVKkaOajS31MhhiArJIMCJduzq','Nikos','Nikou',1,'nikk@gmail.com',1),
						('kostas','$2y$10$oJkKp7pQllVCPN9ZUVptz.h2qRDBXFMITj8VsoYP3M8If76XIImye','Kostas','Kosta',1,'kostas@gmail.COM',1),
						('giota',	'$2y$10$9nrTZmsbfHN9eDFZtP1CHOzMjtUoqa/IbHVjJEOXNZHRVuiFMg5yq','Giota','Giotou',1,'giota@gmail.com',1);

INSERT INTO thread(header, userid, unitid) VALUES 	
							('Το καλύτερο Κατοικίδιο',4,1),
							('Πιστοποιήσεις',4,5),
							('Ποντιακοί χοροί',2,3);

INSERT INTO message(text, userid, threadid) VALUES		
								('Τι ζώο πιστεύετε ότι θα ήταν το καλύτερο κατοικίδιο?',4,1),
								('Έχω έναν χαμαιλέοντα αλλά σκεφτόμουν να αλλάξω',4,1),
								('Πιστεύετε ότι είναι καλό να παίρνεις πιστοποιήσεις?',4,2),
								('Εγώ έχω μια γάτα και δεν σταματάω ποτέ να μαζεύω τρίχες',2,1),
								('Καλησπέρα, έχω ακούσει ότι οι ποντιακοί χοροί είναι πολύ έντονοι και ότι καις πολλές θερμίδες αληθεύει?',2,3),
								('Τι σε πείραξε ο χαμαιλέοντας και θες να τον αλλάξεις εσύ?',3,1),
								('Εγώ θα έπαιρνα ένα σπουργιτάκι. Πολύ φαί δεν θέλει, πολύ φροντίδα δεν θέλει, και κελαηδάει κιόλας',3,1),
								('Ναι φυσικά. Αν πας ποντιακά δικαιούσαι να μην πας γυμναστήριο και να τρως πίτσες όλη μέρα',3,3),
								('Για επαγγελματική αποκατάσταση ναι είναι πολύ καλό',3,2),
								('Αν και πρέπει να προσέχεις ποια πιστοποίηση θα διαλέξεις. Κυκλοφορεί πολύ σαβούρα εκεί έξω',3,2);
