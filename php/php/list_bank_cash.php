<?
# This form allows to enter a date. After submitting, all Bank and Cash Account-balances get
# printed seperated by Projects. 
session_start();

require("accrp.php");
require("security/secure.php");

beginDocument("list Cash and Bank", $sess_user);

if ($date) {
	$ac1 = get_ac_array(1);
	foreach ($ac1 as $key => $prj_desc) {
		$accounts = new BankAndCashAccounts_byProject($key,$date,"c");
		beginPrettyTable (4,"$prj_desc");
		printRow(array("Account","Balance"));
		while ($row = $accounts->getNext()) {
			printRow(array(	$row["AC5_DESC"],
					$accounts->getActualBalance()."&nbsp;"), 
					"irgendwas");
		}
		printRow(array("Sum",$accounts->getSum()));
		endPrettyTable();
	}
	

} else { 
	   openForm("enter Date", $PHP_SELF);
	   beginPrettyTable("2", "list bank and cash");
	    makeTextField("date", get_today_hrd_string(), "Date:");
	    makeSubmitter();
	   endPrettyTable();
	   closeForm(); 
	
}
endDocument();
?>
