<?
# enter_desig.php displays a form that allows the entry of a designation and
# handles the submission

require("accrp.php");
session_start();
require("security/secure.php");
pt_register('POST','desig_desc');

beginDocument("Enter Designation", $sess_user);

if ($desig_desc) {
	$db = getDBConnection();
	$result = mysql_query("INSERT INTO DESIG (DESIG_DESC) VALUES ('$desig_desc')", $db);
	checkMySQLError();
	mysqlReport($result, "Designation added", "main.php", "Home");
	mysql_close($db);
} else { 
	
		?> 
	<table cellpadding=5 cellspacing=0 border=0 width='100%'>
	 <tr>
	  <td valign=top width='15%'>
	
           <? beginPrettyTable("1"); ?>
	   <tr>
	    <? echo "<td><a href='list_desig.php'>show List</a></td>"; ?>
	   </tr>
	   <tr>
	    <td><hr color='black'></td>
	   </tr>
	   <? endPrettyTable();?>
	  </td>
	  <td valign=top align=center width='65%'>
	     <?

	openForm("enterDESIGNATION", $PHP_SELF);
	beginPrettyTable("2", "enter Designation");
	  makeTextField("desig_desc", "", "Designation");
	  makeSubmitter();
	endPrettyTable();
	closeForm();
	
	?>
	</td>
	  </table>
	  <?
	
}
endDocument();
?>
