<?
# enter_salary_element.php displays a form that allows the entry of a new salary element and
# handles the submission

require("accrp.php");
session_start();
require("security/secure.php");

pt_register('POST','remove_confirm','vr_no');

beginDocument("Enter Salary Element", $sess_user);

if ($remove_confirm==1) {
	$db = getDBConnection();
	$result = mysql_query("DELETE FROM TRANS WHERE VR_NO=$vr_no", $db);
	if (!checkMySQLError())
		mysqlReport($result, "VoucherNo $vr_no sucessfully removed", "main.php", "Home");
	mysql_close($db);

} else if ($vr_no) {
	$db = getDBConnection();
	$result = mysql_query("SELECT AC_ID1, AC_ID2, AC_ID3, AC_ID4, AC_ID5, VR_TP, VR_NO, VR_DT, PARTY, REMARKS, AMOUNT FROM TRANS WHERE VR_NO=$vr_no AND DR_CR='D'", $db);
	checkMySQLError();
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	if(!$row)
		die ("No Voucher with this Number found!");
	else if ($row["VR_TP"]=="CR")
		die ("Requested Voucher is a Credit-Voucher. Credit-Vouchers can't get deleted. Instead, make a Debit-Voucher and correct the mistake!");
	else if (mysql_fetch_array($result,MYSQL_ASSOC))
		die("more than one Voucher found! Database-integrity is in Danger! Call your System-maintainer!");
	beginPrettyTable("1","Do you want to Remove this Voucher ?");
	 printRow(array("AC_Codes","VR_TP","Vr_No","VR_DT","PARTY","NARRATION","AMOUNT"),"","1,5");
	 $row["VR_DT"] = conv_to_hrd($row["VR_DT"]);
	 printRow($row,"something");
	 openForm("removevoucher_confirm_form", $PHP_SELF);
	 makeHiddenField("remove_confirm",0);
	 makeHiddenField("vr_no",$vr_no);
	 makeSpecialSubmitter("yes, remove it!","this.form.remove_confirm.value=1",6);

	endPrettyTable();
	mysql_close($db);
} else { 
	
		?> 
	<table cellpadding=5 cellspacing=0 border=0 width='100%'>
	 <tr>
	  <td valign=top width='15%'>
	
           <? beginPrettyTable("1"); ?>
	   <tr>
	    <? echo "<td><a href='not_existing.php'>bad link here</a></td>"; ?>
	   </tr>
	   <tr>
	    <td><hr color='black'></td>
	   </tr>
	   <? endPrettyTable();?>
	  </td>
	  <td valign=top align=center width='65%'>
	     <?

	openForm("removevoucher_form", $PHP_SELF);
	beginPrettyTable("2", "enter Voucher-No");
	  maketextfield("vr_no", "", "Voucher Number");
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
