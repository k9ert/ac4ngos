<?
# List Loans of personal

require("accrp.php");
session_start();
require("security/secure.php");

beginDocument($lCustomerProfile, $sess_user);

$db = getDBConnection();

# the date, which is the reference for the calculation of due and balance
$ref_date = get_today_srd_string();

$personal_array = get_personal_array(); 
echo "<h2>Centre for the rehabilitation of the paralysed - CRP</h2>";
echo "<div align=center>" . ($type=="PF" ?  "Provident " : "Credit Union ") . "Fund Statement for the month of ";
echo get_month_name($ref_date) . " " . get_year($ref_date) . "</div>";

if ($type=="PF")
	$loan_table="PF_LOAN";
else if ($type=="CUF")
	$loan_table="CUF_LOAN";
else
	die("Loantype unspecified !");

$result = mysql_query("select * from $loan_table", $db);
checkMySQLError();
beginPrettyTable("4", "$loan_table");
printRow(array("Emp_ID3","Emp. Name","LoanStart","LoanEnd","Loan-Amt","Instalment","Tot_Amt","Due","Balance"));

while ($row = mysql_fetch_array($result)) {
	$name = $personal_array[$row["EMP_ID3"]];
	$rest_inst_no = datediff("m",$ref_date,$row["LOAN_END"]);
	$rest_amount = $rest_inst_no*$row["INT_RATE"];
	if ($rest_inst_no < 0) continue;
	$inst_sum += $rest_inst_no;
	$rest_amount_sum += $rest_amount;
	$loan_amount_sum += $row["LOAN_AMT"];
	printRow(array($row["EMP_ID3"],$name,conv_to_hrd($row["LOAN_START"]),conv_to_hrd($row["LOAN_END"]),$row["LOAN_AMT"],$row["INT_RATE"],$row["TOT_AMT"],$rest_inst_no,$rest_amount),"fluct");
}
printRow(array("","","Loan-Amt-Sum: ",$loan_amount_sum),"","3,2");
printRow(array("","","Inst-Sum: ","",$inst_sum),"","3,2");
printRow(array("","","Balance-Sum: ","","","","",$rest_amount_sum),"","3,2");

endPrettyTable();
endDocument();
?>
