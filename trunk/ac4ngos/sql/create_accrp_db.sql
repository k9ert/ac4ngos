create database ac4ngos;
use mysql;
REPLACE INTO user ( host, user, password,  file_priv)
    VALUES (
        'localhost',
	'crpuser',
	password('crp'),
	'y'
);

REPLACE INTO db ( host, db, user, select_priv, insert_priv, update_priv,delete_priv, create_priv, drop_priv) 
	VALUES (
		'localhost',
		'ac4ngos',
		'crpuser',
		'Y', 'Y', 'Y', 'Y','Y', 'Y'
);

												  

