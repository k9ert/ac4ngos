# These are the Table-Definitions of the NGO Accounting Database-Application
# This Database is a kind of clone from an existing one, based on a Oracle7
# backend. You can see the old definition and afterwards my definitions with
# comments.


use ac4ngos;
#SQL> describe AC_CODE1
# Name                            Null?    Type
# ------------------------------- -------- ----
# AC_ID1                                   CHAR(2)
# AC1_DESC                                 VARCHAR2(55)

# This table makes it possible to distinguish between different Sub-projects of the
# NGO. For CRP, there are e.g. different subcenters which are also physically separated

create table AC_CODE1 (
	AC_ID1 INTEGER auto_increment primary key, 
	AC1_DESC VARCHAR(55),
	# The Shortcuts are used to distinguish Accounts with same name in the drop-down-boxes
	AC1_SC VARCHAR(3)
);

#
#SQL> describe AC_CODE2
# Name                            Null?    Type
# ------------------------------- -------- ----
# AC_ID2                                   CHAR(1)
# AC2_DESC                                 VARCHAR2(15)

# Should only contain exactly four Entries, calles Libility, Assets, Income and Expenses
# Maybe, it is a good idea to get a fourth entry for opening balances only.

create table AC_CODE2 (
	AC_ID2 INTEGER auto_increment primary key, 
	AC2_DESC VARCHAR(15),
	# Shortcut for AC2
	AC2_SC VARCHAR(3)
);

#
#SQL> describe AC_CODE3
# Name                            Null?    Type
# ------------------------------- -------- ----
# AC_ID3                                   CHAR(3)
# AC3_DESC                                 VARCHAR2(30)

# This table is another possibility to categorize the accounts. Special entries (for the
# application) are the two entries "CASH" and "BANK".

create table AC_CODE3 (
	AC_ID3 INTEGER auto_increment primary key, 
	AC3_DESC VARCHAR(30)
);

#
#SQL> describe AC_CODE4
# Name                            Null?    Type
# ------------------------------- -------- ----
# AC_ID4                                   CHAR(3)
# AC4_DESC                                 VARCHAR2(15)

# This table is another possibility to categorize the accounts.

create table AC_CODE4 (
	AC_ID4 INTEGER auto_increment primary key, 
	AC4_DESC VARCHAR(15)
);

#
#SQL> describe AC_CODE5
# Name                            Null?    Type
# ------------------------------- -------- ----
# AC_ID1                                   CHAR(2)
# AC_ID2                                   CHAR(1)
# AC_ID3                                   CHAR(3)
# AC_ID4                                   CHAR(3)
# AC_ID5                                   CHAR(3)
# AC5_DESC                                 VARCHAR2(40)

# This table combines the 4 AC_CODEs to a unique account. It is not a category but an account.

create table AC_CODE5 (
	AC_ID1 INTEGER, 
	AC_ID2 INTEGER, 
	AC_ID3 INTEGER, 
	AC_ID4 INTEGER, 
	AC_ID5 integer auto_increment PRIMARY KEY, 
#	AC_ID5 integer, 
	AC5_DESC VARCHAR(40)
);
ALTER TABLE `AC_CODE5` ADD INDEX(`AC_ID2`);

#
#SQL> describe CUFL_INFO
# Name                            Null?    Type
# ------------------------------- -------- ----
# EMP_ID4                                  CHAR(3)
# OPEN_DT                                  DATE
# STATUS                                   CHAR(1)
# EMP_OPBAL                                NUMBER
# EMP_OPINT                                NUMBER
# TOTCONT                                  NUMBER

# This table is not used in the application but maybe we need it in the future ?

create table CUFL_INFO (
	EMP_ID4 CHAR(3), 
	OPEN_DT DATE, 
	STATUS CHAR(1),
	EMP_OPBAL DOUBLE,
	EMP_OPINT DOUBLE,
	TOTCONT DOUBLE
);

#
#SQL> describe CUF_LOAN
# Name                            Null?    Type
# ------------------------------- -------- ----
# EMP_ID3                                  CHAR(3)
# LOAN_DT                                  DATE
# LOAN_START                               DATE
# LOAN_END                                 DATE
# LOAN_AMT                                 NUMBER
# INT_AMT                                  NUMBER
# TOT_AMT                                  NUMBER
# INST_NO                                  NUMBER
# INT_RATE                                 NUMBER

# CUF is an acronym for "Credit Union Fund". The Employees are allowed to take
# a loan from the organisations under specific circumstances.
# The interest-rate is not stored in this table. 
# 3 to 4 fields are dependant from the other. I havent change it yet and
# maybe it is better to leave it like that, to make it easier if one is
# not paying ... but I am not sure ...

create table CUF_LOAN (
	# The ID of the Employee
	EMP_ID3 INTEGER NOT NULL,
	LOAN_DT DATE,
	# Timepoint, starting to pay back
	LOAN_START DATE,
	# Timepoint, ending of Payback
	LOAN_END DATE,
	# Amount of money, wanted to borrow
	LOAN_AMT INTEGER,
	# Total Interest, the person have to pay
	INT_AMT INTEGER,
	# TOT_AMT=LOAN_AMT+INT_AMT
	TOT_AMT INTEGER,
	# Number of Month, the person have to pay back
	INST_NO INTEGER,
	# Rate per month, the person have to pay back = TOT_AMT / INST_NO
	INT_RATE INTEGER
);

#
#SQL> describe DEPT
# Name                            Null?    Type
# ------------------------------- -------- ----
# DEPT_ID                                  CHAR(3)
# DEPT_NAME                                VARCHAR2(30)
# PCT                                      NUMBER

# There is a possibility, to refer a transeaction to a department. In the reports, this is not used yet
# and maybe will never used. Maybe it is a good idea to refer the personals to a department ?!

create table DEPT (
	DEPT_ID INTEGER auto_increment primary key,
	DEPT_NAME VARCHAR(30),
	# I dont know, for what this is. It is not used in the application
	PCT DOUBLE
);

#
#SQL> describe DESIG
# Name                            Null?    Type
# ------------------------------- -------- ----
# DESIG_ID                        NOT NULL CHAR(3)
# DESIG_DESC                      NOT NULL VARCHAR2(40)

# A Designation Table because every Employee seems to need a designation

create table DESIG (
	DESIG_ID INTEGER auto_increment primary key,
	DESIG_DESC VARCHAR(40) NOT NULL
);

#
#SQL> describe EMP_SAL
# Name                            Null?    Type
# ------------------------------- -------- ----
# EMP_ID3                         NOT NULL CHAR(3)
# ID_TP                           NOT NULL CHAR(1)
# SAL_ID                          NOT NULL CHAR(3)
# SAL_AMT                         NOT NULL NUMBER(10,2)

# The Salary of the Employees. Each Entry refer to the ID of the Employee, the Type
# of the Salary (there are deductions and "addings" to the Salary). The Basic-Salary
# is nothing else than another Type of salary, as well as Type of Salaries which are deductions
# see Table SAL_ID

create table EMP_SAL (
	EMP_ID3 INTEGER NOT NULL,
	# The type of the Salary (raise or lower the salary) see Table SAL_ID
	ID_TP CHAR(1) NOT NULL,
	SAL_ID INTEGER NOT NULL,
	SAL_AMT INTEGER NOT NULL
);

#
#SQL> describe PERSONAL
# Name                            Null?    Type
# ------------------------------- -------- ----
# EMP_ID3                         NOT NULL CHAR(3)
# EMP_NAME                        NOT NULL VARCHAR2(40)
# DESIG_ID                        NOT NULL CHAR(3)
# STATUS                          NOT NULL CHAR(1)
# AC_NO                                    VARCHAR2(20)
# Here is the first and only appearance of EMP_ID2
# EMP_ID2                                  CHAR(3)
# CONF_DT                                  DATE

# The table of the employees

create table PERSONAL (
	# Do not ask me, why the name is "ID3". I should change it ...
	EMP_ID3 INTEGER auto_increment primary key,
	EMP_NAME VARCHAR(40) NOT NULL,
	# See Table DESIG
	DESIG_ID INTEGER,
	# "Y" or "N". No use in the application in the moment
	STATUS CHAR(1) NOT NULL,
	# Account_no at the bank
	AC_NO VARCHAR(20),
	# Do not ask me, no use at Application. Maybe I should delete it.
	EMP_ID2 CHAR(3),
	# Dont know. Just get printed in report.
	CONF_DT DATE,
	# And a new field, requested by shaheen. I should ask him for the purpose.
	JOIN_DT DATE
);

#
#SQL> describe PF_INFO
# Name                            Null?    Type
# ------------------------------- -------- ----
# EMP_ID4                                  CHAR(3)
# OPEN_DT                                  DATE
# STATUS                                   CHAR(1)
# EMP_OPBAL                                NUMBER
# COMP_OPBAL                               NUMBER
# EMP_OPINT                                NUMBER
# TOTCONT                                  NUMBER
# COMP_INT                                 NUMBER
# TOTINT                                   NUMBER
# INTRATE                                  NUMBER

# This table is not used in the application but maybe we need it in the future ?

create table PF_INFO (
	EMP_ID4 CHAR(3),
	OPEN_DT DATE,
	STATUS CHAR(1),
	EMP_OPBAL DOUBLE,
	COMP_OPBAL DOUBLE,
	EMP_OPINT DOUBLE,
	TOTCONT DOUBLE,
	COMP_INT DOUBLE,
	TOTINT DOUBLE,
	INTRATE DOUBLE
);

#
#SQL> describe PF_LOAN
# Name                            Null?    Type
# ------------------------------- -------- ----
#EMP_ID3                                  CHAR(3)
# LOAN_DT                                  DATE
# LOAN_START                               DATE
# LOAN_END                                 DATE
# LOAN_AMT                                 NUMBER
# INT_AMT                                  NUMBER
# TOT_AMT                                  NUMBER
# INST_NO                                  NUMBER
# INT_RATE                                 NUMBER

# PF is a acronym for "Provident Fund". The table-structure is absolute the same
# than that for CUF_LOAN. For details see above. The interest rate (not stored in
# the DB at all) is different for PF_LOAN.

create table PF_LOAN (
	EMP_ID3 INTEGER NOT NULL,
	LOAN_DT DATE,
	LOAN_START DATE,
	LOAN_END DATE,
	LOAN_AMT INTEGER,
	INT_AMT INTEGER,
	TOT_AMT INTEGER,
	INST_NO INTEGER,
	INT_RATE INTEGER
);

#
#SQL> describe SAL_ID
# Name                            Null?    Type
# ------------------------------- -------- ----
# SAL_ID                          NOT NULL CHAR(3)
# SAL_DESC                        NOT NULL VARCHAR2(30)
# ID_TP                           NOT NULL CHAR(1)

# The salary is calculated from different Elements. These elements are 
# dynamically maintained in the database. This makes it much more difficult
# to print reports, but it makes it easier for the use of other NGOs

create table SAL_ID (
	SAL_ID INTEGER auto_increment PRIMARY KEY,
	SAL_DESC VARCHAR(30) NOT NULL,
	# Either "I" or "E". "I" raise salary, "E" lower salary
	ID_TP CHAR(1) NOT NULL
);

#
#SQL> describe TEMP_SAL
# Name                            Null?    Type
# ------------------------------- -------- ----
# EMP_ID3                                  CHAR(3)
# BASIC                                    NUMBER
# H_RENT                                   NUMBER
# M_ALLOW                                  NUMBER
# C_ALLOW                                  NUMBER
# O_ALLOW                                  NUMBER
# PF                                       NUMBER
# GAS                                      NUMBER
# RENT                                     NUMBER
# CUP                                      NUMBER
# CUFL                                     NUMBER
# W_FUND                                   NUMBER
# ELECT                                    NUMBER
# OTHER                                    NUMBER
# TAX                                      NUMBER
# ARREAR                                   NUMBER
# PF_LOAN                                  NUMBER

# This table is not used in the application but maybe we need it in the future ?

create table TEMP_SAL (
	EMP_ID3 CHAR(3),
	BASIC DOUBLE,
	H_RENT DOUBLE,
	M_ALLOW DOUBLE,
	C_ALLOW DOUBLE,
	O_ALLOW DOUBLE,
	PF DOUBLE,
	GAS DOUBLE,
	RENT DOUBLE,
	CUP DOUBLE,
	CUFL DOUBLE,
	W_FUND DOUBLE,
	ELECT DOUBLE,
	OTHER DOUBLE,
	TAX DOUBLE,
	ARREAR DOUBLE,
	PF_LOAN DOUBLE
);

#
#SQL> describe TRANS
# Name                            Null?    Type
# ------------------------------- -------- ----
# VR_NO                           NOT NULL CHAR(5)
# VR_DT                           NOT NULL DATE
# VR_TP                           NOT NULL CHAR(2)
# AC_ID1                          NOT NULL CHAR(2)
# AC_ID2                          NOT NULL CHAR(1)
# AC_ID3                          NOT NULL CHAR(3)
# AC_ID4                          NOT NULL CHAR(3)
# AC_ID5                          NOT NULL CHAR(3)
# DR_CR                                    CHAR(1)
# CHQ_NO                                   VARCHAR2(20)
# AMOUNT                                   NUMBER
# PARTY                                    VARCHAR2(25)
# REMARKS                                  VARCHAR2(40)
# TDATE                                    DATE
# DEPT                                     CHAR(3)

# Here (and only here) are all the financial Transactions stored. A Voucher is a combination
# of at least 2 Transactions (debit/credit). This is the reason, why VR_NO is no primary key. 

create table TRANS (
	VR_NO INTEGER NOT NULL,
	# Voucher-date is freely editable
	VR_DT DATE NOT NULL,
	# Three Voucher-Types are in use: DB (Debit-V), CR (Credit-V), and JV (Journal-V)
	VR_TP ENUM("DB","CR","JV") NOT NULL,
	# All these ID_Types are stored in the moment. maybe I should change that, because
	# AC_ID5 would be enough
	AC_ID1 INTEGER NOT NULL,
	AC_ID2 INTEGER NOT NULL,
	AC_ID3 INTEGER NOT NULL,
	AC_ID4 INTEGER NOT NULL,
	AC_ID5 INTEGER NOT NULL,
	# "D" or "C". D Raises an account, C lowers it.
	DR_CR ENUM("C","D") NOT NULL,
	# Cheque number ...
	CHQ_NO VARCHAR(20),
	# it is most easy in the moment, to just make an integer. For the future, I have to think
	# about it ... (double is stupid for currencies)
	AMOUNT Integer,
	# involved party ... Rcvd from or paid to ...
	PARTY VARCHAR(25),
	REMARKS VARCHAR(40),
	# not editable, date of creation of this voucher
	T_DT DATE,
	# See Dept-Table
	DEPT CHAR(3),
	KEY (VR_DT,VR_TP,DR_CR)
);
ALTER TABLE `TRANS` ADD INDEX(`AC_ID2`);
ALTER TABLE `TRANS` ADD INDEX(`AC_ID1`);

#
#SQL> describe WF_INFO
# Name                            Null?    Type
# ------------------------------- -------- ----
# EMP_ID4                                  CHAR(3)
# OPEN_DT                                  DATE
# STATUS                                   CHAR(1)
# EMP_OPBAL                                NUMBER
# EMP_OPINT                                NUMBER
# TOTCONT                                  NUMBER

# This table is not used in the application but maybe we need it in the future ?

create table WF_INFO (
	EMP_ID4 CHAR(3),
	OPEN_DT DATE,
	STATUS CHAR(1),
	EMP_OPBAL DOUBLE,
	EMP_OPINT DOUBLE,
	TOTCONT DOUBLE
);

#
#SQL> describe ADV_TEMP
# Name                            Null?    Type
# ------------------------------- -------- ----
# AC_ID1                                   CHAR(2)
# AC_ID2                                   CHAR(1)
# AC_ID3                                   CHAR(3)
# AC_ID4                                   CHAR(3)
# AC_ID5                                   CHAR(3)
# DR_BAL                                   NUMBER

# This table is not used in the application but maybe we need it in the future ?

create table ADV_TEMP (
	AC_ID1 CHAR(2),
	AC_ID2 CHAR(1),
	AC_ID3 CHAR(3),
	AC_ID4 CHAR(3),
	AC_ID5 CHAR(3),
	DR_BAL DOUBLE
);

#
#SQL> spool off
