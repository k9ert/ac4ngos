<?
# lists all transactions on a specific Account 

require("accrp.php");
session_start();
require("security/secure.php");

pt_register('POST','ac_id1','startdate','enddate');

beginDocument("list Project Transactions", $sess_user);

if (isSet($ac_id1)) {
	print_project_transactions_head($ac_id1,$startdate,$enddate,$ac_name);
	
	
	#Receipts
	# generate Transaction-object
	if ($ac_id1!=0) 
		$transactions = new Transactions($ac_id1, $startdate, $enddate);	
	else
		$transactions = new Transactions_byProject("1", $startdate, $enddate);
	beginPrettyTable("4", "Receipts");
	printRow(array("", "<b>opening Balance:</b>", "<b>" . $transactions->getOpeningBalance(). "</b>"));
	printRow(array("Code Number","Description","Amount","VR-Type"));
	while ($row = $transactions->getNextReceipt()) {
		printRow($row,"fluct");
	}
	printRow(array("","<b>total Receipts<b>","<b>" . $transactions->getTotalReceipts() . "</b>"));
	endPrettyTable();
	
	# Payments
	# generate Transaction-object
	if ($ac_id1!=0) 
		$transactions = new Transactions($ac_id1, $startdate, $enddate);	
	else
		$transactions = new Transactions_byProject("1", $startdate, $enddate);
	beginPrettyTable("4", "Payments");
	printRow(array("Code Number","Description","Amount","VR-Type"));
	while ($row = $transactions->getNextPayment()) {
		printRow($row,"fluct");
	}
	printRow(array("","<b>total Payments</b>","<b>" . $transactions->getTotalPayments() . "</b>"));
	printRow(array("","<b>closing Balance:</b> ", "<b>" . $transactions->getClosingBalance()."</b>"));
	endPrettyTable();


} else { 
	$ac_array = get_ac_array(1);
	$ac_array[0] = "ALL PROJECTS";
	   openForm("enter Date", $PHP_SELF);
	   beginPrettyTable("2", "Project Transactions");
	    makeDropBox("ac_id1", $ac_array, "Project");
	    makeTextField("startdate", get_today_hrd_string(), "Start Date:");
	    makeTextField("enddate", get_today_hrd_string(), "End Date:");
	    makeSubmitter();
	   endPrettyTable();
	   closeForm(); 
	
}
endDocument();
?>
