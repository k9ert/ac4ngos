<?
# This file contains lost of functions perhaps used by other php-Files
# to generate no fun things like tables, forms, and such.

require("accrp-settings.php");

if(!(extension_loaded("mysql"))) {
	die ("MySQL support not enabled, <b>gcdb</b> cannot run.");
}

session_save_path($SESSION_PATH);

###############################################################################
# DB FUNCTIONS
###############################################################################

# getDBConnection
#
# I did this so the DB could easily be changed
function getDBConnection () {
	global $PERSISTENT, $DBHOST, $DBUSER, $DBPASSWORD, $DBNAME;

	if($PERSISTENT == "On") {
		$db = mysql_pconnect($DBHOST, $DBUSER, $DBPASSWORD);
	} else {
		$db = mysql_connect($DBHOST, $DBUSER, $DBPASSWORD);
	}
	mysql_select_db($DBNAME, $db);
	checkMySQLError("getDBConnection");
	return $db;
}

# checkMySQLError
#
# Checks for an error with MySQL and prints the error if it exists
function checkMySQLError ($additional_info="no Additional Informations available") {
	$test = mysql_errno();
	if($test != 0) {
		echo mysql_errno().":".mysql_error()."<br>";
		echo "The following information is in the code:<br>";
		die($additional_info . "<br>");
	}
}

# mysqlReport
#
# Uses the PrettyTable functions to generate a table detailing what happened
function mysqlReport ($result, $title, $return_url, $return_link) {
	global $lUpdateSuccess, $lUpdateFailed;
	if($result == 1) {
		beginPrettyTable("1", $title);
		echo "<tr>\n";
		echo " <td><div class=data>$lUpdateSuccess <a href='$return_url'>$return_link</a></div></td>\n";
		echo "</tr>\n";
		endPrettyTable();
	} else {
		beginPrettyTable("1", $title);
		echo "<tr> <td><div class=data>$lUpdateFailed: ";
		echo mysql_error();
		echo "</div></td></tr>\n";
		endPrettyTable();
	}
}

# report
#
# Uses the PrettyTable functions to generate a general report to the user what happened
function report ($result=1, $message="nothing more to say", $return_url="main.php", $return_link="back to home") {
	if($result == 1) {
		beginPrettyTable("1", "Ok (Tikache)");
		echo "<tr>\n";
		echo " <td><div class=data>$message: <a href='$return_url'>$return_link</a></div></td>\n";
		echo "</tr>\n";
		endPrettyTable();
	} else {
		beginPrettyTable("1", "something failed (bhalo na)");
		echo "<tr> <td><div class=data>$message: <a href='$return_url'>$return_link</a></div></td>\n";
		echo "</tr>\n";
		endPrettyTable();
	}
}

###############################################################################
# ACCRP FUNCTIONS (functions, special to accrp-Database)
###############################################################################

# get_acs_from_ac5
#
# returns all ac-Codes of a specific AC_ID5
function get_acs_from_ac5($ac5) {
	if (!isset($ac5)) echo "Problem in get_acs_from_ac5";
	$db = getDBConnection();
	$result = mysql_query("SELECT AC_ID1, AC_ID2, AC_ID3, AC_ID4, AC5_DESC FROM AC_CODE5 WHERE AC_ID5=$ac5", $db);
	checkMySQLError("get_acs_from_ac5");
	$row = mysql_fetch_array($result);
	return $row;
}

# get_ac_array
#
# returns an array with IDs as Keys and Descriptions as Values from one of the
# 4 nearly equal AC_Tables

function get_ac_array($ac_number) {
	$ac_number = (int)$ac_number;
	if ($ac_number < 1 || $ac_number > 5) { 
		echo "<h1>get_ac_array: bad argument</h1>";
		exit;
	}
	$db = getDBConnection();
	$result = mysql_query("select * from AC_CODE$ac_number", $db);
	checkMySQLError("get_ac_array");
	while($row = mysql_fetch_array($result))
    		$ac_array[$row["AC_ID$ac_number"]] = $row["AC${ac_number}_DESC"];
	asort($ac_array);
	return $ac_array;
}

# get_ac5_array
#
# returns the complete AC_CODE5 Table in a multidimensional field
# it is used at enter_trans, to get easily the AC_IDX Codes from the
# AC_CODE5 Table (in the moment they must be all stored in the TRANS
# Table because the AC_ID5 Column might not work properly (double-entries)
#
# maybe, it would be better to make a function like
# get_from_AC5_RowX_codeY($ac_ID5, $ac_Code)
# and call it each time, someone needs the Information

function get_ac5_array() {
	$db = getDBConnection();
	$result = mysql_query("select * from AC_CODE5", $db);
	checkMySQLError("get_ac5_array");
	while($row = mysql_fetch_array($result)) {
		$ac5_array[$row["AC_ID5"]][1] = $row["AC_ID1"];
		$ac5_array[$row["AC_ID5"]][2] = $row["AC_ID2"];
		$ac5_array[$row["AC_ID5"]][3] = $row["AC_ID3"];
		$ac5_array[$row["AC_ID5"]][4] = $row["AC_ID4"];
	}
	return $ac5_array;
}

function get_ac1_sc_array() {
	# define shortcuts for AC_CODE1
	$db = getDBConnection();
	$result = mysql_query("select AC_ID1, AC1_SC from AC_CODE1", $db);
	checkMySQLError("get_ac5_array");
	while($row = mysql_fetch_array($result)) {
		$ac1_sc_array[$row["AC_ID1"]] = $row["AC1_SC"];
	}
	return $ac1_sc_array;
}

# get_ac5_sc_array (get_accounting_code5_shortcut_array)
#
# returns an array with IDs as Keys and shortcut-Descriptions as Values from the AC_CODE5

function get_ac5_sc_array($format="1/2/5", $string="") {
	# define shortcuts for AC_CODE2
	# normally, the definitions of shortcuts should not be hardecoded
	# In this case, it should be OK because it stays the same all the time
	$ac2_sc_array = array(
		1 => "LB",
		2 => "EX",
		3 => "AS",
		4 => "IN",
		5 => "NIL"); # Nil is nothing and only for opening balance !!!
	$ac1_sc_array = get_ac1_sc_array();
	
	$db = getDBConnection();
	$where_clause = get_whereclause_for_accounts($string);
	$query = "select * from AC_CODE5 WHERE $where_clause";
	#echo $query . "<br>";
	$result = mysql_query($query, $db);
	checkMySQLError("get_ac5_sc_array");
	while($row = mysql_fetch_array($result)) {
		$ac1_sc = $ac1_sc_array[$row["AC_ID1"]];
		$ac2_sc = $ac2_sc_array[$row["AC_ID2"]];
		# Example: SONALI BANK, AC/NO-473 (CRP)
		if ($format=="5(1)")
			$ac5_array[$row["AC_ID5"]] = $row["AC5_DESC"]. " ($ac1_sc)";
		else if ($format=="1/2/5")
			$ac5_array[$row["AC_ID5"]] = $ac1_sc . "/" . $ac2_sc . "/" . $row["AC5_DESC"];
		else if ($format=="5")
			$ac5_array[$row["AC_ID5"]] = $row["AC5_DESC"];
		else if ($format=="5(1/2)")
			$ac5_array[$row["AC_ID5"]] = $row["AC5_DESC"] . " ($ac1_sc/$ac2_sc)";
		else
			die ("get_ac5_sc_array: unsupported Format ($format)");

	}
	if ($ac5_array=="" ) 
		echo "get_ac5_sc_array: ac5_array not set!!";
	asort($ac5_array);
	return $ac5_array;
}

# get_bank_ac3
# 
# returns the ac3 of BANK
function get_bank_ac3() {
	$db = getDBConnection();
	$query = "SELECT AC_ID3 from AC_CODE3 WHERE AC3_DESC='BANK'";
	#echo $query . "<br>";
	$result = mysql_query($query, $db);
	checkMySQLError("get_bank_ac3");
	$row = mysql_fetch_array($result);
	if (mysql_fetch_array($result))
		die("get_bank_ac3: Error! More than one AC3 with the Description \"BANK\" !<br>");
	return $row["AC_ID3"];
}

# get_cash_ac3
# 
# returns the ac3 of CASH
function get_cash_ac3() {
	$db = getDBConnection();
	$query = "SELECT AC_ID3 from AC_CODE3 WHERE AC3_DESC='CASH'";
	#echo $query . "<br>";
	$result = mysql_query($query, $db);
	checkMySQLError("get_cash_ac3");
	$row = mysql_fetch_array($result);
	if (mysql_fetch_array($result))
		die("get_cash_ac3: Error! More than one AC3 with the Description \"CASH\" !<br>");
	return $row["AC_ID3"];
}
# get_advance_ac3
# 
# returns the ac3 of ADVANCE
function get_advance_ac3() {
	$db = getDBConnection();
	$query = "SELECT AC_ID3 from AC_CODE3 WHERE AC3_DESC='ADVANCE'";
	#echo $query . "<br>";
	$result = mysql_query($query, $db);
	checkMySQLError("get_advance_ac3");
	$row = mysql_fetch_array($result);
	if (mysql_fetch_array($result))
		die("get_advance_ac3: Error! More than one AC3 with the Description \"ADVANCE\" !<br>");
	return $row["AC_ID3"];
}


# get_personal_array
#
# returns an array with IDs as Keys and Descriptions as Values
function get_personal_array() {
	$db = getDBConnection();
	$result = mysql_query("select * from PERSONAL", $db);
	checkMySQLError("get_personal_array");
	while($row = mysql_fetch_array($result))
    		$personal_array[$row["EMP_ID3"]] = $row["EMP_NAME"];
	asort($personal_array);
	return $personal_array;
}

# get_salary_elements_array
#
# returns an array with IDs as Keys and Descriptions as Values
function get_salary_elements_array($fieldname="SAL_AMT",$where_clause="1") {
	$db = getDBConnection();
	$result = mysql_query("SELECT * FROM SAL_ID WHERE $where_clause ORDER BY ID_TP DESC", $db);
	checkMySQLError("get_salary_elements_array");
	while($row = mysql_fetch_array($result,MYSQL_ASSOC))
    		$salary_elements_array[$row["SAL_ID"]] = $row["$fieldname"];
	return $salary_elements_array;
}

# get_salary_elements_count
#
# returns the count of the salary-elements
function get_salary_elements_count() {
	$db = getDBConnection();
	$result = mysql_query("SELECT COUNT(SAL_ID) FROM SAL_ID ", $db);
	checkMySQLError("get_salary_elements_count");
	$row = mysql_fetch_array($result,MYSQL_ASSOC);
	return $row["COUNT(SAL_ID)"];
}


# get_designation_array
#
# returns an array with IDs as Keys and Descriptions as Values

function get_designation_array() {
	$db = getDBConnection();
	$result = mysql_query("select * from DESIG", $db);
	checkMySQLError("get_designation_array");
	while($row = mysql_fetch_array($result))
    		$designation_array[$row["DESIG_ID"]] = $row["DESIG_DESC"];
	asort($designation_array);
	mysql_close($db);
	return $designation_array;
}

# get_designation_of
#
# returns the designation of the given EMP_ID3

function get_designation_of($emp_id3) {
	$db = getDBConnection();
	$result = mysql_query("select * from PERSONAL LEFT JOIN DESIG ON PERSONAL.DESIG_ID=DESIG.DESIG_ID WHERE EMP_ID3=$emp_id3", $db);
	checkMySQLError("get_designation_array");
	$row = mysql_fetch_array($result);
	mysql_close($db);
	return $row["DESIG_DESC"];
}

# get_ac5_desc_of
# 
# returns the description of an AC5-Code

function get_ac5_desc_of($ac5) {
	$db = getDBConnection();
	$result = mysql_query("SELECT AC5_DESC FROM AC_CODE5 WHERE AC_ID5=$ac5", $db);
	checkMySQLError("get_designation_array");
	$row = mysql_fetch_array($result);
	mysql_close($db);
	return $row["AC5_DESC"];
}

# get_dept_array
#
# returns an array with IDs as Keys and Descriptions as Values

function get_dept_array() {
	$db = getDBConnection();
	$result = mysql_query("select * from DEPT", $db);
	checkMySQLError("get_dept_array");
	$counter=0;
	while($row = mysql_fetch_array($result))
    		$dept_array[$row["DEPT_ID"]] = $row["DEPT_NAME"];
	asort($dept_array);
	mysql_close($db);
	return $dept_array;
}

# get_new_vrno
#
# returns the next free VR_NO. Should ONLY be used by enter_openingbalance.php
# it would be nice, to use it with enter_trans.php but there, the Table
# Trans has to be locked (here it is very unlikely that someone else tries
# to enter a voucher ...)
function get_new_vrno() {
	$db = getDBConnection();
	$result = mysql_query("SELECT MAX(VR_NO) FROM TRANS",$db);
	checkMySQLError();
	$row = mysql_fetch_array($result);
	$max_vr_no=$row["MAX(VR_NO)"];
	mysql_close($db);
	return ++$max_vr_no;
	
}

# get_amount_internal
#
# an internal helper-function helps code reuse, should be only used in the next
# two functions.

function get_amount_internal($where_clause,$dr_cr,$enddate,$startdate="") {
	if ($startdate) $startdate = conv_to_srd($startdate);
	$enddate = conv_to_srd($enddate);
	if ($startdate == "") $startdate_where=""; else $startdate_where="AND VR_DT >= '$startdate'";
	$db = getDBConnection();
	$query="SELECT sum(AMOUNT) FROM `TRANS` WHERE $where_clause AND VR_DT <= '$enddate' $startdate_where AND DR_CR='$dr_cr'";
	#echo "$query<br>";
	$result = mysql_query($query, $db);
	checkMySQLError();
	$row = mysql_fetch_array($result);
	$num_rows = mysql_num_rows($result);
	mysql_close($db);
	if ($num_rows==0 or $row["sum(AMOUNT)"]=="") 
		return 0;
	else
		return $row["sum(AMOUNT)"];

}

# get_debit_sum
# 
# returns the money-receipts in a timeframe on a specific account

function get_debit_sum($where_clause,$enddate,$startdate="") {
	return get_amount_internal($where_clause,"D",$enddate,$startdate);
}

# get_credit_sum
# 
# returns the payments in a timeframe on a specific account

function get_credit_sum($where_clause,$enddate,$startdate="") {
	return get_amount_internal($where_clause,"C",$enddate,$startdate);

}

# get_turnover
#
# returns the turnover 

function get_turnover($where_clause,$enddate,$startdate="") {
	$income= get_debit_sum($where_clause,$enddate,$startdate);
	$expenses = get_credit_sum($where_clause,$enddate,$startdate);
	return abs($income - $expenses);
}

# get_opening_balance
#
# returns the opening balance on a specific acount
# same as closing balance but one day is substracted because
# for opening balance we need X < $date so we take opening balance
# with one day substracted -> X <= $date-1day

function get_opening_balance($where_clause,$date) {
	return get_turnover($where_clause,add_days($date,-1));
}

# get_closing_balance
#
# returns the closing balance on a specific acount

function get_closing_balance($where_clause,$date) {
	$turno= get_turnover($where_clause,$date);
	return $turno;
}

# CLASS Transactions
#
# A Wrapper-Class to simplify access on Transactions

Class Transactions {
	
	var $whereClause;
	var $startdate;
	var $enddate;
	var $result;
	var $db;
	var $row;
	var $balance;
	var $totalPayments;
	var $totalReceipts;
	var $openingBalance;
	var $closingBalance;
	var $finishFlag;
	
	function Transactions($where_clause, $startdate, $enddate) {
		$this->whereClause = $where_clause . " AND AC_ID2=3";
		$this->startdate = conv_to_srd($startdate);
		$this->enddate = conv_to_srd($enddate);
		$this->balance=0;
		$this->db = getDBConnection();
		#TODO: AC_ID3!=30 --> this means "no Advances" and should be number independent
		$query = "SELECT AC_ID1, AC_ID2, AC_ID3, AC_ID4 ,AC_ID5, REMARKS, AMOUNT, DR_CR, VR_TP FROM `TRANS` WHERE $where_clause AND (AC_ID2=3 AND AC_ID3!=30) AND VR_DT <= '$this->enddate' AND VR_DT >= '$this->startdate' ";
		#echo "$query<br>";
		$this->result = mysql_query($query, $this->db);
		$this->finishFlag="";
		checkMySQLError("Transactions");
		$this->totalPayments=get_credit_sum($this->whereClause,$this->enddate,$this->startdate);
		$this->totalReceipts=get_debit_sum($this->whereClause,$this->enddate,$this->startdate);
	}

	function getNext() {
		if (!($this->row = mysql_fetch_array($this->result,MYSQL_ASSOC))) {
			$this->finishFlag=1;
			return NULL;
		}
		if ($this->row["DR_CR"]=="C") 
			$this->balance -= $this->row["AMOUNT"];
		elseif ($this->row["DR_CR"]=="D")
			$this->balance += $this->row["AMOUNT"];
		$returnrow = $this->row;
		$returnrow["AC_ID1"]= get_HTML_Code_String($this->row);
		unset($returnrow["AC_ID2"]); unset($returnrow["AC_ID3"]); 
		unset($returnrow["AC_ID4"]); unset($returnrow["AC_ID5"]);
		return $returnrow;
	}

	function getActual() {
		return $this->row;
	}

	function getNextPayment() {
		do {
			if (!($row=$this->getNext())) 
				return NULL;
		} while ($row["DR_CR"]!="C");
		unset($row["DR_CR"]);
		return $row;
	}

	function getNextReceipt() {
		do {
			if (!($row=$this->getNext())) 
				return NULL;
		} while ($row["DR_CR"]!="D");
		unset($row["DR_CR"]);
		return $row;
	}

	function getOpeningBalance() {
		if (isset($this->openingBalance))
			return $this->openingBalance;
		else {
			$this->openingBalance=get_opening_balance($this->whereClause, $this->startdate);
			return $this->openingBalance;
		}
	}

	function getClosingBalance() {
		if ($this->finishFlag=="") {
			echo "getClosingBalance: not yet finished!";
			return NULL;
		}
		else {
			$temp=$this->balance;
			return $this->getOpeningBalance()+$this->balance;
		}
	}

	function getTotalPayments() {
		return $this->totalPayments;
	}

	function getTotalReceipts() {
		return $this->totalReceipts;

	}

	function finish() {
		while ($this->getNext());
	}
}

Class Transactions_byProject extends Transactions {
	var $prjNo;

	function Transactions_byProject($prjNo,$startdate, $enddate) {
		$this->prjNo = $prjNo;
		parent::Transactions("AC_ID1=$prjNo",$startdate, $enddate);
	}
}

# get_whereclause_for_accounts
#
# calculate a where-clause for AC2 Accoubts as needed. The needing is given by a string
# Allowed Characters in the string are "LEAaBDI" (Liability, Expenses, Assets, Assets without
# Bank/Cash/Advance, only Bank/Cash, Advance, Income). The accounts are combined with "or".

function get_whereclause_for_accounts($string="") {
	if ($string=="")
		return "1";
	if(ereg(".*L",$string))
		$where_clause_array[] = "AC_ID2=1";
	if(ereg(".*E",$string))
		$where_clause_array[] = "AC_ID2=2";
	if(ereg(".*a",$string)) {
		$bank_ac3 = get_bank_ac3();
		$cash_ac3 = get_cash_ac3();
		$advance_ac3 = get_advance_ac3();
		$where_clause_array[] = "(AC_ID2=3 AND AC_ID3!=$cash_ac3 AND AC_ID3!=$bank_ac3 AND AC_ID3!=$advance_ac3)";
	}
	else if (ereg(".*A",$string))
		$where_clause_array[] = "AC_ID2=3";
	else if (ereg(".*B",$string)) {
		$bank_ac3 = get_bank_ac3();
		$cash_ac3 = get_cash_ac3();
		$where_clause_array[] = "(AC_ID2=3 AND (AC_ID3=$cash_ac3 OR AC_ID3=$bank_ac3))";
	}
	
	if(ereg(".*I",$string))
		$where_clause_array[] = "AC_ID2=4";
	if(ereg(".*D",$string)) {
		$advance_ac3 = get_advance_ac3();
		$where_clause_array[] = "AC_ID3=$advance_ac3";
	}
	$where_clause = "(" . join(" OR ",$where_clause_array) . ")";
	return $where_clause;	
}

# CLASS Accounts
#
# A Class to get Accounts (balances etc.) 

Class Accounts {
	var $whereClause;
	var $startdate;
	var $enddate;
	var $balance;
	var $sum;
	var $finish_flag;
	var $result;
	var $row;
	
	# definition of type-string see function get_whereclause_for_accounts	
	function Accounts($where_clause,$startdate, $enddate, $type_string) {
		$this->whereClause=$where_clause . " AND " . get_whereclause_for_accounts($type_string);
		$this->startdate = $startdate;
		$this->enddate = $enddate;
		$this->sum = 0;
		$this->finish_flag = 0;
		$db = getDBConnection();
		$query = "SELECT * FROM `AC_CODE5` WHERE $this->whereClause";
		#echo "$query<br>";
		$this->result = mysql_query($query, $db);
		checkMySQLError("Accounts");
	}

	function getNext() {
		if (!$this->row = mysql_fetch_array($this->result,MYSQL_ASSOC)) {
			$this->finish_flag = 1;
			return NULL;
		}
		$ac5 = $this->row["AC_ID5"];
		$this->balance= get_turnover("AC_ID5=$ac5",$this->enddate,$this->startdate);
		$this->sum += $this->balance;
		return $this->row;
	}

	function getActualCodeString() { 
		return get_HTML_code_string($this->row);
	}

	function getActualCodeName() {
		return $this->row["AC5_DESC"];
	}

	function getActualBalance() {
		return $this->balance;	
	}

	function getActualIncome() {
		$ac5 = $this->row["AC_ID5"];
		return get_debit_sum("AC_ID5=$ac5",$this->enddate);
	}

	function getActualExpenses() {
		$ac5 = $this->row["AC_ID5"];
		return get_credit_sum("AC_ID5=$ac5",$this->enddate);
	}

	function getSum() {
		if ($this->finish_flag!=1)
			die ("Class Accounts(getSum): not yet finished!");
		return $this->sum;
	}
}

# CLASS Accounts
#
# A Class to get Accounts related to a special Project (balances etc.) 
Class Accounts_byProject extends Accounts {
	var $prjNo;

	function Accounts_byProject($prjNo,$startdate, $enddate, $type_string) {
		$this->prjNo = $prjNo;
		parent::Accounts("AC_ID1=$prjNo",$startdate, $enddate,$type_string);
	}
}

# CLASS Accounts
#
# A Class to get Bank and Cash-Accounts (balances etc.) 

Class BankAndCashAccounts extends Accounts {
	var $open_or_close_bal;

	function BankAndCashAccounts($where_clause,$enddate,$open_or_close_bal) {
		$this->open_or_close_bal=$open_or_close_bal;
		$bank_ac3 = get_bank_ac3();
		$cash_ac3 = get_cash_ac3();
		parent::Accounts("$where_clause AND (AC_ID3=$bank_ac3 OR AC_ID3=$cash_ac3)","", $enddate,"");
	}

	function getNext() {
		if (!$this->row = mysql_fetch_array($this->result,MYSQL_ASSOC)) {
			$this->finish_flag = 1;
			return NULL;
		}
		$ac5 = $this->row["AC_ID5"];
		if ($this->open_or_close_bal=="c")
			$this->balance= get_closing_balance("AC_ID5=$ac5",$this->enddate);
		else
			$this->balance= get_opening_balance("AC_ID5=$ac5",$this->enddate);
		$this->sum += $this->balance;
		return $this->row;
	}
}

# CLASS Accounts
#
# A Class to get Bank and Cash-Accounts related to a special Project (balances etc.)

Class BankAndCashAccounts_byProject extends BankAndCashAccounts {
	var $prjNo;
	
	function BankAndCashAccounts_byProject($prjNo,$enddate, $open_or_close_bal) {
		$this->prjNo = $prjNo;
		parent::BankAndCashAccounts("AC_ID1=$prjNo", $enddate,$open_or_close_bal);
	}
}

# print_ledger_sheet_head
#
# prints the head for a Ledger-Sheet

function print_ledger_sheet_head($startdate,$enddate,$ac5) {
	echo "<H2>Ledger Sheet</H2>";
	echo "From $startdate To $enddate<br>";
	echo "<hr>";
	$acs = get_acs_from_ac5($ac5);
	$ac1=$acs["AC_ID1"];
	$ac2=$acs["AC_ID2"];
	$ac3=$acs["AC_ID3"];
	$ac4=$acs["AC_ID4"];
	$ac5_desc=$acs["AC5_DESC"];
	echo "<b>CODE $ac1 $ac2 $ac3 $ac4 $ac5</b><br>";
	echo "<b>$ac5_desc</b>";
	echo "<hr>";
}

# print_project_transactions_head
#
# prints the head of a Project-Transactions-Sheet

function print_project_transactions_head($prj_no,$startdate,$enddate) {
	$prj_array=get_ac_array(1);
	$prj_desc=$prj_array[$prj_no];
	echo "<H2>Project Transactions</H2>";
	echo "<H3>$prj_desc</H3>";
	echo "<H3>From $startdate To $enddate</H3>";
	echo "<hr>";
}

###############################################################################
# MISCELLANEOUS FUNCTIONS
###############################################################################

# Registers global variables
# 
# This function takes global namespace $HTTP_*_VARS variables from input and if they exist,
# register them as a global variable so that scripts can use them.  The first argument
# signifies where to pull the variable names from, and should be one of GET, POST, COOKIE, ENV, or SERVER.
#

function pt_register()
{
	$num_args = func_num_args();
	$vars = array();

	if ($num_args >= 2) {
        	$method = strtoupper(func_get_arg(0));

		if (($method != 'SESSION') && ($method != 'GET') && ($method != 'POST') && ($method != 'SERVER') && ($method != 'COOKIE') && ($method != 'ENV')) {
			die('The first argument of pt_register must be one of the following: GET, POST, SESSION, SERVER, C
OOKIE, or ENV');
		}

		$varname = "HTTP_{$method}_VARS";
		global ${$varname};

		for ($i = 1; $i < $num_args; $i++) {
			$parameter = func_get_arg($i);

			if (isset(${$varname}[$parameter])) {
				global $$parameter;
				$$parameter = ${$varname}[$parameter];
			}
		}
	} else {
        	die('You must specify at least two arguments');
	}
}



# get_today_srd_string (get today system readable date string)
#
# returns a string containing today e.g. 2001-12-20

function get_today_srd_string() {
	$today_array = getdate();
	$today = $today_array["year"] . "-" . $today_array["mon"] . "-" . $today_array["mday"];
	return $today;
}

# get_today_hrd (get today human readable date string)
#
# returns a string containing today e.g. 20-12-2001

function get_today_hrd_string() {
	return conv_to_hrd(get_today_srd_string());
}

# conv_to_srd (convert human readable date to system readable date)
#
# gets a system readable datestring like 2001-12-20 and converts it to
# a human readable datestring like 20-12-2001

function conv_to_hrd($srdate) {
	# e.g. $sysdate = "2001-05-12"
	if(ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})",$srdate, $regs)) 
  		return "$regs[3]-$regs[2]-$regs[1]";
	elseif (ereg("([0-9]{1,2})-([0-9]{1,2})-([0-9]{4})",$srdate, $regs))
		return $srdate;
	else
		die("conv_to_hrd: Date-format ($srdate) not valid!");
}

# conv_to_hrd (convert system readable date to human readable date)
#
# gets a human readable datestring like 2001-20-12 and converts it to
# a system readable datestring like 2001-20-12

function conv_to_srd($hrdate) {
	# e.g. $hrdate = "12-05-2001";
	if(ereg ("([0-9]{1,2})-([0-9]{1,2})-([0-9]{4})",$hrdate, $regs)) 
  		return "$regs[3]-$regs[2]-$regs[1]";
	elseif (ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})",$hrdate, $regs)) 
		return $hrdate;
	else
		die("conv_to_srd: Date-format ($hrdate) not valid!");
}

# get_day
#
# returns the day of a datestring

function get_day($date) {
	if(ereg ("([0-9]{1,2})-([0-9]{1,2})-([0-9]{4})",$date, $regs)) 
  		return $regs[1];
	elseif (ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})",$date, $regs)) 
		return $regs[3];
	else
		die("get_day: Date-format ($date) not valid!");

}

# get_month
#
# returns the month of a datestring

function get_month($date) {
	if(ereg ("([0-9]{1,2})-([0-9]{1,2})-([0-9]{4})",$date, $regs)) 
  		return $regs[2];
	elseif (ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})",$date, $regs)) 
		return $regs[2];
	else
		die("get_month: Date-format ($date) not valid!");

}

# add_days
# 
# add days to a datestring and return new datestring

function add_days($date,$adddays) {
	$timestamp = mktime(0,0,0,get_month($date),get_day($date)+$adddays,get_year($date));
	return date("Y-m-d",$timestamp);
}

# get_year
#
# returns the year of a datestring

function get_year($date) {
	if(ereg ("([0-9]{1,2})-([0-9]{1,2})-([0-9]{4})",$date, $regs)) 
  		return $regs[3];
	elseif (ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})",$date, $regs)) 
		return $regs[1];
	else
		die("get_year: Date-format ($date) not valid!");

}



# get_month_name
#
# returns the shortcut-name of a month for a given datestring

function get_month_name($date) {
	$monthname = array(1 => "JAN",
			2 => "FEB",
			3 => "MAR",
			4 => "APR",
			5 => "MAY",
			6 => "JUN",
			7 => "JUL",
			8 => "AUG",
			9 => "SEP",
			10 => "OCT",
			11 => "NOV",
			12 => "DEC");
	return $monthname[get_month($date)];
}
# get_timestamp
#
# returns the UNIX-Timestamp from a given datestring

function get_timestamp($date) {
	if(ereg ("([0-9]{1,2})-([0-9]{1,2})-([0-9]{4})",$date, $regs)) 
  		return mktime(0,0,0,$regs[2],$regs[1],$regs[3]);
	elseif (ereg("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})",$date, $regs)) 
		return mktime(0,0,0,$regs[2],$regs[3],$regs[1]);
	else
		die("get_timestamp: Date-format ($date) not valid!");
}

# datediff_string
#
# Wrapper-function for datediff to use datestrings as arguments

function datediff($interval, $date1, $date2) {
	return datediff_timestamp($interval, get_timestamp($date1),get_timestamp($date2));
}

# datediff
#
# returns a datediff. $intervall can be "y","m","w","d","h","i"(minutes) or "s"

function datediff_timestamp($interval, $date1, $date2) {
	// Function roughly equivalent to the ASP "DateDiff" function
	$seconds = $date2 - $date1;
     
	switch($interval) {
	 case "y":
		list($year1, $month1, $day1) = split('-', date('Y-m-d', $date1));
		list($year2, $month2, $day2) = split('-', date('Y-m-d', $date2));
		$time1 = (date('H',$date1)*3600) + (date('i',$date1)*60) + (date('s',$date1));
		$time2 = (date('H',$date2)*3600) + (date('i',$date2)*60) + (date('s',$date2));
		$diff = $year2 - $year1;
		if($month1 > $month2) {
			$diff -= 1;
		} elseif($month1 == $month2) {
			if($day1 > $day2) {
				$diff -= 1;
			} elseif($day1 == $day2) {
				if($time1 > $time2) {
					$diff -= 1;
				}
			}
		}
		break;
	 case "m":
		list($year1, $month1, $day1) = split('-', date('Y-m-d', $date1));
		list($year2, $month2, $day2) = split('-', date('Y-m-d', $date2));
		$time1 = (date('H',$date1)*3600) + (date('i',$date1)*60) + (date('s',$date1));
		$time2 = (date('H',$date2)*3600) + (date('i',$date2)*60) + (date('s',$date2));
		$diff = ($year2 * 12 + $month2) - ($year1 * 12 + $month1);
		if($day1 > $day2) {
			$diff -= 1;
		} elseif($day1 == $day2) {
			if($time1 > $time2) {
				$diff -= 1;
			}
		}
		break;
	 case "w":
		// Only simple seconds calculation needed from here on
		$diff = floor($seconds / 604800);
		break;
	 case "d":
		$diff = floor($seconds / 86400);
		break;
	 case "h":
		$diff = floor($seconds / 3600);
		break;        
	 case "i":
		$diff = floor($seconds / 60);
		break;        
	 case "s":
		$diff = $seconds;
		break;        
	}    
	return $diff;
}

# deldir
#
# deletes a whole directory with all the content in it
function deldir($dir){
	if(!(is_dir($dir)))
		return;
	$current_dir = opendir($dir);
	while($entryname = readdir($current_dir)){
		if(is_dir("$dir/$entryname") and ($entryname != "." and $entryname!="..")){
			deldir("${dir}/${entryname}");
		} 
		elseif($entryname != "." and $entryname!=".."){
			unlink("${dir}/${entryname}");
		}
	}
	closedir($current_dir);
	rmdir(${dir});
}

# get_number_wording
#
# returns the wording for a number
function get_number_wording($number,$flag="") {
	$dig_1 = array( 1 => "one ",
				2 => "two ",
				3 => "three ",
				4 => "four ",
				5 => "five ",
				6 => "six ",
				7 => "seven ",
				8 => "eight ",
				9 => "nine ");
	if ($number < 10) return $dig_1[$number];
	if ($number == 11) return "eleven ";
	if ($number == 12) return "twelve ";
	if ($number == 13) return "thirteen ";
	if ($number == 14) return "fourteen ";
	if ($number == 15) return "fifteen ";
	if ($number == 16) return "sixteen ";
	if ($number == 17) return "seventeen ";
	if ($number == 18) return "eightteen ";
	if ($number == 19) return "nineteen ";
	
	if ($number < 100) {
		$dig_2 = array( 1 => "nil ",
				2 => "twenty ",
				3 => "thirty ",
				4 => "fourty ",
				5 => "fifty ",
				6 => "sixty ",
				7 => "seventy ",
				8 => "eighty ",
				9 => "ninety ");
		return $dig_2[floor($number/10)] . get_number_wording($number % 10); 
	}
	#if ($flag=="uptohundred") return;
	if ($number < 1000) # hundred
	  return get_number_wording(floor($number/100)) . "hundred " .  get_number_wording($number % 100);
	if ($number < 1000000) # thousand
	  return get_number_wording(floor($number/1000)) . "thousand " .  get_number_wording($number % 1000);
	if ($number < 1000000000) # million
	  return get_number_wording(floor($number/1000000)) . "million " .  get_number_wording($number % 1000000);
	if ($number < 1000000000000) # milliard
	  return get_number_wording(floor($number/1000000000)) . "billion " .  get_number_wording($number % 1000000000);
	return "sorry, number too big";

}

# get_ac
#
# try to make things easier. The stupid AC5 is not working. this
# helper-function is only for updating AC5.

function get_ac5($ac1,$ac2,$ac3,$ac4) {
	$db = getDBConnection();
	$result = mysql_query("SELECT AC_ID5 FROM `AC_CODE5` WHERE AC_ID1=$ac1 AND AC_ID2=$ac2 AND AC_ID3=$ac3 AND AC_ID4=$ac4", $db);
	checkMySQLError("get_ac5");
	$row1 = mysql_fetch_array($result);
	if (!($row = mysql_fetch_array($result))) {
		return $row1["AC_ID5"];
	}
	else {
		# Scheisst der Hund drauf
		#echo "<H1>We have a problem, more than Account for $ac1 $ac2 $ac3 $ac4</h1>";
		return $row1["AC_ID5"];
	}
}

# get_Code_HTML_Code_String
#
# returns a string with the AC_Code with html-non-breaking-spaces in between
# e.g.: "1 4 001 0" (the blanks are &nbsp;)

function get_HTML_code_string($row) {
	$ac1 = $row["AC_ID1"];
	$ac2 = $row["AC_ID2"];
	$ac3 = $row["AC_ID3"];
	$ac4 = $row["AC_ID4"];
	$ac5 = $row["AC_ID5"];
	$html_code_string = sprintf ("%01d&nbsp;&nbsp;%01d&nbsp;&nbsp;%03d&nbsp;&nbsp;%02d&nbsp;&nbsp;%03d", $ac1,$ac2,$ac3,$ac4,$ac5);
	return $html_code_string;
}

###############################################################################
# DOCUMENT FUNCTIONS
###############################################################################

# beginDocument 
#
# echos the HTML tags that get the document started, provides a central
# location for the background color, stylesheets, and whatnot.
function beginDocument ($title, $user,$javascript="") {
	beginDocument_noHead($title, $user, $javascript);
	echoDocumentHead();
}

# beginDocument_noHead
#
# echos the HTML tags that get the document started

# searchBar
#
# echos the HTML for the search Bar
function beginDocument_noHead ($title, $user,$javascript="") {
	global $BRAND, $BACKGROUND, $TOPBAR;
	echo "<html>\n";
	echo "<head>\n <title>[$BRAND] :: $title</title>\n";
	echo " <link rel=stylesheet href='accrp.css' type='text/css'>\n";
	echo $javascript;
	echo "</head>\n<body bgcolor='$BACKGROUND' text='black' leftmargin=0 topmargin=0 marginheight=0 marginwidth=0>\n";

}

function echoDocumentHead() {
	global $BRAND, $BACKGROUND, $TOPBAR;
	echo "<table bgcolor='$TOPBAR' width=100%><tr><td width='80%' align='left' class='header'>&nbsp;&nbsp;<a href='http://www.crp-bangladesh.org'><img src='images/CRP.png' height=45 border=0 alt='Home'></a> $BRAND: $user</td><td width='20%' align='right'>";
	if($user != "Not Logged In") {
  	echo "<a href='main.php'><img src='images/home.gif' width=24 height=24 border=0 alt='Home'></a>";
	}
  echo "&nbsp;&nbsp;</td></tr></table>";
	echo "<table cellpadding=10 width='100%' height='100%' border=0><tr><td valign=top>\n";
}



function searchBar() {
	global $lSearch, $lLast, $lAddress, $lEmail;
	openForm("searcher", "search.php");
		beginPrettyTable("2");
		echo "<tr><td>\n";
		echo "<b>$lSearch</b>: <input name='tosearch' type='text'>\n";
		echo "</td><td valign='center'>\n";
		echo "<select name='searcher' type='text'>\n";
		echo " <option value='last'>$lLast\n";
		echo " <option value='address'>$lAddress\n";
		echo " <option value='email'>$lEmail\n";
		echo "</select>\n";
		echo "<input type='image' align='center' src='images/go.gif' value='submit' border=0>\n";
		echo "</td></tr>\n";
		endPrettyTable();
		closeForm();
}

# endDocument
# 
# closes all the HTML opened by beginDocument
function endDocument() {
	echo "</td></tr></table></body></html>\n";
}
	

###############################################################################
# TABLE FUNCTIONS
###############################################################################

# beginPrettyTable
#
# handle the creation of a pretty, outlined table.  Uses the supplied
# bordercolor, bgcolor, colspan (for header) and text (text of header)
function beginPrettyTable () {
	global $TABLEBORDER, $INNERTABLE;

	# Allow us to take either 1 or 2 arguments
	$numargs = func_num_args();
	if($numargs == 0) {
		die ("Must provide a colspan to beginPrettyTable()\n");
	}
	$arg_list = func_get_args();
	for($i = 0; $i < $numargs; $i++) {
		if($i == 0) {
			$colspan = $arg_list[$i];
		}
		if($i == 1) {
			$header = $arg_list[$i];
		}
	}
	echo "<table bgcolor='$TABLEBORDER' cellpadding=1 cellspacing=0 border=0>\n";
	if ($header) {
		echo " <tr> <td colspan=$colspan align=right><div class='header'>$header&nbsp;</div></td> </tr>\n";
	}
	echo " <tr>\n  <td>\n";
	echo "   <table bgcolor='$INNERTABLE' cellpadding=2 cellspacing=0 border=0>\n";
	echo "    <tr>\n  <td>\n";
	echo "      <table bgcolor='$INNERTABLE' cellpadding=2 cellspacing=0 border=0>\n";
}

# endPrettyTable
#
# close all tags opened by beginPrettyTable
function endPrettyTable () {
	echo "      </table>\n";
	echo "     </td> </tr>\n";
	echo "   </table>\n";
	echo "  </td> </tr>\n";
	echo "</table>\n";
}

# startRow
#
# guess what, starts a row
function startRow() {
	echo " <tr>";
}

# endRow
#
# guess what, ends a row
function endRow() {
	echo " </tr>";
}


# beginBorderedTable
#
# when used in unison with beginPrettyTable, gives a nice visual cue around
# the data inside a table
function beginBorderedTable ($colspan) {
	global $TABLEBORDER, $INNERTABLE;

	echo "<tr>\n  <td>\n";
	echo " <table bgcolor='$INNERTABLE' cellpadding=2 cellspacing=2 border=0>\n";
}

# endBorderedTable
#
# close all tags opened by beginBorderedTable
function endBorderedTable () {
	echo "   </td> </tr>\n";
	echo "  </table>\n";
	echo " </td> </tr>\n";
}

# printRow
#
# take a Array as Parameter and print them in table
function printRow($array,$type="",$colspan="") {
	# the following if-statement makes it possible, to use the colspan-attribut via
	# the $colspan argument (it parses the string). An Example for $colspan would be:
	# "3,2:6,3"
	# This would make an colspan (2 columns) in the third column
	# and a colspan (3 columns) in the sixth column
	if ($colspan!="") {
		$colspan_temp_array=split(":",$colspan);
		foreach($colspan_temp_array as $key => $value) {
			$temp_array = split(",",$value);
			$colspan_array[$temp_array[0]] = $temp_array[1];
		}
	}
	if(!isset($array)) die ("Array is empty in printRow!");
	static $class = "odd";
	if ($type=="") 
		echo "<tr>"; 
	else {
		if($class == "odd") $class = "even"; else $class = "odd";
		echo "<tr class=$class>";
	}
	#$array = array_unique($array);
	$counter=1;
	foreach($array as $key => $value ) {
		if ($value=="") $value="&nbsp;";
		if (isSet($colspan_array[$counter])) 
			echo "<td colspan=" . $colspan_array[$counter] . ">$value</td>\n";
		else
			echo "<td>$value</td>";
		$counter++;
	}
	echo "</tr>";
}

###############################################################################
# FORM FUNCTIONS
###############################################################################

# openForm
#
# creates a form
function openForm ($name, $action,$additions="") {
	echo "<form method='post' name='$name' action='$action' $additions>";
}

# makeHiddenField
#
# creates a hidden field
function makeHiddenField ($name, $value) {
	echo "<input type=\"hidden\" name=\"$name\" value=\"$value\">\n";
}

# makeStaticField
#
# creates an uneditable field
function makeStaticField ($name, $value, $field, $size=30) {
	echo " <tr> <td><b>$field</b></td>";
	makePlainStaticField($name, $value, "",$size);
	echo "</tr>";
}

# makePlainStaticField
#
# creates an uneditable field
function makePlainStaticField ($name, $value, $field, $size=30) {
	if ($field != "") { echo "<td><b>$field</b></td>";}
	# it would be nice to use the "disabled"-attribute in the input-tag as well but then,
	# PHP does not know the variable !!
	echo "<td><input name='$name' type='text' value='$value' size='$size' readonly ></td> \n";
}

# makeTextField
#
# creates a textfield with the supplied paramaters
function makeTextField ($name, $value, $field, $size=30,$additions="") {
	echo " <tr>";
	makePlainTextField($name, $value, $field, $size,$additions);
	echo "</tr>\n";
}

# makePlainTextField
#
# creates a textfield with the supplied paramaters
function makePlainTextField ($name, $value, $field="", $size=30,$additions="") {
	if ($field != "") { echo "<td><b>$field</b></td>";}
	echo "<td><input name='$name' type='text' value='$value' size='$size' $additions></td>\n";
}

# makeLargeTextField
#
# creates a textarea with the supplied parameters
function makeLargeTextField ($name, $value, $field) {
	echo "<tr> <td valign='top'><b>$field:</b></td><td><textarea name=$name cols='48' rows='15' wrap='physical'>$value</textarea></td></tr>\n";
}

# makePlainDropBox
#
# A function to make a drop-Box. contents should be an array with keys as values
# and the value of the arraykey as text to use.

function makePlainDropBox ($name, $contents,  $field="", $selectedkey=-1 ) {
	if ($field != "") { echo "<td><b>$field</b></td>";}
	echo "<td><select name='$name'>\n";
	if ($selectedkey==-1 || $selectedkey == "") $contents[-1]="";
	foreach($contents as $key => $value ) {
		if ((string)$key == (string)$selectedkey) $selected = "selected";
		echo "<option value='$key' $selected> $value</option>\n";
		$selected="";
	}
	echo "</select></td>\n";
}

# makeDropBox
#
# Let's Do Code reuse 

function makeDropBox ($name, $contents, $field, $selectedkey=-1) {
	
	echo " <tr> <td><b>$field:</b></td>";
	makePlainDropBox($name, $contents, "", $selectedkey);
	echo "</tr>";
}

# makeSubmitter
#
# makes a submit field
function makeSubmitter () {
	echo " <tr> <td colspan=2 align=center>\n";
	echo "  <input type=image type='submit' value='submit' src='images/submit.gif' border=0 alt='Go!'>";
	echo " </td> </tr>\n";
}

# makeSubmitter
#
# makes a special-submitter field
function makeSpecialSubmitter ($value, $onclick="",$colspan=2) {
	echo " <tr> <td colspan=$colspan align=center>\n";
	echo "  <input type='submit' value='$value' border=0 onClick='$onclick'>";
	echo " </td> </tr>\n";
}

# makeButton
#
# makes a button
function makeButton ($value, $onclick="") {
	echo "  <td align=center>\n";
	echo "  <input type='button' value='$value' border=0 onClick='$onclick'>";
	echo " </td>\n";
}

# closeForm
#
# close a form
function closeForm () {
	echo "</form>";
}

###############################################################################
# LANGUAGES
###############################################################################

if(!(isset($sess_lang))) {
 	# different languages are disabled
	
	#$db = getDBConnection();
 	#$result = mysql_query("select * from Configuration");
	#$config_row = mysql_fetch_array($result);
	#require("lang/".$config_row["Language"]);
	require("lang/english.php");
} else {
	#require("lang/".$sess_lang);
	require("lang/english.php");
}

?>
