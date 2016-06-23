/*
CREATE DATABASE bemean;
USE bemean;
*/

CREATE TABLE users(
	 id int(8) primary key auto_increment
	,name		VARCHAR(200)
	,email    VARCHAR(200)
	,type	    varchar(80)
	,active   CHAR(1)
);

INSERT INTO users (name, email, type, active) VALUES ('Suissa', 'suissa@yahoo.com.br'	,'teacher','1');
INSERT INTO users (name, email, type, active) VALUES ('Gabriel', 'gabriel@yahoo.com.br'	,'student','1');
INSERT INTO users (name, email, type, active) VALUES ('Test', 'test@yahoo.com.br'		,'teacher','1');
