<?
# print_money_receipt displays a money receipt, which could get printed out via the Browsers Print-button
session_start();

require("accrp.php");
require("security/secure.php");

beginDocument_noHead("Enter Salary Element", $sess_user);

if ($vr_no) {
	$db = getDBConnection();
	$result = mysql_query("SELECT * FROM TRANS WHERE VR_NO=$vr_no AND DR_CR='D'", $db);
	checkMySQLError();
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	if (mysql_fetch_array($result,MYSQL_ASSOC))
		die("more than one Voucher found! Database-integrity is in Danger! Call your System-maintainer!");
	if ($row["VR_TP"]!="CR")
		die("The given Voucher-No is not a CreditVoucher !");
	

	for ($i=0;$i<2;$i++) {
	?>
	<div align="center">
	<h1>Centre For The Rehabilitation Of The Paralysed (CRP)</h1>
	crp-Chapain, Savar, Dhaka-1343<br>
	Tel:7710464-5, Fax:7710069<br>
	Email: info@crp-bangladesh.org<br>
	<h2>MONEY RECEIPT</h2>
	</div>
	<div align="right">Date: <?echo get_today_hrd_string();?> </div>

	<div align="left"> <? echo $row["VR_NO"]; ?> </div>
	
	<div align="center"> Received with thanks an amount of Taka <? echo $row["AMOUNT"]; ?><br>
	( in words: <? echo get_number_wording($row["AMOUNT"]); ?> only) <br>
	<div align="left">from: <? echo $row["PARTY"]; ?> </div>
	<div align="right">in CASH/CHEQUE/DD/P.O no</div>
	<div align="center">against <? echo $row["REMARKS"]; ?> </div>
	<div align="left">Accountant</div>
	<div align="right">Cashier</div>

	<hr>
	<? }
	
	
	
	
	
	mysql_close($db);
} else { 
	
		?> 
	<table cellpadding=5 cellspacing=0 border=0 width='100%'>
	 <tr>
	  <td valign=top width='15%'>
	
           <? beginPrettyTable("1"); ?>
	   <tr>
	    <? echo "<td><a href='list_salary_elements.php'>show List</a></td>"; ?>
	   </tr>
	   <tr>
	    <td><hr color='black'></td>
	   </tr>
	   <? endPrettyTable();?>
	  </td>
	  <td valign=top align=center width='65%'>
	     <?

	openForm("moneyreceipt_form", $PHP_SELF);
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
