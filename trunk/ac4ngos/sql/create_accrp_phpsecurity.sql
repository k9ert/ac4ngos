# File:		create_accrp_security.sql
# 
# This additional Table is needed because of the security-System
# used by the PHP-Files, copied and adapted by the gcdb-Project
# the Table User is not part of the original ACCRP-Database, so I
# put this SQL-Statements in an extra-File (this one).
# So the security-System is based on two steps:
# 1. mysql-internal security System (Database mysql, Table user)
# this is the "base"-System (No access without access on this level)
# 2. php-internal security system (Database accrp, Table Users)
# The Table and some entries are generated in this file. This level of
# security is only relevant for access by web (PHP)
#

use ac4ngos;

CREATE TABLE Users (
  UserID int(10) unsigned DEFAULT '0' NOT NULL auto_increment,
  Username varchar(50),
  Password varchar(50),
  RealName varchar(100),
  Language varchar(50),
  Admin enum('Yes','No'),
  PRIMARY KEY (UserID)
);

#
# Dumping data for table 'Users'
#

LOCK TABLES Users WRITE;
INSERT INTO Users VALUES (1,'admin','admin','Administrator','english.php','Yes');
INSERT INTO Users VALUES (4,'test','test','A Test Account','english.php','No');
UNLOCK TABLES;
