<?
session_start();

require("accrp.php");
require("security/secure.php");

beginDocument("Main", $sess_user);

$db = getDBConnection();

#$result = mysql_query("SELECT * from Configuration", $db);
# $configuration = mysql_fetch_array($result);

$version = 0.1 ;# $configuration["Version"];

echo "<b>&nbsp;&nbsp;accrp $version</b>\n";
?>
<hr>
<table cellpadding=5 cellspacing=0 border=0 width='100%'>
 <tr>
  <td valign=top width='15%'>
   <? beginPrettyTable("1"); ?>
    <tr>
     <td><a href='list_ledger_sheet.php'>Ledger sheet</a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
    <tr>
     <td><a href='list_project_balances.php'>Project Balance</a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>

    <tr>
     <td><a href='list_bank_cash.php'>Bank and Cash</a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
    <tr>
     <td><a href='list_project_transactions'>Project Transactions</a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
    <tr>
     <td><a href='print_money_receipt.php'>Print money Receipt</a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
    <tr>
     <td><a href='list_personal_salary.php'>list personal salary</a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
    <tr>
     <td><a href='list_salary_elements.php'>list salary elements</a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
    <tr>
     <td><a href='list_loan.php?type=PF'>PF Loan Statement</a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
    <tr>
     <td><a href='list_loan.php?type=CUF'>CUF Loan Statement</a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
     <tr>
     <td><a href='list_advance.php'>Schedule of Advance</a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>


		<?if($sess_admin == "Yes") {?>
    <tr>
     <td><a href='administration.php'><?echo $lAdmin;?></a></td>
    </tr>
		<?}?>
       		<tr>
 		 <td><a href='index.php'><?echo $lLogout?></a></td>
   <? endPrettyTable(); ?>
  </td>
  <td valign=top align=center width='65%'>
   <table cellpadding=0 cellspacing=0 border=0 width='75%'>
    <tr>
     <td>
      <? beginPrettyTable("1","News"); ?>
      <tr><td>
	<b>Yeah!</b><br>
	<li>And here, one can give special Informations about Reports !<br>
      </td></tr>
      <? endPrettyTable(); ?>
     </td>
    </tr>
   </table>
  </td>
   </tr>
</table>
<? endDocument(); ?>
