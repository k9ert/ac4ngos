<?
# Show Profile shows all the Customer Info, Invoices, Payments, Tickets,
# and a balance.

require("accrp.php");
session_start();
require("security/secure.php");

beginDocument($lCustomerProfile, $sess_user);

$db = getDBConnection();

$desig_array = get_designation_array();
if (!isset($orderby)) $orderby="EMP_ID3";

# calculate received money by Employees
$result = mysql_query("select SUM(AMOUNT),AC_ID5 from TRANS WHERE AC_ID3=30 AND DR_CR='C' GROUP BY AC_ID5 order by PARTY", $db);
checkMySQLError();

$receive_array=array();
while($row = mysql_fetch_array($result)) {
	$receive_array[$row["AC_ID5"]] = $row["SUM(AMOUNT)"];
}

# calculate paid_back money
$result = mysql_query("select SUM(AMOUNT),PARTY, AC_ID5 from TRANS WHERE AC_ID3=30 AND DR_CR='D' GROUP BY PARTY order by PARTY", $db);
checkMySQLError();
while($row = mysql_fetch_array($result)) {
	if(array_key_exists($row["AC_ID5"],$receive_array))
		$receive_array[$row["AC_ID5"]] -= $row["SUM(AMOUNT)"];
}


beginPrettyTable("1", "Schedule of Advance as on ". get_today_hrd_string());
if (isSet($receive_array)) {
	printRow(array("Code","Employer's name", "Amount"));
	foreach ($receive_array as $key => $value) {
		if ($value!=0)
			printRow(array("$key", get_ac5_desc_of($key), 0-$value),"fluct");
		$sum += 0-$value;
	}
	printRow(array("","Sum",$sum));
} else 
	printRow(array("No Advance-money at anybody!"),"");
endPrettyTable();

endDocument();
?>
