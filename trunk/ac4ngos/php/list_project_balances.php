<?
# Idont know, what this is for :-( 

require("accrp.php");
session_start();
require("security/secure.php");

pt_register('POST','ac_id1','startdate','enddate');

beginDocument("list project Balance", $sess_user);

if (isSet($ac_id1)) {
	print_project_transactions_head($ac_id1,$startdate,$enddate,$ac_name);
	
	# generate account-objects
	if ($ac_id1!=0) {
		$openingAccounts = new BankAndCashAccounts_byProject($ac_id1, $startdate,"o");
		$paymentsAccounts = new Accounts_byProject($ac_id1, $startdate, $enddate,"Ea");
		$receiptsAccounts = new Accounts_byProject($ac_id1, $startdate, $enddate, "LI");
		$closingAccounts = new BankAndCashAccounts_byProject($ac_id1, $enddate,"c");
	} else {
		$openingAccounts = new BankAndCashAccounts("1", $startdate,"o");
		$paymentsAccounts = new Accounts("1", $startdate, $enddate,"Ea");
		$receiptsAccounts = new Accounts("1", $startdate, $enddate, "LI");
		$closingAccounts = new BankAndCashAccounts("1", $enddate,"c");
	}
	
	# Opening Balance
	beginPrettyTable("4", "Opening Balance");
	printRow(array("Code Number","Description","Amount"));
	while ($openingAccounts->getNext()) {
		printRow(array($openingAccounts->getActualCodeString(),$openingAccounts->getActualCodeName(),$openingAccounts->getActualBalance()),"abwechselnd");
	}
	printRow(array("","Total",$openingAccounts->getSum()));
	endPrettyTable();

	
	# Payments
	beginPrettyTable("4", "Payments");
	printRow(array("Code Number","Description","Amount"));
	while ($paymentsAccounts->getNext()) {
		if ($paymentsAccounts->getActualBalance()=="") continue;
		printRow(array($paymentsAccounts->getActualCodeString(),$paymentsAccounts->getActualCodeName(),$paymentsAccounts->getActualBalance()),"abwechselnd");
	}
	printRow(array("","Total",$paymentsAccounts->getSum()));
	endPrettyTable();

	# Receipts
	beginPrettyTable("4", "Receipts");
	printRow(array("Code Number","Description","Amount"));
	while ($receiptsAccounts->getNext()) {
		if ($receiptsAccounts->getActualBalance()=="") continue;
		printRow(array($receiptsAccounts->getActualCodeString(),$receiptsAccounts->getActualCodeName(),$receiptsAccounts->getActualBalance()),"abwechselnd");
	}
	printRow(array("","Total",$receiptsAccounts->getSum()));
	endPrettyTable();
	
	# Closing Balance
	beginPrettyTable("4", "Closing Balance");
	printRow(array("Code Number","Description","Amount"));
	while ($closingAccounts->getNext()) {
		printRow(array($closingAccounts->getActualCodeString(),$closingAccounts->getActualCodeName(),$closingAccounts->getActualBalance()),"abwechselnd");
	}
	printRow(array("","Total",$closingAccounts->getSum()));
	endPrettyTable();
	
} else { 
	$ac_array = get_ac_array(1);
	$ac_array[0] = "ALL PROJECTS";

	   openForm("enter Date", $PHP_SELF);
	   beginPrettyTable("2", "Project Balances");
	    makeDropBox("ac_id1", $ac_array, "Project");
	    makeTextField("startdate", get_today_hrd_string(), "Start Date:");
	    makeTextField("enddate", get_today_hrd_string(), "End Date:");
	    makeSubmitter();
	   endPrettyTable();
	   closeForm(); 
	
}
endDocument();
?>
