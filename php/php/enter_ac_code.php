<?
# Enter customer displays a form that allows the entry of a customer, and 
# handles the submission
session_start();

require("accrp.php");
require("security/secure.php");

beginDocument($lEnterCustomer, $sess_user);

if ($ac_desc) {
	# the variable $ac determines, which AC_CODE_Table to alter
	if (!$ac)
                $ac=1;
	
	$db = getDBConnection();
	$result = mysql_query("INSERT INTO AC_CODE$ac (AC${ac}_DESC) VALUES ('$ac_desc')", $db); 
	mysqlReport($result, "show list", "list_acs?showac=$ac", "show list");
	mysql_close($db);
} else {
	
	if (!$ac)
		die("Error: I don't know, which Table to alter !");
	?> 
	<table cellpadding=5 cellspacing=0 border=0 width='100%'>
	 <tr>
	  <td valign=top width='15%'>
	
           <? beginPrettyTable("1"); ?>
	   <tr>
	    <? echo "<td><a href='list_acs.php?showac=$ac'>show List</a></td>"; ?>
	   </tr>
	   <tr>
	    <td><hr color='black'></td>
	   </tr>
	   <? endPrettyTable();?>
	  </td>
	  <td valign=top align=center width='65%'>
	   <? openForm("enterAC_CODE$ac", $PHP_SELF);
	   beginPrettyTable("2", "enter AC_CODE$ac");
	    makeHiddenField("ac",$ac);
	    makeTextField("ac_desc", "", "AC_CODE$ac");
	    makeSubmitter();
	   endPrettyTable();
	   closeForm(); ?>
	   </td>
	  </table>
	  <?
	
}
endDocument();
?>
