<?
# lists all transactions on a specific Account 

require("accrp.php");
session_start();
require("security/secure.php");

pt_register('POST','ac_name','startdate','enddate');

beginDocument("list Ledger Sheet", $sess_user);

if ($ac_name) {
	print_ledger_sheet_head($startdate,$enddate,$ac_name);
	$startdate_srd = conv_to_srd($startdate);
	$enddate_srd = conv_to_srd($enddate);
	$db = getDBConnection();
	
	$result = mysql_query("SELECT VR_NO, VR_DT, VR_TP, REMARKS, AMOUNT, DR_CR FROM TRANS WHERE AC_ID5='$ac_name' AND VR_DT >= '$startdate_srd' AND VR_DT <= '$enddate_srd'", $db);
	checkMySQLError();

	beginPrettyTable("4", "From $startdate to $enddate<nbs>       Code$ac_name");
	printRow(array("opening Balance: " . get_opening_balance("AC_ID5=$ac_name",$startdate_srd)),"","1,3");
	printRow(array("<hr>"),"","1,6");
	printRow(array("VR No","VR Date","Vr Type","Particulars","Debit","Credit"));
	while ($row = mysql_fetch_array($result,MYSQL_ASSOC)) {
		$row["VR_DT"]=conv_to_hrd($row["VR_DT"]);
		if ($row["DR_CR"]=="D") {
			$row["DR_CR"]=$row["AMOUNT"];
			$row["AMOUNT"]="&nbsp;";
		}
		else
			$row["DR_CR"]="&nbsp;";
		printRow($row,"abwechselnd");
	}
	printRow(array("","","DebitSum:",get_debit_sum("AC_ID5=$ac_name",$enddate_srd,$startdate_srd)),"","1,2");
	printRow(array("","","CreditSum:",get_credit_sum("AC_ID5=$ac_name",$enddate_srd,$startdate_srd)),"","1,3");
	printRow(array("<hr>"),"","1,6");
	printRow(array("closing Balance: " . get_closing_balance("AC_ID5=$ac_name",$enddate_srd)),"","1,3");
	endPrettyTable();
	mysql_close($db);

} else { 
	$ac_array = get_ac5_sc_array("5(1/2)");
	?> 
	<table cellpadding=5 cellspacing=0 border=0 width='100%'>
	 <tr>
	  <td valign=top width='15%'>
	
           <? beginPrettyTable("1"); ?>
	   <tr>
	    <? echo "<td><a href='list_ledger_sheet.php'>Nothing here</a></td>"; ?>
	   </tr>
	   <tr>
	    <td><hr color='black'></td>
	   </tr>
	   <? endPrettyTable();?>
	  </td>
	  <td valign=top align=center width='65%'>
	   <? openForm("enter Date", $PHP_SELF);
	   beginPrettyTable("2", "enter AC_CODE$ac");
	    makeDropBox("ac_name", $ac_array, "Ac_Name");
	    makeTextField("startdate", get_today_hrd_string(), "Start Date:");
	    makeTextField("enddate", get_today_hrd_string(), "End Date:");
	    makeSubmitter();
	   endPrettyTable();
	   closeForm(); ?>
	   </td>
	  </table>
	  <?
	
}
endDocument();
?>
