<?
# enter_personal.php displays a form that allows the entry of a personal and
# handles the submission
session_start();

require("accrp.php");
require("security/secure.php");
$javascript= <<<EOD
<script type="text/javascript">
<!--
var incomeOld;
var exoenseOld;

function editIncomeField(value) {
	if (isNaN(value) || value=="")
		incomeOld = 0;
	else
		incomeOld=parseInt(value);	
}

function changedIncomeField(value) {
	if (isNaN(value) || value=="")
		value = 0;
	var diff = parseInt(value)-incomeOld;
	var income_sum = parseInt(document.editsalary.income_sum.value);
	var total_sum = parseInt(document.editsalary.total_sum.value);
	document.editsalary.income_sum.value = income_sum + diff;
	document.editsalary.total_sum.value = total_sum + diff;
}

function editExpenseField(value) {
	if (isNaN(value) || value=="")
		expenseOld = 0;
	else
		expenseOld=parseInt(value);
}

function changedExpenseField(value) {
	if (isNaN(value) || value=="")
		value = 0;
	var diff = parseInt(value)-expenseOld;
	var expense_sum = parseInt(document.editsalary.expense_sum.value);
	var total_sum = parseInt(document.editsalary.total_sum.value);
	document.editsalary.expense_sum.value = expense_sum + diff;
	document.editsalary.total_sum.value = total_sum - diff;
}


//-->
</script>
EOD;

beginDocument("Enter personal", $sess_user,$javascript);

if ($submitnow==1) {
	$db = getDBConnection();

	# this is not very clever. First removing everything and then again inserting and we 
	# don't get a history, what the employees earned one
	# year ago ... but it's easier and I'm in hurry ...
	$query = "DELETE FROM EMP_SAL WHERE EMP_ID3=$emp_id3";
	echo "$query";
	$result = mysql_query($query,$db);
	checkMySQLError();
	$salary_elements_array_desc = get_salary_elements_array("SAL_DESC");
	$salary_elements_array_tp = get_salary_elements_array("ID_TP");

	foreach ($_POST as $key => $value) {
		echo "processing field: $key<br>";
		if (ereg("^salary_field_([0-9]*)_tp$",$key)) continue;
		if (ereg("^salary_field_([0-9]*)_desc$",$key)) continue;
		if (!ereg("^salary_field_([0-9]*)",$key,$regs)) continue;
		
		$salary_tp = $salary_elements_array_tp[$regs[1]];
		$salary_desc = $salary_elements_array_desc[$regs[1]];
		$query = "INSERT INTO EMP_SAL (EMP_ID3, ID_TP, SAL_ID, SAL_AMT) VALUES ('$emp_id3','$salary_tp','$regs[1]','$value')";
		echo $query ."<br>";
		$result = mysql_query($query,$db);
		checkMySQLError(); 

	}
} else if ($emp_id3) {
	
	$db = getDBConnection();
	
	$result = mysql_query("SELECT * FROM PERSONAL WHERE EMP_ID3=$emp_id3",$db);
	checkMySQLError();
	$edit_personal = mysql_fetch_array($result);
	$result = mysql_query("SELECT * FROM EMP_SAL, SAL_ID WHERE EMP_SAL.SAL_ID=SAL_ID.SAL_ID AND EMP_ID3=$emp_id3",$db);
	checkMySQLError();
	while($row = mysql_fetch_array($result,MYSQL_ASSOC))
		$edit_salary[$row["SAL_ID"]] = $row["SAL_AMT"];

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
	$income_salary_elements_array_desc = get_salary_elements_array("SAL_DESC","ID_TP='I'");
	$expense_salary_elements_array_desc = get_salary_elements_array("SAL_DESC","ID_TP='E'");
	$salary_elements_array_tp = get_salary_elements_array("SAL_TP");
	$designation_array = get_designation_array();
	openForm("editsalary", $PHP_SELF);
	beginPrettyTable("2", "edit Salary");
	  makeHiddenField("emp_id3", $emp_id3);
	  makeHiddenField("submitnow", 0);
	  makeStaticField("emp_name",$edit_personal["EMP_NAME"], "Employer Name");
	  makeStaticField("desig_desc", get_designation_of($emp_id3),"Designation");
	# Income-Positions
	printRow(array("<div align=\"center\"><h2>INCOME</h2></div>"),"","1,2");
	foreach ($income_salary_elements_array_desc as $key => $value) { 
	  makeTextField("salary_field_".$key,$edit_salary["$key"],"$value",10,"onFocus=\"editIncomeField(this.value)\" onChange=\"changedIncomeField(this.value) \"");
	  $income_sum += $edit_salary["$key"];
	}
	makeStaticField("income_sum",$income_sum, "Income Sum:","",11);
	# Expense-Positions
	printRow(array("<div align=\"center\"><h2>EXPENSE</h2></div>"),"","1,2");
	foreach ($expense_salary_elements_array_desc as $key => $value) {
	  makeTextField("salary_field_".$key,$edit_salary["$key"],"$value",10,"onFocus=\"editExpenseField(this.value)\" onChange=\"changedExpenseField(this.value) \"");
	  $expense_sum += $edit_salary["$key"];
	}
	makeStaticField("expense_sum",$expense_sum, "Expense Sum:","",11);
	printRow(array("<hr>"),"","1,2");
	makeStaticField("total_sum",$income_sum-$expense_sum, "Total Sum:","",14);
	makeSpecialSubmitter("submit","this.form.submitnow.value=\"1\"");
	endPrettyTable();
	closeForm();
	
	?>
	</td>
	  </table>
	  <?
	
} else {
	$personal_array = get_personal_array();
	openForm("selectemployee", $PHP_SELF);
	beginPrettyTable("2", "select Employee");
	  makeDropBox("emp_id3",$personal_array, "Employer Name");
	  makeSubmitter();
	endPrettyTable();
	closeForm();

}
endDocument();
?>
