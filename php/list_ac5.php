<?
# Show Profile shows all the Customer Info, Invoices, Payments, Tickets,
# and a balance.

require("accrp.php");
session_start();
require("security/secure.php");


beginDocument($lCustomerProfile, $sess_user);

$db = getDBConnection();

	$ac1_array=get_ac_array(1);
	$ac2_array=get_ac_array(2);
	$ac3_array=get_ac_array(3);
	$ac4_array=get_ac_array(4);
	if (!isset($orderby)) $orderby="AC_ID5";
	$lastditch_result = mysql_query("select * from AC_CODE5 order by $orderby", $db);
	checkMySQLError();
	if ($lastditch_row = mysql_fetch_array($lastditch_result)) {
		beginPrettyTable("4", "AC_CODE5");
		beginBorderedTable("4");
		?>
		<tr>
		
		
		<td><b><a href='<?echo "$PHP_SELF"?>?orderby=AC_ID5<?if ($orderby=="AC_ID5") echo " DESC" ?>'>AC_ID5</a></b></td> 
		<td><b><a href='<?echo "$PHP_SELF"?>?orderby=AC_ID1<?if ($orderby=="AC_ID1 DESC") echo " DESC" ?>'>AC1_DESC</a></b></td> 
		<td><b><a href='<?echo "$PHP_SELF"?>?orderby=AC_ID2<?if ($orderby=="AC_ID2 DESC") echo " DESC" ?>'>AC2_DESC</a></b></td>
		<td><b>AC3_DESC</b></td>
		<td><b>AC4_DESC</b></td>
		<td><b>AC5_DESC</b></td>
		
		</tr>

		<?
		do {
			if($class == "odd") { $class = "even"; } else { $class = "odd"; }
			printf(" <tr class='$class'>\n  <td><a href='%s?CustomerID=%s'>%s</a></td> <td>%s</td>\n<td>%s</td> <td>%s</td>  <td>%s</td><td>%s</td> </td></tr>\n", $PHP_SELF, $lastditch_row["AC_ID5"], $lastditch_row["AC_ID5"], $ac1_array[(int)$lastditch_row["AC_ID1"]], $ac2_array[(int)$lastditch_row["AC_ID2"]],$ac3_array[(int)$lastditch_row["AC_ID3"]],$ac4_array[(int)$lastditch_row["AC_ID4"]], $lastditch_row["AC5_DESC"]); 
		} while ($lastditch_row = mysql_fetch_array($lastditch_result));
		endPrettyTable();
		endBorderedTable();
	} else {
		echo ("$lNoProfilesFound...\n"); 
	}

endDocument();
?>
