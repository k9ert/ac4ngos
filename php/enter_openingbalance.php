<?
# enter_openingbalance.php displays a form that allows the entry of a opening balance
# All Accounts which have BANK or CASH in their AC3 get listed
require("accrp.php");
session_start();
require("security/secure.php");

pt_register('POST','submitnow');
# others are not needed, they get accessed by $_POST (see line 33)

beginDocument("Enter OpeningBalance", $sess_user);

if ($submitnow==1) {
	$db = getDBConnection();
	$query_part1 = "INSERT INTO TRANS (VR_NO, VR_DT, VR_TP, AC_ID1, AC_ID2, AC_ID3, AC_ID4, AC_ID5,  DR_CR, CHQ_NO, AMOUNT, PARTY, REMARKS, T_DT, DEPT) VALUES ";
	$today = get_today_srd_string();
	$accounts = new BankAndCashAccounts("1","0000-00-00","c");

	while ($row = $accounts->getNext()) {
		$ac1 = $row["AC_ID1"];
		$ac2 = $row["AC_ID2"];
		$ac3 = $row["AC_ID3"];
		$ac4 = $row["AC_ID4"];
		$ac5 = $row["AC_ID5"];
		$field_name = "account_field_".$ac5;
		$field_desc = $accounts->getActualCodeName();
		$amount = $_POST[$field_name];
		
		if ($amount=="") {
			echo "Warning: Field $field_desc has zero Amount!<br>";
			continue;
		}
		$amount = $_POST[$field_name];
		$vr_no = get_new_vrno();
		# insert voucher
		$query = $query_part1 . "('$vr_no','$today','CR','$ac1', '$ac2','$ac3','$ac4','$ac5','D','','$amount','','opening Balance','$today','')";
		$result = mysql_query($query,$db);
		checkMySQLError();
		# insert counterbooking
		$query = $query_part1 . "('$vr_no','$today','CR','0', '5','0','0','0','C','','$amount','','opening Balance','$today','')";
		$result = mysql_query($query,$db);
		checkMySQLError();

	}
	report(1,"Everything seems to be fine. Check via Bank and Cash Report!");
	
} else {
	$db = getDBConnection();
	$result = mysql_query("Select * FROM TRANS");
	if (mysql_num_rows($result)!=0) {
		report(0,"Sorry, opening balance can only be performed when you have no Vouchers entered");
	}
	$accounts_array = get_ac5_sc_array("5(1)","B");
	beginPrettyTable("2", "enter opening balances");
	openForm("openingbalance", $PHP_SELF);
	  makeHiddenField("submitnow", 0);
	  foreach ($accounts_array as $ac5 => $desc) {
		makeTextField("account_field_".$ac5,"",$desc);
	  }
	  makeSpecialSubmitter("submit","onClick='this.form.submitnow.value=\"1\"'");
	closeForm();
	endPrettyTable();
}
endDocument();
?>
