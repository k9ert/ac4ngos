<?
# Correct the stupid AC5-ID in the TRANS Table
# should only started once after loading datadump
session_start();

require("accrp.php");
require("security/secure.php");

beginDocument("Correcting AC5 in TRANS-Table", $sess_user);

$db = getDBConnection();
$result = mysql_query("SELECT * from `TRANS`", $db);
checkMySQLError();
while ($row = mysql_fetch_array($result, $db)) {
	$vr_dt = $row["VR_DT"];
	$vr_no = $row["VR_NO"];
	$ac1 = $row["AC_ID1"];
	$ac2 = $row["AC_ID2"];
	$ac3 = $row["AC_ID3"];
	$ac4 = $row["AC_ID4"];
	$new_ac5 = get_ac5($ac1,$ac2,$ac3,$ac4);
	$result2 = mysql_query("UPDATE TRANS SET AC_ID5='$new_ac5' WHERE VR_DT='$vr_dt' AND VR_NO='$vr_no'", $db);
	checkMySQLError();
	echo "updated new AC5 $new_ac5<br>";
}
echo "done it<br>";
endDocument();

