<?
# enter_personal.php displays a form that allows the entry of a personal and
# handles the submission
require("accrp.php");
session_start();
require("security/secure.php");

pt_register('POST','ac_count','submitnow','vr_no','t_dt','vr_dt','remarks','credit_sum','debit_sum');
# We have another problem here with registering:
# We don't know the names of the other variables :-(
#TODO: Registering them also

$javascript= <<<EOD
<script type="text/javascript">
<!--

function sumFields(dr_cr_content) {
	sumOfFields=0;
	numberOfFields = parseInt(document.transaction.ac_count.value);
	for(i=0;i<numberOfFields;i++) {
		fieldName = "dr_cr_" + i;
		if (document.transaction.elements[fieldName].value == dr_cr_content) {
			amountFieldName = "amount_" + i;
			amountFieldValue = parseInt(document.transaction.elements[amountFieldName].value)
			if (isNaN(amountFieldValue))
				amountFieldValue = 0;
			sumOfFields += amountFieldValue;
		}
	}
	return sumOfFields
}

function updateTotalFields() {
	document.transaction.debit_sum.value = sumFields("D");
	document.transaction.credit_sum.value = sumFields("C");
}

function copyNarrationField() {
	narrationFieldValue = document.transaction.remarks.value;
	numberOfFields = parseInt(document.transaction.ac_count.value);
	for(i=0;i<numberOfFields;i++) {
		fieldName = "remarks_" + i;
		document.transaction.elements[fieldName].value = narrationFieldValue
	}

}

function checkBeforeSubmit() {
	if (document.transaction.submitnow.value=="1") {
		totalDebit = parseInt(document.transaction.debit_sum.value);
		totalCredit = parseInt(document.transaction.credit_sum.value);
		if (totalDebit != totalCredit) {
			alert("Total Debit is not equal Total Credit !");
			return false;
		}
		if (totalDebit == 0) {
			alert("Sum of Bookings is zero ?!");
			return false;
		}
		numberOfFields = parseInt(document.transaction.ac_count.value);
		for(i=0;i<numberOfFields;i++) {
			fieldName = "dr_cr_" + i;
			dr_cr_field_value = document.transaction.elements[fieldName].value;
			if (dr_cr_field_value != "") {
				if (dr_cr_field_value != "D" && dr_cr_field_value != "C") {
					alert("For DR_CR, only C or D is allowed!");
					return false;
				}
				amountFieldName = "amount_" + i;
				amountFieldValue = parseInt(document.transaction.elements[amountFieldName].value);
				if (amountFieldValue == 0 || isNaN(amountFieldValue)) {
					alert("One Booking is zero or not a number ?");
					return false
				}
				acnameFieldName = "ac_name_"+i;
				if (document.transaction.elements[acnameFieldName].value == -1) {
					alert("At least one Booking does not define an account");
					return false;
				}
			}
		}

	}
	return true
}

function moreRows(numberOfRows) {
	document.transaction.ac_count.value=numberOfRows;
	document.transaction.submitnow.value="";
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
	
	for ($i=0, $varname2= "ac_name_" . $i; isset($$varname2); $i++,$varname2= "ac_name_" . $i ) {
		# We have to insert a row for each group of fields
		# We have to use variable Variables
		$varname = "ac_name_" . $i;
		$ac_name = $$varname; # the content of varname is used as a Variablename
		if ($ac_name == -1) continue;
		$ac_id1=$ac5_array[$ac_name][1];
		$ac_id2=$ac5_array[$ac_name][2];
		$ac_id3=$ac5_array[$ac_name][3];
		$ac_id4=$ac5_array[$ac_name][4];
		$varname = "remarks_" . $i;
		$remarks = $$varname;
		$varname = "dr_cr_" . $i;
		$dr_cr = $$varname;
		$varname = "dept_" . $i;
		$dept = $$varname;
		if ($dept==-1) $dept="";
		$varname = "amount_" . $i;
		$amount = $$varname;
		$result = mysql_query("INSERT INTO TRANS (VR_NO, VR_DT, VR_TP, AC_ID1, AC_ID2, AC_ID3, AC_ID4, AC_ID5,  DR_CR, CHQ_NO, AMOUNT, PARTY, REMARKS, T_DT, DEPT) VALUES ('$max_vr_no','$vr_dt','JV','$ac_id1','$ac_id2','$ac_id3','$ac_id4','$ac_name','$dr_cr','$chq_no','$amount','$party','$remarks','$t_dt','$dept')", $db);
		checkMySQLError();
	
	}

	$result = mysql_query("UNLOCK TABLES",$db);
	
	mysqlReport($result, "Transaction sucessfully added, Voucher Number is $max_vr_no", "print_money_receipt.php?vr_no=$max_vr_no", "printmoneyReceipt");

} else {
	$type_of_voucher="Journal ";
	$ac_array = get_ac5_sc_array("5(1)","B");
	
	$dept_array = get_dept_array();
	if (!isset($ac_count)) { $ac_count = 3 ;} 

	$today = get_today_hrd_string() ;
	?> 
	<table cellpadding=5 cellspacing=0 border=0 width='100%'>
	 <tr>
          <td valign=top align=center width='65%'>
	     <?

	openForm("transaction", $PHP_SELF, "onSubmit='return checkBeforeSubmit()'");
	beginPrettyTable("2", "enter $type_of_voucher Voucher");
	  makeHiddenField("ac_count", "$ac_count");
	  makeHiddenField("submitnow","");
	  makePlainStaticField("vr_no", "", "Vr.No.:", 10);
	  makePlainStaticField("t_dt", $today,"Transaction Date:", 10);
	  makePlainTextField("vr_dt", $today, "Vr.-Date:", 10);
	  
	  makeTextField("remarks", "", "Narration:",30,"onChange=\"copyNarrationField()\"");
	endPrettyTable();
	# Start now with that second part of the story
	$ac_array = get_ac5_sc_array("5(1/2)","");
	
	beginPrettyTable("1", "I call it \"Counterbooking\"");
	?> <tr><td>AccountName</td><td>Narration</td><td>Dept</td><td>DR_CR</td><td>Amount</td></tr><?
	  for ($i=0; $i < $ac_count; $i++) {
	    startRow();
	     makePlainDropBox("ac_name_$i", $ac_array);
	     makePlainTextField("remarks_$i", "", "", 20);
	     makePlainDropBox("dept_$i", $dept_array);
	     makePlainTextField("dr_cr_$i","","",2,"onChange='updateTotalFields()'");
	     makePlainTextField("amount_$i","","",6,"onChange='updateTotalFields()'");
	    endRow();
	  }
	  $ac_count++;
	  makeSpecialSubmitter("moreRows", "onClick=\"moreRows($ac_count)\"");
	makePlainStaticField("debit_sum", "", "Total Debit:", 10);
	makePlainStaticField("credit_sum", "", "Total Credit:", 10);

	endPrettyTable();
	makeSpecialSubmitter("submit", "onClick='this.form.submitnow.value=\"1\"'");

	closeForm();
	
	?>
	</td>
	  </table>
	  <?
	
}
endDocument();
?>
