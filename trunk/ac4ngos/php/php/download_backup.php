<?
session_start();

require("accrp.php");
require("security/secure.php");
beginDocument("Main", $sess_user);

$db = getDBConnection();
beginPrettyTable("2", "Backup");
deldir("/tmp/accrpexport/");
mkdir("/tmp/accrpexport",0333);
# 0333 does not work properly :-( so chmod ...
chmod("/tmp/accrpexport",0733);

$result = mysql_query("SHOW TABLES FROM $DBNAME");
checkMySQLError();

while ($row = mysql_fetch_array($result,$db)) {
	$tablename = $row["Tables_in_accrp"];
	$filename = "/tmp/accrpexport/" . $tablename.".txt";
	$query = "SELECT * FROM $tablename INTO OUTFILE \"$filename\"";
	$result2 = mysql_query($query,$db);
	checkMySQLError($query);
}
$today = get_today_srd_string();
$backup_filename = "accrp-datadump-$today.tar.gz";
exec("tar -czf /tmp/accrpexport/$backup_filename /tmp/accrpexport/*");
$doc_root = $_SERVER["DOCUMENT_ROOT"];
copy("/tmp/accrpexport/$backup_filename", "$doc_root/crp/backups/$backup_filename");
echo "<a href=\"backups/$backup_filename\"> click here to download datadump</a>";

endDocument(); 

?>
