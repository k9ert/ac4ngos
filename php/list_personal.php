<?
# List all Employees

require("accrp.php");
session_start();
require("security/secure.php");

beginDocument($lCustomerProfile, $sess_user);


$desig_array = get_designation_array();
if (!isSet($orderby)) $orderby="EMP_ID3";

$db = getDBConnection();
$result = mysql_query("select * from PERSONAL order by $orderby", $db);
checkMySQLError();
beginPrettyTable("5", "PERSONAL");

#we have the following columns
$columns=array("EMP_ID3"	=>"EMP_ID3",
		"EMP_NAME"	=>"EMP_NAME",
		"DESIG_ID"	=>"DESIG_ID",
		"STATUS"	=>"STATUS",
		"AC_NO"		=>"AC_NO",
		"CONF_DT"	=>"CONF_DT",
		"JOIN_DT"	=>"JOIN_DT");
# now a very fancy piece of code :-)
$columns[$orderby].=" DESC";
# wasn't it fancy ;-)
# now something not very fancy :-(
$id=$columns["EMP_ID3"];
$name=$columns["EMP_NAME"];
$desig=$columns["DESIG_ID"];
$status=$columns["STATUS"];
$conf_dt=$columns["CONF_DT"];
$join_dt=$columns["JOIN_DT"];
$ac_no=$columns["AC_NO"];

printRow(array("<b><a href='$PHP_SELF?orderby=$id'>EMP_ID3</a></b>",
		"<b><a href='$PHP_SELF?orderby=$name'>EMP_NAME</a></b>",
		"<b><a href='$PHP_SELF?orderby=$desig'>DESIGNATION</a></b>",
		"<b><a href='$PHP_SELF?orderby=$status'>STATUS</a></b>",
		"<b><a href='$PHP_SELF?orderby=$ac_no'>AC_NO</a></b>",
		"<b><a href='$PHP_SELF?orderby=$conf_dt'>CONF_DT</a></b>",
		"<b><a href='$PHP_SELF?orderby=$join_dt'>JOIN_DT</a></b>")
		);

while ($row = mysql_fetch_array($result)) {
	printRow(array("<a href=enter_personal.php?EMP_ID3='".$row["EMP_ID3"]."'>".$row["EMP_ID3"]."</a>",
		$row["EMP_NAME"],
		$desig_array[$row["DESIG_ID"]],
		$row["STATUS"],
		$row["AC_NO"],
		$row["CONF_DT"],
		$row["JOIN_DT"]),
		"fluct");
} 
endPrettyTable();
endBorderedTable();


endDocument();
?>
