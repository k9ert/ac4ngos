<?
# Show Profile shows all the Customer Info, Invoices, Payments, Tickets,
# and a balance.

require("accrp.php");
session_start();
require("security/secure.php");

beginDocument($lCustomerProfile, $sess_user);

$db = getDBConnection();

	if (!$showac) 
		$showac=1;
	
	$lastditch_result = mysql_query("select * from AC_CODE$showac order by AC_ID$showac", $db);
	
	checkMySQLError();
	if ($lastditch_row = mysql_fetch_array($lastditch_result)) {
		beginPrettyTable("4", "AC_CODE$showac");
		beginBorderedTable("4");
		echo ("<tr>\n");
		echo (" <td><b>AC_ID</b></td> <td><b>AC_DESC</b></td> \n <td><b>$lActions</b></td>\n </tr>\n");
		do {
			if($class == "odd") { $class = "even"; } else { $class = "odd"; }
			printf(" <tr class='$class'>\n  <td><a href='%s?CustomerID=%s'>%s</td> <td>%s</td>\n <td align='center'><a href='confirm.php?action=deletecustomer&CustomerID=%s'><img src='images/delete.gif' height=24 width=24 border=0 alt='Delete this Customer'></a></td></tr>\n", $PHP_SELF, $lastditch_row["AC_ID1"], $lastditch_row["AC_ID$showac"], $lastditch_row["AC${showac}_DESC"], "empty", $lastditch_row["AC_ID1"]); 
		} while ($lastditch_row = mysql_fetch_array($lastditch_result));
		endPrettyTable();
		endBorderedTable();
	} else {
		echo ("$lNoProfilesFound...\n"); 
	}

endDocument();
?>
