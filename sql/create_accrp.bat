#!/bin/bash
#
# File: 	create_accrp
# created:	23.10.2003
# Author:	Kim Neunert (k9ert@gmx.de)
#
# Following things are needed, to run this file properly:
# - Linux-operating System with Bash
# - Running MySQL-Installation
#	(try to start "mysql" and "mysqladmin")
# - MySQL-Account called "root" which has no password
# - The following files are needed in the same directory than this one:
#	- create_accrp_db.sql
#	- create_accrp_tables.sql
#	- load_accrp_datadump.sql

echo -n creating accrp-database ... 
mysql -u root < create_accrp_db.sql || exit
echo done
echo -n reloading mysql-database ... 
mysqladmin -u root reload || exit
echo done
echo -n creating table-structure ... 
mysql -u root create_accrp_tables.sql || exit
echo done
echo -n "creating security System (for PHP access) ..."
mysql -u root create_accrp_phpsecurity.sql || exit
echo done

echo -n "Altering AC_CODE5_5.TXT (see details in code) ..."
# Before we Load the Datadump into the database, we have to do some work.
# The AC_ID5 Field in the AC_ID5 table is not properly. Values are not unique.
# In difference to the oracle structure, the mySQL structure contains the
# AC_ID5 field as autoincrement and as primary key.
# Before Loading, we have to delete the column with the AC_ID5-field.
# We do that by the following work-around:
cd accrpdump
# First, make a backup of our File
cp AC_CODE5_5.TXT AC_CODE5_5.TXT.backup
# Then, we split up AC_ID5 in 2 files. One with Columns 1-4 ...
cat AC_CODE5_5.TXT | cut --fields=1,2,3,4 > AC_CODE5_5.TXT.1
# ... and one with Column 6
cat AC_CODE5_5.TXT | cut --fields=6 > AC_CODE5_5.TXT.3
# Column 5 should be empty. In the database, this column will be
# filled up with the autoincrement-values
touch AC_CODE5_5.TXT.2
# Now, we paste these 3 Files to one file
paste AC_CODE5_5.TXT.1 AC_CODE5_5.TXT.2  AC_CODE5_5.TXT.3 > AC_CODE5_5.TXT
# and tidy up
rm AC_CODE5_5.TXT.1 AC_CODE5_5.TXT.2  AC_CODE5_5.TXT.3
# That's it. Let's proceed
cd ..
echo done

echo -n Loading Datadump into database ...
cat load_accrp_datadump.sql | mysql -u root || exit
echo done

echo -n Copying back AC_CODE5_5.TXT.backup to origin ...
mv accrpdump/AC_CODE5_5.TXT.backup accrpdump/AC_CODE5_5.TXT
echo done


