<?
# Show Profile shows all the Customer Info, Invoices, Payments, Tickets,
# and a balance.
session_start();

require("accrp.php");
require("security/secure.php");

beginDocument($lCustomerProfile, $sess_user);

$db = getDBConnection();

#$result = mysql_query("select * from SAL_ID", $db);
#checkMySQLError();
beginPrettyTable("4", "Salary Elements");
#printRow(array("SAL_ID","Description","Income/Expense"));


$result = mysql_query("DROP TABLE test");
checkMySQLError();

$result = mysql_query("CREATE TABLE test (dummy int) TYPE=HEAP SELECT PERSONAL.EMP_ID3, EMP_NAME, SAL_ID FROM PERSONAL, SAL_ID");
checkMySQLError();

$query = "select * from test LEFT OUTER JOIN EMP_SAL USING(EMP_ID3, SAL_ID) ORDER BY test.EMP_ID3, test.SAL_ID";
$result = mysql_query($query, $db);
checkMySQLError();

while ($row = mysql_fetch_array($result)) {
	unset($printrow);
	$printrow["Name"]=$row["EMP_NAME"];
	for ($i=0; $i<14;$i++,$row = mysql_fetch_array($result))
		$printrow[]=$row["SAL_AMT"];
	printRow($printrow);

}
endPrettyTable();
endDocument();
?>
