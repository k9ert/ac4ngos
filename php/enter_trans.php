<?
# enter_personal.php displays a form that allows the entry of a personal and
# handles the submission

require("accrp.php");
session_start();
require("security/secure.php");

pt_register('POST','ac_count','submitnow','vr_tp','ac_name','t_dt','dr_cr','amount','vr_no');
pt_register('POST','vr_dt','party','chq_no','remarks');
#TODO the Counterbookings-variables have to get access via the $_POST-Array

$javascript= <<<EOD
<script type="text/javascript">
<!--
function changeField() {
	alert("Hallo");
	document.loan_form.
}
//-->
</script>
EOD;

beginDocument("Enter Transaction", $sess_user,$javascript);

if ($submitnow=="1") {
	$db = getDBConnection();
	$ac5_array = get_ac5_array();
	# Get easily the different AC_Codes with AC_ID5
	# For this, AC_ID5 must be a primary key (no doubles)
	$ac_id1=$ac5_array[$ac_name][1];
	$ac_id2=$ac5_array[$ac_name][2];
	$ac_id3=$ac5_array[$ac_name][3];
	$ac_id4=$ac5_array[$ac_name][4];
	$vr_dt = conv_to_srd($vr_dt);
	$t_dt = get_today_srd_string();
	# First, we have to check, if everything is fine
	$sum=0;
	for ($i=0, $varname2= "ac_name_" . $i; isSet($_POST[$varname2]); $i++,$varname2= "ac_name_" . $i ) {
		$varname = "ac_name_" . $i;
		$ac_name_temp = $_POST[$varname];
		printrow(array("ac_name_temp is $ac_name_temp"));
		# 1. We need at least one "counter-booking"
		if ($ac_name_temp!=-1) $proceed_flag=1;
		# sum it up
		$varname = "amount_" . $i;
		$amount_temp = $_POST[$varname];
		# 2. sum of "counter-bookings" should be same as amount
		$sum+=$amount_temp;
		# 3. If a Amount is given, an Account must be chosen also
		if ($amount_temp==0 AND $ac_name_temp!=-1) die ("<h1>Error:</H1> At least one counterbooking have an amount of 0. This is not allowed! Please use the back-button of your browser and correct your voucher!");

	}
	if ($proceed_flag!=1) die ("Error:You have to make at least one  counter-booking, please use the back-button of your browser and correct your voucher!");
	if ($sum!=$amount) die ("Error: Your Voucher's amount is $amount but the sum of the counter-bookings is $sum! Please use the back-button of your browser and correct your voucher!");

	
	# This Locking-stuff is needed, to get a proper ID for vouchers. Because one voucher
	# consists of at least 2 entries, we can't use an autoincrement/primary key
	# To be sure, to get a unique number (and to make this application network-usable)
	# we have to lock the table 
	$personal_array = get_personal_array();
	$result = mysql_query("LOCK TABLES TRANS WRITE",$db);
	checkMySQLError();
	
	$result = mysql_query("SELECT MAX(VR_NO) FROM TRANS",$db);
	checkMySQLError();
	$row = mysql_fetch_array($result);
	$max_vr_no=$row["MAX(VR_NO)"];
	$max_vr_no++;
	
	if ($vr_tp=="AD" || $vr_tp=="AR") 
		$party = "CRP-Emp ID " . $party . " (" . $personal_array[$party] . ")";
	
	# insert the fields ...
	$result = mysql_query("INSERT INTO TRANS (VR_NO, VR_DT, VR_TP, AC_ID1, AC_ID2, AC_ID3, AC_ID4, AC_ID5,  DR_CR, CHQ_NO, AMOUNT, PARTY, REMARKS, T_DT, DEPT) VALUES ('$max_vr_no','$vr_dt','$vr_tp','$ac_id1','$ac_id2','$ac_id3','$ac_id4','$ac_name','$dr_cr','$chq_no','$amount','$party','$remarks','$t_dt','')", $db);
	checkMySQLError();

	# Now, the worst part of the story, inserting these "counter-billings"
	if ($dr_cr == "C") 
		$dr_cr = "D";
	elseif ($dr_cr == "D") 
		$dr_cr = "C"; 
	else 
		die("DR_CR unknown: $dr_cr");
	for ($i=0, $varname2= "ac_name_" . $i; isset($_POST[$varname2]); $i++,$varname2= "ac_name_" . $i ) {
		# We have to insert a row for each group of fields
		# We can use the $_POST-Array for that
		$varname = "ac_name_" . $i;
		$ac_name = $_POST[$varname]; 
		if ($ac_name == -1) continue;
		$ac_id1=$ac5_array[$ac_name][1];
		$ac_id2=$ac5_array[$ac_name][2];
		$ac_id3=$ac5_array[$ac_name][3];
		$ac_id4=$ac5_array[$ac_name][4];
		$varname = "remarks_" . $i;
		$remarks = $_POST[$varname];
		$varname = "dept_" . $i;
		$dept = $POST[$varname];
		if ($dept==-1) $dept="";
		$varname = "amount_" . $i;
		$amount = $_POST[$varname];
		# Would that not be much more easier ? You can make sums very easily ?!
		# Because I don't know about all the implications, I let it like that
		# $amount = -$amount;
		$result = mysql_query("INSERT INTO TRANS (VR_NO, VR_DT, VR_TP, AC_ID1, AC_ID2, AC_ID3, AC_ID4, AC_ID5,  DR_CR, CHQ_NO, AMOUNT, PARTY, REMARKS, T_DT, DEPT) VALUES ('$max_vr_no','$vr_dt','$vr_tp','$ac_id1','$ac_id2','$ac_id3','$ac_id4','$ac_name','$dr_cr','$chq_no','$amount','$party','$remarks','$t_dt','$dept')", $db);
		checkMySQLError();
	
	}

	$result = mysql_query("UNLOCK TABLES",$db);
	
	mysqlReport($result, "Transaction sucessfully added, Voucher Number is $max_vr_no", "print_money_receipt.php?vr_no=$max_vr_no", "printmoneyReceipt");
} else {
	if ($vr_tp=="DB") {
		$type_of_voucher="Debit ";
		$dr_cr = "C";
		$ac_array = get_ac5_sc_array("5(1)","B");
	} elseif ($vr_tp=="CR") {
		$type_of_voucher="Credit ";
		$dr_cr = "D";
		$ac_array = get_ac5_sc_array("5(1)","B");
	} elseif ($vr_tp=="JV") {
		$type_of_voucher="Journal ";
		$dr_cr = "C";
		$ac_array = get_ac5_sc_array("5(1)","B");
	## I hate the idea of generating an account for People who gets money in Advance
	## The following was a try for a workaround ... a bad try !
	#} elseif ($vr_tp=="AD") {
	#	$type_of_voucher="Advance (Debit) ";
	#	$dr_cr = "C";
	#	$ac_array = get_ac5_sc_array("5(1)","B");
	#} elseif ($vr_tp=="AR") {
	#	$type_of_voucher="Advance Refund (Credit) ";
	#	$dr_cr = "D";
	#	$ac_array = get_ac5_sc_array("5(1)","B");
	} else
		die("Unknown Voucher-type: ($vr_tp)");	
	
	$dept_array = get_dept_array();
	if (!isset($ac_count)) { $ac_count = 3 ;} 

	$today = get_today_hrd_string() ;
	?> 
	<table cellpadding=5 cellspacing=0 border=0 width='100%'>
	 <tr>
          <td valign=top align=center width='65%'>
	     <?

	openForm("transaction", $PHP_SELF);
	beginPrettyTable("2", "enter $type_of_voucher Voucher");
	  makeHiddenField("ac_count", "$ac_count");
	  makeHiddenField("submitnow","");
	  makeHiddenField("vr_tp",$vr_tp);
	  makePlainDropBox("ac_name", $ac_array, "Ac_Name");
	  makePlainStaticField("t_dt", $today,"         ", 10);
	  startRow();
	    makePlainStaticField("dr_cr", $dr_cr,"DR./CR", 2);
	    makePlainTextField("amount", "", "Amount:", 8);
	  endRow();
	  startRow();
	    makePlainStaticField("vr_no", "", "Vr.No.:", 10);
	    makePlainTextField("vr_dt", $today, "Vr.-Date:", 10);
	  endRow();
	  ## belongs to the bad try (see above)
	  #if ($vr_tp=="AD" || $vr_tp=="AR") {
	  #	$personal_array = get_personal_array();
	  #	makePlainDropBox("party", $personal_array, $dr_cr=="C" ? "Paid To" : "Rcvd. from");
	  #}
	  #else
	  
	  makePlainTextField("party", "", $dr_cr=="C" ? "Paid To" : "Rcvd. from");
	  if ($vr_tp!="JV")
	  	makePlainTextField("chq_no", "", "Chq-No.:",10);
	  makeTextField("remarks", "", "Narration:");
	endPrettyTable();
	# Start now with that second part of the story
	if ($vr_tp=="DB") 
		$ac_array = get_ac5_sc_array("5(1/2)","");
	elseif ($vr_tp=="CR") 
		$ac_array = get_ac5_sc_array("5(1/2)","");
	elseif ($vr_tp=="JV") 
		$ac_array = get_ac5_sc_array("5(1/2)","");
	## belongs to the bad try (see above)
	#elseif ($vr_tp=="AD") 
	#	$ac_array = get_ac5_sc_array("5(1/2)","D");
	#elseif ($vr_tp=="AR") 
	#	$ac_array = get_ac5_sc_array("5(1/2)","D");
	else
		die("Unknown Voucher-type: ($vr_tp)");
	
	beginPrettyTable("1", "I call it \"Counterbooking\"");
	?> <tr><td>AccountName</td><td>Narration</td><td>Dept</td><td>Amount</td></tr><?
	  for ($i=0; $i < $ac_count; $i++) {
	    startRow();
	     makePlainDropBox("ac_name_$i", $ac_array);
	     makePlainTextField("remarks_$i", "", "", 20);
	     makePlainDropBox("dept_$i", $dept_array, $edit["DEPT_ID"]);
	     makePlainTextField("amount_$i", "");
	    endRow();
	  }
	  $ac_count++;
	  makeSpecialSubmitter("moreRows", "this.form.ac_count.value=$ac_count");
	endPrettyTable();
	makeSpecialSubmitter("submit", "this.form.submitnow.value=\"1\"");

	closeForm();
	
	?>
	</td>
	  </table>
	  <?
	
}
endDocument();
?>
