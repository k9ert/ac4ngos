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
$result = mysql_query("select * from AC_CODE1 order by AC_ID1", $db);
checkMySQLError();

echo "<table border=0>";
while ($row = mysql_fetch_array($result)) {
	$ac1_desc=$row["AC1_DESC"];
	$ac1=$row["AC_ID1"];
	$result2 = mysql_query("select * from AC_CODE2 order by AC_ID2", $db);
	checkMySQLError();
	while ($row2 = mysql_fetch_array($result2)) {
		$ac2_desc=$row2["AC2_DESC"];
		$ac2=$row2["AC_ID2"];
		$result3 = mysql_query("select * from AC_CODE3 order by AC_ID3", $db);
		checkMySQLError();
		while ($row3 = mysql_fetch_array($result3)) {
			$ac3_desc=$row3["AC3_DESC"];
			$ac3=$row3["AC_ID3"];
			$result4 = mysql_query("select * from AC_CODE4 order by AC_ID4", $db);
			checkMySQLError();
			while ($row4 = mysql_fetch_array($result4)) {
				$ac4_desc=$row4["AC4_DESC"];
				$ac4=$row4["AC_ID4"];
				$result5 = mysql_query("select * from AC_CODE5 where AC_ID1=$ac1 AND AC_ID2=$ac2 AND AC_ID3=$ac3 AND AC_ID4=$ac4 order by AC_ID5", $db);
				checkMySQLError();
				while ($row5 = mysql_fetch_array($result5)) {
					if ($h1_flag!=1) {
						echo "<H1 style=\"text-indent:1cm;\">$ac1_desc</H1>";
						$h1_flag=1;
					}
					if ($h2_flag!=1) {
						echo "<H2 style=\"text-indent:2cm;\">$ac2_desc</H2>";
						$h2_flag=1;
					}
					if ($h3_flag!=1) {
						echo "<H3 style=\"text-indent:3cm;\">$ac3_desc</H3>";
						$h3_flag=1;
					}
					if ($h4_flag!=1) {
						echo "<H4 style=\"text-indent:4cm;\">$ac4_desc</H4>";
						$h4_flag=1;
					}

					$ac5_desc=$row5["AC5_DESC"];
					$ac5=$row5["AC_ID5"];
					echo "$temp: <H5 style=\"text-indent:5cm;\">$ac5_desc</H5>";
					$temp++;
				}
				$h4_flag=0;
			}
			$h3_flag=0;
		}
		$h2_flag=0;
	}
	$h1_flag=0;
}

echo "</table>";

endDocument();
?>
