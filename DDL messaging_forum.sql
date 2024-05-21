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
	CONSTRAINT thread_unit_fk FOREIGN KEY (unitid) REFERENCES unit(id) ON UPDATE CASCADE ON DELETE RESTRICT
)ENGINE=InnoDB;

DROP TABLE IF EXISTS message;
CREATE TABLE message (
	id				INTEGER			NOT NULL UNIQUE AUTO_INCREMENT,
	text			VARCHAR(1000)	NOT NULL,
	userid 			INTEGER		 	NOT NULL,
	threadid		INTEGER			NOT NULL,
	CONSTRAINT message_pk PRIMARY KEY (id),
	CONSTRAINT message_user_fk FOREIGN KEY (userid) REFERENCES user(id) ON UPDATE CASCADE ON DELETE RESTRICT,
	CONSTRAINT message_thread_fk FOREIGN KEY (threadid) REFERENCES thread(id) ON UPDATE CASCADE ON DELETE RESTRICT
)ENGINE=InnoDB;


INSERT INTO unit(name) values("Ζώα");
INSERT INTO unit(name) values("Οικογένεια");
INSERT INTO unit(name) values("Χόμπυ");


INSERT INTO thread(header, userid, unitid) values("Το κατοικίδιό μου", 1, 1);
INSERT INTO thread(header, userid, unitid) values("Η γάτα το έσκασε", 1, 1);
INSERT INTO thread(header, userid, unitid) values("Ο αδελφός μου ο αρκούδος", 1, 2);