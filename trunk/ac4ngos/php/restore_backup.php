<?
session_start();

require("accrp.php");
require("security/secure.php");
beginDocument("restore_backup", $sess_user);

pt_register('POST','submit');
if ($dbdumpfile) {
	if ($_FILES['dbdumpfile']['type'] != "application/x-tgz")
		die("Datadump must be a tar-gz-file !");
	
	$db = getDBConnection();
	deldir("/tmp/ac4ngosdata/");
	# 0333 does not work properly :-( so chmod ...

	$filename=$_FILES['dbdumpfile']['tmp_name'];

	move_uploaded_file($filename, "/tmp/ac4ngosimport.tar.gz") || die("Something wrong with the uploaded file ...");
	exec("cd /tmp; tar -xzf ac4ngosimport.tar.gz;chmod -R o+r ac4ngosdata");
		
	$result = mysql_query("SHOW TABLES FROM $DBNAME");
	checkMySQLError();

	while ($row = mysql_fetch_array($result,$db)) {
		$tablename = $row["Tables_in_$DBNAME"];
		$query = "DELETE FROM $tablename";
		$result2 = mysql_query($query,$db);
		checkMySQLError($query);
		$filename = "/tmp/accrpdata/" . $tablename.".txt";
		if (file_exists($filename)) {
			$query = "LOAD DATA INFILE '$filename' INTO TABLE `$tablename`";
			$result2 = mysql_query($query,$db);
			checkMySQLError($query);
		}
	}
	deldir("/tmp/ac4ngosdata/");
	die("finished");
}
else {
	beginPrettyTable(2,"Restore Backup");
	 openForm("uploadfile","$PHP_SELF","form enctype=\"multipart/form-data\" accept\"application/x-tgz\"");
	  makeHiddenField ("MAX_FILE_SIZE","30000");
	  echo "Send this file: <input name=\"dbdumpfile\" type=\"file\">";
	  makePlainSpecialSubmitter("Send File");
	 closeForm();
	endPrettyTable();


}
	endDocument(); 

?>
