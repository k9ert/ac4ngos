<?
# List all Employees

session_start();

require("accrp.php");
require("security/secure.php");

beginDocument($lCustomerProfile, $sess_user);

$db = getDBConnection();

$sal_el_array = get_salary_elements_array();
reset($sal_el_array);
$personal_array = get_personal_array();

beginPrettyTable("5", "PERSONAL SALARY");

#$header["EMP_ID3"] = "ID";
#$header["EMP_NAME"] = "NAME";

$query = "select * FROM PERSONAL LEFT JOIN EMP_SAL USING (EMP_ID3) ORDER BY EMP_SAL.EMP_ID3, EMP_SAL.SAL_ID";
$result = mysql_query($query, $db);

while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
	if (!(isSet($emp_name))) 
		$emp_name=$row["EMP_NAME"];	
		
	
	# ToDo: should get traeted as well
	if ($row["SAL_ID"]==NULL) {
		continue;
		for ($i=0;$i<14;$i++) $printrow[]="0";
		continue;
	}

	if (!(isSet($emp_name))) 
		$emp_name=$row["EMP_NAME"];


	if (isSet($emp_name) && $row["EMP_NAME"] != $emp_name) {
		$emp_name = $row["EMP_NAME"];
		printRow(array("counter ist $counter"));
		$counter = 0;
		while (sizeof($printrow) <= 15)
			$printrow[] = "0";
		printRow($printrow); 
		reset($sal_el_array); 
		unSet($printrow);
		$printrow[] = $row["EMP_ID3"];
		$printrow[] = $row["EMP_NAME"];
	}
	## perhaps should be here ?
	#if ($row["SAL_ID"]==NULL) {
	#	for ($i=0;$i<14;$i++) $printrow[]="0";
	#	continue;
	#}
	#printrow(array("name is:".$emp_name));
	#printrow(array("key vom array is ".key($sal_el_array)."und row sal_id is".$row["SAL_ID"]));
	while (!is_bool(next($sal_el_array)) && key($sal_el_array) < $row["SAL_ID"] ) {
		printRow(array("inside loop :$current<br>"));
		$printrow[] = "0";
		#if (!next($sal_el_array))
		#	break;
		if (key($sal_el_array) == $row["SAL_ID"])
			break;
		
	}
	#next($sal_el_array);
	$printrow[] = $row["SAL_AMT"];
}

endPrettyTable();
endBorderedTable();


endDocument();
?>
