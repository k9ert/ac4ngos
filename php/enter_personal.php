<?
# enter_personal.php displays a form that allows the entry of a personal and
# handles the submission
session_start();

require("accrp.php");
require("security/secure.php");

beginDocument("Enter personal", $sess_user);

if ($desig_id) {
	$db = getDBConnection();
	
	# convert dates
	if ($conf_dt=="")
		$conf_dt = "0000-00-00";
	else
		$conf_dt = conv_to_srd($conf_dt);
	if (!isSet($join_dt) || $join_dt=="")
		$join_dt = "0000-00-00";
	else
		$join_dt = conv_to_srd($join_dt);
	
	if ($emp_id3) {
		$query = "UPDATE PERSONAL SET EMP_NAME='$emp_name', DESIG_ID=$desig_id, STATUS='$status', AC_NO='$ac_no', CONF_DT='$conf_dt', JOIN_DT='$join_dt' WHERE EMP_ID3=$emp_id3";
		echo $query;
		$result = mysql_query($query,$db);
		checkMySQLError(); 
		mysqlReport($result, "Employer sucessfully updated", "main.php", "Home");
		mysql_close($db);

	} else {
		$query = "INSERT INTO PERSONAL (EMP_NAME, DESIG_ID, STATUS, AC_NO, CONF_DT,JOIN_DT) VALUES ('$emp_name','$desig_id','$status', '$ac_no', '$conf_dt','$join_dt')";
		echo $query;
		$result = mysql_query($query, $db);
		checkMySQLError();
		mysqlReport($result, "Employer sucessfully added", "main.php", "Home");
		mysql_close($db);
	}
} else {
	
	$db = getDBConnection();
	
	if ($EMP_ID3) {
		$result = mysql_query("select * from PERSONAL where EMP_ID3 = $EMP_ID3");
		checkMySQLError();
		$edit = mysql_fetch_array($result);
	}
	$desig_array = get_designation_array();
	
	?> 
	<table cellpadding=5 cellspacing=0 border=0 width='100%'>
	 <tr>
	  <td valign=top width='15%'>
	
           <? beginPrettyTable("1"); ?>
	   <tr>
	    <? echo "<td><a href='list_personal.php'>show List</a></td>"; ?>
	   </tr>
	   <tr>
	    <td><hr color='black'></td>
	   </tr>
	   <? endPrettyTable();?>
	  </td>
	  <td valign=top align=center width='65%'>
	     <?

	openForm("enter Personal", $PHP_SELF);
	beginPrettyTable("2", "enter Personal");
	  makeHiddenField("emp_id3", $edit["EMP_ID3"]);
	  makeTextField("emp_name",$edit["EMP_NAME"], "Employer Name");
	  makeDropBox("desig_id", $desig_array, "Designation", $edit["DESIG_ID"]);
	  makeDropBox("status", array("Y" => "Y", "N" => "N", "UNKNOWN"  => ""), "Status", $edit["STATUS"]);
	  makeTextField("ac_no",$edit["AC_NO"], "Account Number");
	  makeTextField("conf_dt", $edit["CONF_DT"], "Conf_Date");
	  makeTextField("join_dt", $edit["JOIN_DT"], "Join Date");
	  makeSubmitter();
	endPrettyTable();
	closeForm();
	
	?>
	</td>
	  </table>
	  <?
	
}
endDocument();
?>
