<?
# enter_ac5.php displays a form that allows the entry of a AC5 and
# handles the submission
require("accrp.php");
session_start();
require("security/secure.php");

pt_register('POST','ac5_desc','ac_id1','ac_id2','ac_id3','ac_id5');

beginDocument("Enter AC_Code 5", $sess_user);

if ($ac5_desc) {
	foreach ($HTTP_POST_VARS as $key => $value) {
		if ($value == -1) {
			report($result, "Please, don't leave any field empty !", "main.php", "Home");
			exit;
		}
	}
	$db = getDBConnection();
	$result = mysql_query("INSERT INTO AC_CODE5 (AC_ID1, AC_ID2, AC_ID3, AC_ID4, AC5_DESC) VALUES ('$ac_id1', '$ac_id2', '$ac_id3', '$ac_id4', '$ac5_desc')", $db);
	checkMySQLError();
	mysqlReport($result, $lCustomerAddition, "main.php", "Home");
	mysql_close($db);
} else { 
	
	$ac1_array = get_ac_array(1);
	$ac2_array = get_ac_array(2);
	$ac3_array = get_ac_array(3);
	$ac4_array = get_ac_array(4);
	
	?>
	<table cellpadding=5 cellspacing=0 border=0 width='100%'>
	 <tr>
	  <td valign=top width='15%'>
	
           <? beginPrettyTable("1"); ?>
	   <tr>
	    <? echo "<td><a href='list_ac5.php'>show List</a></td>"; ?>
	   </tr>
	   <tr>
	    <td><hr color='black'></td>
	   </tr>
	   <? endPrettyTable();?>
	  </td>
	  <td valign=top align=center width='65%'>
	<?
	openForm("enterAC_CODE5", $PHP_SELF);
	beginPrettyTable("2", $lEnterCustomer);
	  makeDropBox("ac_id1", $ac1_array,"AC_CODE1");
	  makeDropBox("ac_id2", $ac2_array,"AC_CODE2");
	  makeDropBox("ac_id3", $ac3_array,"AC_CODE3");
	  makeDropBox("ac_id4", $ac4_array,"AC_CODE4");
	  makeTextField("ac5_desc", "", "AC_CODE5");
	  makeSubmitter();
	endPrettyTable();

	closeForm();?>
	   </td>
	  </table>
	  <?

}
endDocument();
?>
