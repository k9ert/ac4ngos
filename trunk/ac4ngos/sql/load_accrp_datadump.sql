use ac4ngos;
LOAD DATA LOCAL INFILE 'accrpdump/AC_CODE1_1.TXT' INTO TABLE `AC_CODE1` FIELDS TERMINATED BY '\t' ESCAPED BY '\\' LINES TERMINATED BY '\n';

LOAD DATA LOCAL INFILE 'accrpdump/AC_CODE2_2.TXT' INTO TABLE `AC_CODE2` FIELDS TERMINATED BY '\t' ESCAPED BY '\\' LINES TERMINATED BY '\n';

LOAD DATA LOCAL INFILE 'accrpdump/AC_CODE3_3.TXT' INTO TABLE `AC_CODE3` FIELDS TERMINATED BY '\t' ESCAPED BY '\\' LINES TERMINATED BY '\n';

LOAD DATA LOCAL INFILE 'accrpdump/AC_CODE4_4.TXT' INTO TABLE `AC_CODE4` FIELDS TERMINATED BY '\t' ESCAPED BY '\\' LINES TERMINATED BY '\n';

LOAD DATA LOCAL INFILE 'accrpdump/AC_CODE5_5.TXT' INTO TABLE `AC_CODE5` FIELDS TERMINATED BY '\t' ESCAPED BY '\\' LINES TERMINATED BY '\n';

LOAD DATA LOCAL INFILE 'accrpdump/CUFL_INFO_7.TXT' INTO TABLE `CUFL_INFO` FIELDS TERMINATED BY '\t' ESCAPED BY '\\' LINES TERMINATED BY '\n';

LOAD DATA LOCAL INFILE 'accrpdump/CUF_LOAN_8.TXT' INTO TABLE `CUF_LOAN` FIELDS TERMINATED BY '\t' ESCAPED BY '\\' LINES TERMINATED BY '\n';

LOAD DATA LOCAL INFILE 'accrpdump/DEPT_9.TXT' INTO TABLE `DEPT` FIELDS TERMINATED BY '\t' ESCAPED BY '\\' LINES TERMINATED BY '\n';

LOAD DATA LOCAL INFILE 'accrpdump/DESIG_10.TXT' INTO TABLE `DESIG` FIELDS TERMINATED BY '\t' ESCAPED BY '\\' LINES TERMINATED BY '\n';

LOAD DATA LOCAL INFILE 'accrpdump/EMP_SAL_11.TXT' INTO TABLE `EMP_SAL` FIELDS TERMINATED BY '\t' ESCAPED BY '\\' LINES TERMINATED BY '\n';

LOAD DATA LOCAL INFILE 'accrpdump/PERSONAL_12.TXT' INTO TABLE `PERSONAL` FIELDS TERMINATED BY '\t' ESCAPED BY '\\' LINES TERMINATED BY '\n';

LOAD DATA LOCAL INFILE 'accrpdump/PF_INFO_13.TXT' INTO TABLE `PF_INFO` FIELDS TERMINATED BY '\t' ESCAPED BY '\\' LINES TERMINATED BY '\n';

LOAD DATA LOCAL INFILE 'accrpdump/PF_LOAN_14.TXT' INTO TABLE `PF_LOAN` FIELDS TERMINATED BY '\t' ESCAPED BY '\\' LINES TERMINATED BY '\n';

LOAD DATA LOCAL INFILE 'accrpdump/SAL_ID_15.TXT' INTO TABLE `SAL_ID` FIELDS TERMINATED BY '\t' ESCAPED BY '\\' LINES TERMINATED BY '\n';

LOAD DATA LOCAL INFILE 'accrpdump/TEMP_SAL_16.TXT' INTO TABLE `TEMP_SAL` FIELDS TERMINATED BY '\t' ESCAPED BY '\\' LINES TERMINATED BY '\n';

#LOAD DATA LOCAL INFILE 'accrpdump/TRANS_17.TXT' INTO TABLE `TRANS` FIELDS TERMINATED BY '\t' ESCAPED BY '\\' LINES TERMINATED BY '\n';