<?
# Show Profile shows all the Customer Info, Invoices, Payments, Tickets,
# and a balance.

require("accrp.php");
session_start();
require("security/secure.php");

beginDocument($lCustomerProfile, $sess_user);

$db = getDBConnection();

$result = mysql_query("select * from SAL_ID", $db);
checkMySQLError();
beginPrettyTable("4", "Salary Elements");
printRow(array("SAL_ID","Description","Income/Expense"));

while ($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
	printRow($row,"something");
}
endPrettyTable();
endDocument();
?>
