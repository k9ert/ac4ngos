<?
# Show Profile shows all the Customer Info, Invoices, Payments, Tickets,
# and a balance.

require("accrp.php");
session_start();
require("security/secure.php");

beginDocument($lCustomerProfile, $sess_user);

$db = getDBConnection();


	$result = mysql_query("select * from DESIG order by DESIG_ID", $db);
	
	checkMySQLError();
	if ($result_row = mysql_fetch_array($result)) {
		beginPrettyTable("4", "DESIGNATIONS");
		beginBorderedTable("4");
		echo ("<tr>\n");
		echo (" <td><b>DESIG_ID</b></td> <td><b>DESIG_DESC</b></td> \n <td><b>$lActions</b></td>\n </tr>\n");
		do {
			if($class == "odd") { $class = "even"; } else { $class = "odd"; }
			printf(" <tr class='$class'>\n  <td><a href='%s?CustomerID=%s'>%s</td> <td>%s</td>\n <td align='center'><a href='confirm.php?action=deletecustomer&CustomerID=%s'><img src='images/delete.gif' height=24 width=24 border=0 alt='Delete this Customer'></a></td></tr>\n", $PHP_SELF, $result_row["DESIG_ID"], $result_row["DESIG_ID"],$result_row["DESIG_DESC"], $result_row["DESIG_ID"]); 
		} while ($result_row = mysql_fetch_array($result));
		endPrettyTable();
		endBorderedTable();
	} else {
		echo ("No Designations Found...\n"); 
	}

endDocument();
?>
