<?
# enter_pf_loan.php displays a form that allows the entry of a PF-Loan and
# handles the submission

require("accrp.php");
session_start();
require("security/secure.php");

pt_register('POST','emp_id3','type','loan_dt','loan_start','inst_no','loan_end','interest_rate');
pt_register('POST','loan_amt','int_amt','int_rate','tot_amt');

# We want to make some fields to be autocalculated. This has to be done in Javascript

$javascript= <<<EOD
<script type="text/javascript">
<!--

function confirmSubmit() {
	if (!checkdate(document.loan_form.loan_dt.value))
		return false;
	if (!checkdate(document.loan_form.loan_start.value))
		return false;
	if (isNaN(parseInt(document.loan_form.inst_no.value))){
		alert("Instalment No is empty!");
		return false;
	}
	if (isNaN(parseInt(document.loan_form.loan_amt.value))){
		alert("Loan Amount is empty!");
		return false;
	}
	if (document.loan_form.emp_id3.value==-1) {
		alert("No Employee selected !");
		return false;
	}
	updateFields();
	return confirm("press Ok to submit!");
}

function updateFields() {
  var inst_no  = parseInt(document.loan_form.inst_no.value);
  if (!isNaN(inst_no) && document.loan_form.loan_start.value!="") {
  	var reg_exp = /(\d{1,2})-(\d{1,2})-(\d{4})/;
	var loan_start = document.loan_form.loan_start.value;
	reg_exp.exec(loan_start);
  	var loan_start_day = RegExp.$1;
  	var loan_start_month = RegExp.$2;
  	var loan_start_year = RegExp.$3;
	LoanStart = new Date(loan_start_year,loan_start_month-1,loan_start_day);
	LoanEnd = addmonth(LoanStart,inst_no);
	var loan_end = LoanEnd.getDate()+"-"+ (LoanEnd.getMonth()+1)+"-"+ LoanEnd.getFullYear();
	document.loan_form.loan_end.value = loan_end;
  }
  
  var loan_amt  = parseInt(document.loan_form.loan_amt.value);
  if (!isNaN(loan_amt)) {
  	var interest_rate = parseInt(document.loan_form.interest_rate.value);
  	if (isNaN(loan_amt)) loan_amt=0;
  	if (isNaN(inst_no)) inst_no=0;
 	var rates_of_interest = interest_rate/(12*100);

	//the hell knows, what crf is
	var crf = (rates_of_interest*Math.pow(1+rates_of_interest,inst_no))/(Math.pow(1+rates_of_interest,inst_no)-1);
	var int_rate = crf*loan_amt;
  	var int_amt = int_rate*inst_no;
  	var tot_amt = loan_amt + int_amt;
  
  	document.loan_form.int_amt.value = Math.round(int_amt);
  	document.loan_form.tot_amt.value = Math.round(tot_amt);
  	document.loan_form.int_rate.value = Math.round(int_rate);
  }
}

function addmonth(date,addmonth) {
	var year = date.getFullYear();
	var month = date.getMonth()+1;
	var day = date.getDate();
	var addYears = Math.floor(addmonth / 12);
	year += addYears;
	addmonth = addmonth % 12;
	month += addmonth;
	if (month >12) {
		year++;
		month -= 12;
	}
	return new Date(year,month-1,day);
}

function checkdate(datestring) {
	var reg_exp = /(\d{1,2})-(\d{1,2})-(\d{4})/;
	if (!datestring.match(reg_exp)) {
		alert("Date (" + datestring + ") is invalid!");
		return false;
	}
	return true;
}
//-->
</script>
EOD;

beginDocument("Enter PF-Loan entry", $sess_user,$javascript);

if ($emp_id3) {
	$db = getDBConnection();
	if ($type=="PF")
		$loan_table="PF_LOAN";
	else if ($type=="CUF")
		$loan_table="CUF_LOAN";
	else 
		die("Loantype unspecified !");
	$loan_dt = conv_to_srd($loan_dt);
	$loan_start = conv_to_srd($loan_start);
	$loan_end = conv_to_srd($loan_end);
	$query = "INSERT INTO $loan_table (EMP_ID3,LOAN_DT,LOAN_START,LOAN_END,LOAN_AMT,INT_AMT,TOT_AMT,INST_NO,INT_RATE) VALUES ('$emp_name','$loan_dt','$loan_start','$loan_end','$loan_amt','$int_amt','$tot_amt', '$inst_no', '$int_rate' )";
	$result = mysql_query($query, $db);
	checkMySQLError();
	mysqlReport($result, "Loan sucessfully added", "main.php", "Home");
	mysql_close($db);
	
} else {
	
	if ($type=="PF") {
		$loan_type="PF-Loan";
		$interest_rate=10;
	} else if ($type="CUF") {
		$loan_type="CUF-Loan";
		$interest_rate=12; # is that true ????
	} else 
		die("Loantype unspecified !");

	
	?>
	<table cellpadding=5 cellspacing=0 border=0 width='100%'>
	 <tr>
	  <td valign=top width='15%'>
	
           <? beginPrettyTable("1"); # this is again some PHP-Code?> 
	   <tr>
	    <? echo "<td><a href='list_loan.php?type=$type'>show List</a></td>"; ?>
	   </tr>
	   <tr>
	    <td><hr color='black'></td>
	   </tr>
	   <? endPrettyTable(); # it just print out some HTML?>
	  </td>
	  <td valign=top align=center width='65%'>
	<?
	
	$db = getDBConnection();
	
	$employer_array = get_personal_array();
	
	openForm("loan_form", $PHP_SELF,"onSubmit=\"return confirmSubmit()\"");
	beginPrettyTable("2", "enter $loan_type entry");
	  makeHiddenField("type",$type);
	  makeDropBox("emp_id3", $employer_array, "Employers Name");
	 startRow(); 
	  makePlainTextField("loan_dt","", "Loan Santion Dt.",10);
	  makePlainTextField("loan_start", "", "Deduction Start Dt.",10,"onChange=\"checkdate(this.value) && updateFields()\"");
	 endRow();
	 startRow();
	  makePlainTextField("inst_no", "", "Instalment No",6,"onChange=\"updateFields()\"" );
	  makePlainStaticField("loan_end", "", "Loan End Dt.",10);
	 endRow();
	  makeStaticField("interest_rate", "$interest_rate"."%", "Interest Rate",6);
	 startRow();
	  makePlainTextField("loan_amt", "", "Loan Amount",10,"onChange=\"updateFields()\"");
	  makePlainStaticField("int_amt", "", "Total Interest",10);
	 endRow();
	 startRow();
	  makePlainStaticField("int_rate", "", "Inst Amount",10);
	  makePlainStaticField("tot_amt", "", "Total Amount",10);
	 endRow();

	  makeSubmitter();
	endPrettyTable();
	closeForm();

	# the table above has to be closed
	echo "</td> </table>";

	
}
endDocument();
?>
