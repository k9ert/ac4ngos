<?
# enter_salary_element.php displays a form that allows the entry of a new salary element and
# handles the submission
session_start();

require("accrp.php");
require("security/secure.php");

beginDocument("Enter Salary Element", $sess_user);

if ($sal_desc) {
	$db = getDBConnection();
	$result = mysql_query("INSERT INTO SAL_ID (SAL_DESC,ID_TP) VALUES ('$sal_desc','$id_tp')", $db);
	checkMySQLError();
	mysqlReport($result, "Salary Element sucessfully added!", "main.php", "Home");
	mysql_close($db);
} else { 
	
		?> 
	<table cellpadding=5 cellspacing=0 border=0 width='100%'>
	 <tr>
	  <td valign=top width='15%'>
	
           <? beginPrettyTable("1"); ?>
	   <tr>
	    <? echo "<td><a href='list_salary_elements.php'>show List</a></td>"; ?>
	   </tr>
	   <tr>
	    <td><hr color='black'></td>
	   </tr>
	   <? endPrettyTable();?>
	  </td>
	  <td valign=top align=center width='65%'>
	     <?

	openForm("salaryelement_form", $PHP_SELF);
	beginPrettyTable("2", "enter salary element");
	  makeTextField("sal_desc", "", "Salary Description");
	  makeDropBox("id_tp",array("I"=>"I","E"=>"E"),"Expenses/Income");
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
