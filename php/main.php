<?
require("accrp.php");
session_start();

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
     <td><a href='reports.php'><?echo "Reports"; ?></a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
    <tr>
     <td><a href='enter_trans.php?vr_tp=CR'><?echo "enter CreditVoucher"?></a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
    <tr>
     <td><a href='enter_trans.php?vr_tp=DB'><?echo "enter DebitVoucher"?></a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
    <tr>
     <td><a href='enter_journalvoucher'><?echo "enter JournalVoucher"?></a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
    <tr>
     <td><a href='remove_voucher.php'><?echo "remove Voucher"?></a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>

    <tr>
     <td><a href='enter_ac_codes.php'><?echo "enter AC_CODES"; ?></a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
    <tr>
     <td><a href='enter_personal.php'><?echo "enter personal"?></a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
    <tr>
     <td><a href='enter_loan.php?type=PF'><?echo "enter PF-Loan"?></a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
    <tr>
     <td><a href='enter_loan.php?type=CUF'><?echo "enter CUF-Loan"?></a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>

    <tr>
     <td><a href='enter_dept.php'><?echo "enter department"?></a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
    <tr>
     <td><a href='enter_openingbalance.php'><?echo "enter opening-balance"?></a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
    <tr>
     <td><a href='download_backup.php'><?echo "Download Backup"?></a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>


		<?if($sess_admin == "Yes") {?>
    <tr>
     <td><a href='administration.php'><?echo $lAdmin;?></a></td>
    </tr>
		<?}?>
   
      <? endPrettyTable(); ?>
  </td>
  <td valign=top align=center width='65%'>
   <table cellpadding=0 cellspacing=0 border=0 width='75%'>
    <tr>
     <td>
      <? beginPrettyTable("1","News"); ?>
      <tr><td>
	<b>Yeah!</b><br>
	<li>Here, you can put informations for the Users.<br>
      </td></tr>
      <? endPrettyTable(); ?>
     </td>
    </tr>
   </table>
  </td>
  <td valign=top width='20%'>
   <table cellpadding=5 cellspacing=0 border=0>
    <tr>
     <td valign=top>
      <table cellpadding=5 cellspacing=0 border=0>
       <tr>
        <td valign=top>
         <form name='getuser' action='showprofile.php'>
         <? beginPrettyTable("2", $lRetrieveByCustomerID); ?>
          <tr>
           <td><input type='text' name='CustomerID' value=''></td>
           <td><input type='image' src='images/go.gif' value='submit' border=0></td>
          </tr>
         <? endPrettyTable(); ?>
         </form>
        </td>
       </tr>
       <tr>
        <td valign=top>
         <form name='searchuser' action='search.php'>
				 <input type='hidden' name='searcher' value='last'>
         <? beginPrettyTable("2", $lSearchByLastName); ?>
           <tr>
            <td><input type='text' name='tosearch' value=''></td>
            <td><input type='image' src='images/go.gif' value='submit' border=0></td>
           </tr>
         <? endPrettyTable(); ?>
         </form>
        </td>
       </tr>
       <tr>
        <td valign=top>
         <form name='getuser' action='search.php'>
				 <input type='hidden' name='searcher' value='address'>
         <? beginPrettyTable("2", $lSearchByAddress); ?>
          <tr>
           <td><input type='text' name='tosearch' value=''></td>
           <td><input type='image' src='images/go.gif' value='submit' border=0></td>
          </tr>
         <? endPrettyTable(); ?>
         </form>
        </td>
       </tr>
      </table>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
<? endDocument(); ?>
