<?
session_start();

require("accrp.php");
require("security/secure.php");
beginDocument("Main", $sess_user);

$db = getDBConnection();
beginPrettyTable("2", "Backup");
deldir("/tmp/ac4ngosdata/");
mkdir("/tmp/ac4ngosdata",0333);
# 0333 does not work properly :-( so chmod ...
chmod("/tmp/ac4ngosdata",0733);

$result = mysql_query("SHOW TABLES FROM $DBNAME");
checkMySQLError();

while ($row = mysql_fetch_array($result,$db)) {
	$tablename = $row["Tables_in_$DBNAME"];
	$filename = "/tmp/ac4ngosdata/" . $tablename.".txt";
	$query = "SELECT * FROM $tablename INTO OUTFILE \"$filename\"";
	$result2 = mysql_query($query,$db);
	checkMySQLError($query);
}
$today = get_today_srd_string();
$backup_filename = "ac4ngos-datadump-$today.tar.gz";
exec("cd /tmp; tar -czf $backup_filename ac4ngos/*");
$doc_root = $_SERVER["DOCUMENT_ROOT"];
copy("/tmp/$backup_filename", "$doc_root/crp/backups/$backup_filename");
echo "<a href=\"backups/$backup_filename\"> click here to download datadump</a>";
endPrettyTable();
beginPrettyTable("2", "the following datadumps are available as well:");

$handle = opendir("$doc_root/crp/backups/");
while (false !== ($filename = readdir ($handle))) {
	if(is_file("$doc_root/crp/backups/$filename"))
		printRow(array("<a href=\"backups/$filename\">$filename</a>"),"fluct");
}
closedir($handle);

## This is easier but it works only for > PHP 4.3.0
#chdir("$doc_root/crp/backups/");
#foreach (glob("*") as $filename) {
#	printRow(array("<a href=\"backups/$filename\">$filename</a>"),"fluct");
#}

endPrettyTable();

endDocument(); 

?>
