<?
# enter_dept.php displays a form that allows the entry of a department and
# handles the submission


# I don't know what this is doing.
# I've copied it from the origin Project
session_start();

# see page 218 of "beginning PHP". Nearly the same than include(...)
# (I don't know the difference between require and include)
require("accrp.php");
require("security/secure.php");

# Prints all that HTML-stuff, we don't want to deal with
beginDocument("Enter Department", $sess_user);

# This PHP-File (and nearly all the others) have two parts. One is showing
# the Form, the other one is doing database-stuff with the data, typed
# in the form


if ($dept_name) { # means nearly the same than "if user already typed in data"
	# get a connection to the database
	$db = getDBConnection();
	# insert the new department
	$result = mysql_query("INSERT INTO DEPT (DEPT_NAME) VALUES ('$dept_name')", $db);
	# check if anything went wrong
	checkMySQLError();
	# Give the user a report
	mysqlReport($result, "Department sucessfully added", "main.php", "Home");
	# close connection to database
	mysql_close($db);
} 
else # the user haven't typed in any Data
{ 
	## the following code is ignored. perhaps, we will use it sometimes
	## later, when we make the possibility to edit a department
	
	#if ($EMP_ID3) {
	#	$result = mysql_query("select * from DEPT where DEPT_ID = $dept_id");
	#	checkMySQLError();
	#	$edit = mysql_fetch_array($result);
	#}

	# With [Questionmark]> we will leave the PHP-mode. 
	# Everything after this, will
	# get directly to the html-page
		?> 
	<table cellpadding=5 cellspacing=0 border=0 width='100%'>
	 <tr>
	  <td valign=top width='15%'>
	
           <? beginPrettyTable("1"); # this is again some PHP-Code?> 
	   <tr>
	    <? echo "<td><a href='list_dept.php'>show List</a></td>"; ?>
	   </tr>
	   <tr>
	    <td><hr color='black'></td>
	   </tr>
	   <? endPrettyTable(); # it just print out some HTML?>
	  </td>
	  <td valign=top align=center width='65%'>
	     <? # Ok, we are again in PHP-Mode
	
	# Prints all that HTML-stuff, we don't want to deal with
	openForm("enterDEPARTMENT", $PHP_SELF);
	# Prints all that HTML-stuff, we don't want to deal with
	beginPrettyTable("2", "enter DEPARTMENT");
	  # makeHiddenField("dept_id", $edit["DEPT_ID"]); This one, we perhaps use it later
	  
	  # "dept_name" is the name of the form (see html-page)
	  # It will get a variable if this Program is called once again
	  # (see upper "if"-Statement)
	  makeTextField("dept_name", $edit["DEPT_NAME"], "Department");
	  # print out the code for that "submit"-Button
	  makeSubmitter();
	# Prints all that HTML-stuff, we don't want to deal with
	endPrettyTable();
	# Prints all that HTML-stuff, we don't want to deal with
	closeForm();
	
	# sometimes, we have to print it out directly with "echo"
	echo "</td> </table>";
	
}
# Prints all that HTML-stuff, we don't want to deal with
endDocument();
?>
