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
     <td><a href='enter_ac_code.php?ac=1'><?echo "enter AC1_CODE"; ?></a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
    <tr>
     <td><a href='enter_ac_code.php?ac=3'><?echo "enter AC3_CODE"; ?></a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
    <tr>
     <td><a href='enter_ac_code.php?ac=4'><?echo "enter AC4_CODE"; ?></a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
    <tr>
     <td><a href='enter_ac5.php'><?echo "enter AC5_CODE"; ?></a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
    <tr>
     <td><a href='enter_desig.php'><?echo "enter Designation"; ?></a></td>
    </tr>
    <tr>
     <td><hr color='black'></td>
    </tr>
    <tr>
     <td><a href='enter_salary_element.php'><?echo "enter new Salary Element"; ?></a></td>
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
	<li>Here are once again some Informations ...<br>
      </td></tr>
      <? endPrettyTable(); ?>
     </td>
    </tr>
   </table>
  </td>
 </tr>
</table>
<? endDocument(); ?>
